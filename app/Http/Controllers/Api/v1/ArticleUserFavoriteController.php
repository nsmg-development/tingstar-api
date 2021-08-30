<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Repositories\Article\ArticleUserFavoriteRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ArticleUserFavoriteController extends Controller
{
    protected ArticleUserFavoriteRepositoryInterface $articleUserFavorite;

    public function __construct(ArticleUserFavoriteRepositoryInterface $articleUserFavorite)
    {
        $this->articleUserFavorite = $articleUserFavorite;
    }

    public function index(Request $request): Response
    {
        $result = $this->articleUserFavorite->list($request);

        return $this->response($result);
    }

    public function setFavorite(Request $request): Response
    {
        $result = $this->articleUserFavorite->setFavorite($request);

        return $this->response($result);
    }
}
