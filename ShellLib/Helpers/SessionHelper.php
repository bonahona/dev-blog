<?php
class SessionHelper extends DataHelper
{
    public function __construct()
    {
        parent::__construct();

        if(session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        foreach($_SESSION as $key => $value){
            $this->Add($key, $value);
        }
    }

    public function Destroy()
    {
        session_destroy();
    }

    public function offsetSet($offset, $value)
    {
        parent::offsetSet($offset, $value);
        $_SESSION[$offset] = $value;
    }
}