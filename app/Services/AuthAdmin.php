<?php

namespace App\Services;

use App\Repositories\Interfaces\AdministratorRepositoryInterface;

class AuthAdmin {

    protected $admin_repository;

    public function __construct(AdministratorRepositoryInterface $adminRepository)
    {
        $this->admin_repository = $adminRepository;
    }

    // ---------------------------------------------------------------------------------------------------

    /**
     * Fetches a user from datanase
     */
    protected function fetchUserFromDatabase($user_id)
    {
        $admin = $this->admin_repository->findByAdminId($user_id);
        return $admin;
    }

    // ---------------------------------------------------------------------------------------------------

    /**
     * Login user with $user_id
     * @param $user_id string 
     */
    public function forceLogin($admin_id){
        $user = $this->fetchUserFromDatabase($admin_id);
        $this->login($user);
    }

    // ---------------------------------------------------------------------------------------------------

    /**
     * Login user
     */
    protected function login($admin)
    {
        $this->logout();
        session([
            'adminLoggedIn' => true,
            'admin' => $admin,
        ]);
    }

    // ---------------------------------------------------------------------------------------------------

    /**
     * Logouts user by flashing all sessions
     */
    public function logout()
    {
        session()->forget(['adminLoggedIn', 'admin']);
        session()->flush();
    }

    // ---------------------------------------------------------------------------------------------------

    /**
     * Returns all detials of user
     */
    public function getAdmin()
    {
        if(session('adminLoggedIn'))
        {
            $admin_id = session('admin')->admin_id;
            return $this->fetchUserFromDatabase($admin_id);
        }

        return false;
    }

    // ---------------------------------------------------------------------------------------------------

    /**
     * Retuens user id
     */
    public function getAdminId()
    {
        if($admin = $this->getAdmin()){
            return $admin->admin_id;
        }
    }

    // ---------------------------------------------------------------------------------------------------

    /**
     * Retuens admin type
     */
    public function getAdminTypeId()
    {
        if($admin = $this->getAdmin()){
            return $admin->admin_type_id;
        }
    }

    // ---------------------------------------------------------------------------------------------------

    /**
     * Retuens admin type
     */
    public function getAdminProvinceId()
    {
        if($admin = $this->getAdmin()){
            return $admin->province_id;
        }
    }

    // ---------------------------------------------------------------------------------------------------

    /**
     * Retuens user id
     */
    public function getAdminName()
    {
        if($admin = $this->getAdmin()){
            return $admin->admin_name;
        }
    }

    // ---------------------------------------------------------------------------------------------------

    /**
     * Determines if user is logged in
     */
    public function isLoggedIn()
    {
        if(session('adminLoggedIn') && session('admin') )
        {
            return session('adminLoggedIn');
        }

        return false;
    }

    // ---------------------------------------------------------------------------------------------------

    public static function hash_password($username, $password)
    {
        return md5($username . 'p@tm@k2020^Mrud!' . $password);
    }

}