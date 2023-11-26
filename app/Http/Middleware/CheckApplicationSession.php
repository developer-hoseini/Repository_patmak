<?php

namespace App\Http\Middleware;

use App\Repositories\Interfaces\ApplicationRepositoryInterface;
use Closure;

class CheckApplicationSession
{
    protected $appRep;

    public function __construct(ApplicationRepositoryInterface $appRep)
    {
        $this->appRep = $appRep;
    }
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Check session to get current application id
        $application = $this->appRep->find($request->session()->get('application_id', 0));
        if( ! $application ) {
            return response()->apiRes(null, 'درخواست شما پیدا نشد', false, 400);
        }

        // Check application status if it's not editale
        if( $application->status_id != 1 ) {
            return response()->apiRes(null, 'بعد از تایید نهایی فرم امکان تغییر مشخصات وجود ندارد', false, 400);
        }

        $request->merge(['application' => $application]);

        return $next($request);
    }
}
