<?php

namespace App\Http\Controllers\Admin;

use App\Facades\AuthAdminFacade;
use App\Facades\AuthFacade;
use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\AdministratorRepositoryInterface;
use App\Repositories\Interfaces\LogAdminRepositoryInterface;
use App\Services\AuthAdmin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdminAuthentication extends Controller
{
    /**
     * Validated username
     * Will be filled via $validator safe method or session
     */
    protected $username;

    /**
     * Validated password
     * Will be filled via $validator safe method or session
     */
    protected $password;

    /**
     * Shows login page
     */
    public function index()
    {
        if(AuthAdminFacade::isLoggedIn()) {
            return redirect()->route('admin-applications');
        }

        return view('admin.authentication');
    }

    // ---------------------------------------------------------------------------------------------------

    /**
     * Checks if user exists, create if not exists then send an otp to user
     */
    public function attempt(Request $request, AdministratorRepositoryInterface $adminRepo, LogAdminRepositoryInterface $logAdminRepo)
    {
        // Validate input data
        $validator = Validator::make($request->all(), [
            'username' => ['required'],
            'password' => ['required'],
//            'captcha' => ['bail','required', 'captcha'],
        ]);        
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            return response()->apiRes(['errors' => $errors], trans('validation.error'), false, 400);
        }

        $validated = $validator->validated();
        $this->username = $validated['username'];
        $this->password = $validated['password'];
        
        // Check if admin exists
        $user = $adminRepo->findByUsername($this->username);        
        if ( ! $user ) {            
            return response()->apiRes(null, 'خطا 1: ورود صحیح نیست.', false, 401);
        }

        // check if admin failed logins is ore than 10
        // if($user->failed_logins > 2) {
        //     return response()->apiRes(null, 'تعداد دفعات تلاش شما برای ورود بیش از حد مجاز است. امکان ورود وجود ندارد. لطفا با مدیر سیستم تماس بگیرید.', false, 403);
        // }

        // check if admin is not active
        if($user->is_active == 0) {
            return response()->apiRes(null, 'کاربری شما غیر فعال است.', false, 403);
        }

        // check password
        if($user->password !== AuthAdmin::hash_password($this->username, $this->password))  {
            // increase failed login
            $new_failed_logins = (int) $user->failed_logins + 1;
            $adminRepo->update($user->admin_id, ['failed_logins' => $new_failed_logins]);
            return response()->apiRes(null, 'خطا 2: اطلاعات ورود صحیح نیست.', false, 401);
        }

        // reset failed logins
        $adminRepo->update($user->admin_id, ['failed_logins' => 0]);
        // set session
        AuthAdminFacade::forceLogin($user->admin_id);
        $log = ['admin_id' => AuthAdminFacade::getAdminId(),'action' => 'login', 'ip' => $request->ip()];
        $logAdminRepo->create($log);
        return response()->apiRes(null, 'ورودبا موفقیت انجام شد', true, 200, '/admin/application?status_title=all');  
    }
    
    // ---------------------------------------------------------------------------------------------------

    public function logout(Request $request, AuthAdmin $auth)
    {
        $auth->logout();
        return response()->redirectTo('/admin/authentication');
    }
}
