<?php

namespace App\Providers;

use App\Repositories\Interfaces\AdministratorRepositoryInterface;
use App\Services\AuthAdmin;
use App\Repositories\Interfaces\LogIpgCallRepositoryInterface;
use App\Repositories\Interfaces\LogLegalPersonRepositoryInterface;
use App\Repositories\Interfaces\LogOtpRepositoryInterface;
use App\Repositories\Interfaces\LogSabteAhvalRepositoryInterface;
use App\Repositories\Interfaces\PaymentRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Services\Auth;
use App\Services\FakeSabteahval;
use App\Services\FakeSabteasnad;
use App\Services\FakeShahkar;
use App\Services\FakeSms;
use App\Services\PishkhanPayment;
use App\Services\Sabteahval;
use App\Services\Sabteasnad;
use App\Services\smsChaapaar;
use App\Services\SmsMagfa;
use Illuminate\Support\ServiceProvider;


class FacadeServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('sabte-ahval', function(){            
            // return new FakeSabteahval($this->app->make(LogSabteAhvalRepositoryInterface::class));
            return new Sabteahval($this->app->make(LogSabteAhvalRepositoryInterface::class));
        });

        $this->app->bind('sabte-asnad', function(){
            // return new FakeSabteasnad($this->app->make(LogLegalPersonRepositoryInterface::class));
            return new Sabteasnad($this->app->make(LogLegalPersonRepositoryInterface::class));
        });

        $this->app->bind('shahkar', function(){
            return new FakeShahkar();
        });

        $this->app->bind('Auth', function(){
            return new Auth($this->app->make(UserRepositoryInterface::class));
        });

        $this->app->bind('AuthAdmin', function(){
            return new AuthAdmin($this->app->make(AdministratorRepositoryInterface::class));
        });

        $this->app->bind('sms-sender', function(){
            return new FakeSms($this->app->make(LogOtpRepositoryInterface::class));
        });

        $this->app->bind('otp-sender', function(){
            // return new FakeSms($this->app->make(LogOtpRepositoryInterface::class));
            // return new SmsMagfa($this->app->make(LogOtpRepositoryInterface::class));
            return new smsChaapaar($this->app->make(LogOtpRepositoryInterface::class));
        });

        $this->app->bind('payment', function(){
            return new PishkhanPayment($this->app->make(LogIpgCallRepositoryInterface::class), $this->app->make(PaymentRepositoryInterface::class));
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
