<?php

namespace App\Services;

use App\Repositories\Interfaces\UserRepositoryInterface;

class Auth {

    protected $user_repository;

    public function __construct(UserRepositoryInterface $userRep)
    {
        $this->user_repository = $userRep;
    }

    // ---------------------------------------------------------------------------------------------------

    /**
     * Fetches a user from datanase
     */
    protected function fetchUserFromDatabase($user_id)
    {
        $user = $this->user_repository->findByUserId($user_id);
        return $user;
    }

    // ---------------------------------------------------------------------------------------------------

    /**
     * Login user with $user_id
     * @param $user_id string 
     */
    public function forceLogin($user_id){
        $user = $this->fetchUserFromDatabase($user_id);
        $this->login($user);
    }

    // ---------------------------------------------------------------------------------------------------

    /**
     * Login user
     */
    protected function login($user)
    {
        $this->logout();
        session([
            'loggedIn' => true,
            'user' => $user,
        ]);
    }

    // ---------------------------------------------------------------------------------------------------

    /**
     * Logouts user by flashing all sessions
     */
    public function logout()
    {
        session()->forget(['loggedIn', 'user']);
        session()->flush();
    }

    // ---------------------------------------------------------------------------------------------------

    /**
     * Returns all detials of user
     */
    public function getUser()
    {
        if(session('loggedIn'))
        {
            $user_id = session('user')->user_id;
            return $this->fetchUserFromDatabase($user_id);
        }

        return false;
    }

    // ---------------------------------------------------------------------------------------------------

    /**
     * Retuens user id
     */
    public function getUserId()
    {
        if($user = $this->getUser()){
            return $user->user_id;
        }
    }

    // ---------------------------------------------------------------------------------------------------

    /**
     * Determines if user is logged in
     */
    public function isLoggedIn()
    {
        if(session('loggedIn') && session('user') )
        {
            return session('loggedIn');
        }

        return false;
    }

    // ---------------------------------------------------------------------------------------------------

}