<?php 

namespace App\Providers;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\ServiceProvider;

class ResponseMacroServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Response::macro('apiRes', function (array $data = null, string $message = 'OK', bool $status =  true,  int $http_res_code = 200, string $redirect = null) {

            $res = [
                'status' => $status,
                'message' => $message,
                'code' => $http_res_code,
                'data' => $data,
                'redirect' => $redirect
            ];
            return Response::make($res);
        });
    }
}