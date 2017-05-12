<?php

namespace Authentication\Model;

use Zend\Authentication\Storage\Session;

class AppAuthStorage extends Session
{
    public function setRememberMe($rememberMe = 0, $time)
    {
        if ($rememberMe == 1) {
            $this->session->getManager()->rememberMe($time);
        }
    }

    public function forgetMe()
    {
        $this->session->getManager()->forgetMe();
    }
}
