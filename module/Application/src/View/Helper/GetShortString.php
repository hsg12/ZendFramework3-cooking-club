<?php

namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;

class GetShortString extends AbstractHelper
{
    public function __invoke($str, $length = 10)
    {
        if (strlen($str) > $length) {
            return mb_substr($str, 0, $length) . '....';
        } else {
            return $str;
        }
    }
}
