<?php

namespace Application\Library;

use Zend\Navigation\Service\DefaultNavigationFactory;

class TopNavigationFactory extends DefaultNavigationFactory
{
    protected function getName()
    {
        return 'top_navigation';
    }
}
