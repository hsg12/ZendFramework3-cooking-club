<?php

namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;

class GetRouteParams extends AbstractHelper
{
    private $application;

    public function __construct($application)
    {
        $this->application = $application;
    }

    public function __invoke()
    {
        $params = $this->application->getMvcEvent()->getRouteMatch()->getParams();
        return $params ? $params : false;
    }
}
