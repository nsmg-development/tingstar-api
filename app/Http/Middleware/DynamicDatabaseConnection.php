<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class DynamicDatabaseConnection
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->header('C9')) {
            $connection = strtoupper($request->header('C9'));

            Config::set([
                'database.connections.curator9-common.host' => env($connection.'_COMMON_HOST'),
                'database.connections.curator9-common.port' => env($connection.'_COMMON_PORT'),
                'database.connections.curator9-common.database' => env($connection.'_COMMON_DATABASE'),
                'database.connections.curator9-common.username' => env($connection.'_COMMON_USERNAME'),
                'database.connections.curator9-common.password' => env($connection.'_COMMON_PASSWORD')
            ]);

            DB::purge('curator9-common');
            DB::reconnect('curator9-common');

            Config::set([
                'database.connections.curator9.host' => env($connection.'_HOST'),
                'database.connections.curator9.port' => env($connection.'_PORT'),
                'database.connections.curator9.database' => env($connection.'_DATABASE'),
                'database.connections.curator9.username' => env($connection.'_USERNAME'),
                'database.connections.curator9.password' => env($connection.'_PASSWORD')
            ]);

            DB::purge('curator9');
            DB::reconnect('curator9');
        }

        return $next($request);
    }
}
