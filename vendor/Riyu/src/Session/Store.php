<?php
namespace Riyu\Session;

use Riyu\Foundation\Application;

class Store
{
    /**
     * The session object.
     * 
     * @var \Riyu\Session\Session
     */
    protected $session;

    public function __construct(\Riyu\Foundation\Application $app = null)
    {
        $path = $app->getBasePath();
        if (!is_null($path)) {
            $path = $path . '/storage/sessions';
            $path = $this->savepath($path);
        }

        $this->session = new Session($path ?? null);
    }

    public function addAll(array $data)
    {
        foreach ($data as $key => $value) {
            $this->set($key, $value);
        }
    }

    public function set($key, $value)
    {
        $this->session->set($key, $value);
    }

    public function get($key, $default = null)
    {
        if ($this->session->has($key)) {
            return $this->session->get($key);
        }

        return $default;
    }

    public function has($key)
    {
        return $this->session->has($key);
    }

    public function remove($key)
    {
        $this->session->remove($key);
    }

    public function clear()
    {
        $this->session->destroy();

        if ($this->session->status() == Session::NONE) {
            $this->session = new Session();
        }
    }

    public function all()
    {
        return $this->session->all();
    }

    public function regenerate()
    {
        $this->session->regenerate();
    }

    private function savepath($path)
    {
        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }

        if (!is_writable($path)) {
            chmod($path, 0777);
        }
        
        return $path;
    }

    public function destroy()
    {
        $this->session->destroy();
    }

    public function previousUrl()
    {
        return $this->get('_previous_url');
    }

    public function setPreviousUrl($url)
    {
        $this->set('_previous_url', $url);
    }
}