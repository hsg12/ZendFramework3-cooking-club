<?php

namespace Admin\View\Helper;

use Zend\Form\View\Helper\AbstractHelper;

class CutString extends AbstractHelper
{
    public function __invoke($str, $cnt)
    {
        if (strlen($str) <= $cnt) {
            $output = $str;
        } else {
            $output = "
                <span title='" . $str . "' data-toggle='tooltip' data-placement='right'>"
                    . substr($str, 0, $cnt) . '....' .
                "</span>
            ";
        }

        return $output;
    }
}
