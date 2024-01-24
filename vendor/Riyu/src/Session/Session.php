<?php
namespace Riyu\Session;

class Session
{
    public const DISABLED = 0;
    public const NONE = 1;
    public const ACTIVE = 2;

    protected $session;

    protected $status = self::NONE;

    protected $id;

    protected $name;

    protected $path;

    public function __construct($path = null)
    {
        $this->path = $path;

        $this->start();
    }

    private function start()
    {
        if (session_status() == PHP_SESSION_DISABLED || session_status() == PHP_SESSION_NONE) {
            if (!is_null($this->path)) {
                session_save_path($this->path);
            }

            session_start();

            $this->status = self::ACTIVE;
        }

        $this->session = &$_SESSION;

        $this->setAttr();

        return $this;
    }

    private function setAttr()
    {
        $this->id = session_id();

        $this->name = session_name();

        $status = session_status();

        if ($status == PHP_SESSION_DISABLED) {
            return $this->status = self::DISABLED;
        }

        if ($status == PHP_SESSION_NONE) {
            return $this->status = self::NONE;
        }

        if ($status == PHP_SESSION_ACTIVE) {
            return $this->status = self::ACTIVE;
        }
    }

    public function get($key)
    {
        return $this->session[$key];
    }

    public function set($key, $value)
    {
        $this->session[$key] = $value;
    }

    public function has($key)
    {
        return isset($this->session[$key]);
    }

    public function remove($key)
    {
        unset($this->session[$key]);
    }

    public function destroy()
    {
        $result = session_destroy();

        if ($result) {
            $this->status = self::NONE;
        }

        return $result;
    }

    public function all()
    {
        return $this->session;
    }

    public function regenerate()
    {
        $result = session_regenerate_id();

        if ($result) {
            $this->setAttr();
        }
    }

    public function status()
    {
        return $this->status;
    }

    public function id()
    {
        return $this->id;
    }

    public function name()
    {
        return $this->name;
    }

    public function save()
    {
        session_write_close();
    }
}
