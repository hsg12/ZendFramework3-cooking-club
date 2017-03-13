<?php

namespace Application\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;

class TextFilter extends AbstractPlugin
{
    public function __invoke($text)
    {
        $find = array('fuck', 'idiot', 'bitch', 'fool', 'dumb');
        $replace = array('f**k', 'id**t', 'bi**h', 'f**l', 'd**b');

        return str_ireplace($find, $replace, $text);
    }
}
