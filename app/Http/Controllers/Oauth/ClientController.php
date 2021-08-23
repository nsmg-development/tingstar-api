<?php

namespace App\Http\Controllers\Oauth;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Validation\Factory as ValidationFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Laravel\Passport\Client;
use Laravel\Passport\ClientRepository;
use Laravel\Passport\Http\Rules\RedirectRule;
use Laravel\Passport\Passport;
use Symfony\Component\HttpFoundation\Response;

class ClientController extends Controller
{
    /**
     * The client repository instance.
     *
     * @var \Laravel\Passport\ClientRepository
     */
    protected ClientRepository $clients;

    /**
     * The validation factory implementation.
     *
     * @var \Illuminate\Contracts\Validation\Factory
     */
    protected ValidationFactory $validation;

    /**
     * The redirect validation rule.
     *
     * @var \Laravel\Passport\Http\Rules\RedirectRule
     */
    protected RedirectRule $redirectRule;

    /**
     * Create a client controller instance.
     *
     * @param  \Laravel\Passport\ClientRepository  $clients
     * @param  \Illuminate\Contracts\Validation\Factory  $validation
     * @param  \Laravel\Passport\Http\Rules\RedirectRule  $redirectRule
     * @return void
     */
    public function __construct(
        ClientRepository $clients,
        ValidationFactory $validation,
        RedirectRule $redirectRule
    ) {
        $this->clients = $clients;
        $this->validation = $validation;
        $this->redirectRule = $redirectRule;
    }

    /**
     * Get all of the clients for the authenticated user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function forUser(Request $request): Collection
    {
        $userId = $request->user()->getAuthIdentifier();

        $clients = $this->clients->activeForUser($userId);

        if (Passport::$hashesClientSecrets) {
            return $clients;
        }

        return $clients->makeVisible('secret');
    }

    /**
     * @param Request $request
     * @param $providers_id
     * @param $provider_contents_id
     * @return mixed
     */
    public function forContent(Request $request, $providers_id, $provider_contents_id)
    {
        // $userId = $request->user()->getKey();

        return Client::where('provider_contents_id', $provider_contents_id)->get()->makeVisible('secret');
    }

    /**
     * Store a new client.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Laravel\Passport\Client|array
     */
    public function store(Request $request)
    {
        $this->validation->make($request->all(), [
            'name' => 'required|max:191',
            'redirect' => ['required', $this->redirectRule],
            'confidential' => 'boolean',
        ])->validate();

        // $client = $this->clients->create(
        //     $request->user()->getAuthIdentifier(), $request->name, $request->redirect,
        //     null, false, false, (bool) $request->input('confidential', true)
        // );

        $confidential = (bool) $request->input('confidential', true);
        $personal_access_client = (bool) $request->input('personal_access_client', false);
        $password_client = (bool) $request->input('password_client', false);

        $client = Passport::client()->forceFill([
            'user_id' => $request->user()->getAuthIdentifier(),
            'name' => $request->name,
            'secret' => ($confidential || $personal_access_client) ? Str::random(40) : null,
            'provider' => $request->provider,
            'redirect' => $request->redirect,
            'personal_access_client' => $personal_access_client,
            'password_client' => $password_client,
            'revoked' => false,
        ]);

        $client->save();


        if (Passport::$hashesClientSecrets) {
            return ['plainSecret' => $client->plainSecret] + $client->toArray();
        }

        return $client->makeVisible('secret');
    }

    /**
     * Update the given client.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param string $clientId
     * @return \Illuminate\Http\Response|\Laravel\Passport\Client
     */
    public function update(Request $request, string $clientId)
    {
        $client = $this->clients->findForUser($clientId, $request->user()->getAuthIdentifier());

        if (! $client) {
            return new \Illuminate\Http\Response('', 404);
        }

        $this->validation->make($request->all(), [
            'name' => 'required|max:191',
            'redirect' => ['required', $this->redirectRule],
        ])->validate();

        return $this->clients->update(
            $client, $request->name, $request->redirect
        );
    }

    /**
     * Delete the given client.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param string $clientId
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, string $clientId): Response
    {
        $client = $this->clients->findForUser($clientId, $request->user()->getAuthIdentifier());

        if (! $client) {
            return new Response('', 404);
        }

        $this->clients->delete($client);

        return new Response('', Response::HTTP_NO_CONTENT);
    }
}
