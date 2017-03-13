<?php

namespace Application\View\Helper;

use Zend\Form\View\Helper\AbstractHelper;

class CutStr extends AbstractHelper
{
    public function __invoke($str, $cnt = 500)
    {
        if (strlen($str) <= $cnt) {
            $output = nl2br($str);
        } else {
            $output = nl2br(substr($str, 0, $cnt) . '....');
        }

        return $output;
    }
}
