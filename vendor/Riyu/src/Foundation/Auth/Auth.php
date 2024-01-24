<?php
namespace Riyu\Foundation\Auth;

use Riyu\Session\Store;

class Auth
{
    protected $session;

    protected $user;

    public function __construct(Store $session)
    {
        $this->session = $session;
    }

    public function login($user)
    {
        $this->session->set('user', $user->storeToSession());
    }

    public function logout()
    {
        $this->session->remove('user');
    }

    /**
     * Get the user object.
     * 
     *  \Riyu\Foundation\Auth\User|null
     */
    public function user()
    {
        if (is_null($this->user)) {
            if (!$this->session->has('user')) {
                return null;
            }
            $user = $this->session->get('user');
            $model = $user['namespace']::find($user['id']);
            $this->user = $model;
        }

        return $this->user;
    }

    public function check()
    {
        return !is_null($this->user());
    }

    public function guest()
    {
        return !$this->check();
    }

    public function __debugInfo()
    {
        return [
            'user' => $this->user(),
        ];
    }
}