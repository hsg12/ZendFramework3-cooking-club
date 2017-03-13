<?php

namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;

class GetYear extends AbstractHelper
{
    public function __invoke()
    {
        $year = date('Y');
        return ($year > 2017) ? "2017 - {$year}" : $year;
    }
}
