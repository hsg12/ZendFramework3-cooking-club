<?php

namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;

class GetFlashMessenger extends AbstractHelper
{
    public function __invoke($flashMessenger)
    {
         $flash = $flashMessenger;
         $flash->setMessageOpenFormat('<div%s>
         <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
         <ul><li>')
            ->setMessageSeparatorString('</li><li>')
            ->setMessageCloseString('</li></ul></div>');

         echo $flash->render('error',   array('alert', 'alert-dismissible', 'alert-danger',  'fade in'));
         echo $flash->render('info',    array('alert', 'alert-dismissible', 'alert-info',    'fade in'));
         echo $flash->render('default', array('alert', 'alert-dismissible', 'alert-warning', 'fade in'));
         echo $flash->render('success', array('alert', 'alert-dismissible', 'alert-success', 'fade in'));
    }
}
