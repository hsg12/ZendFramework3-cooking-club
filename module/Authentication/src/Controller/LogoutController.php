<?php

namespace Authentication\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Authentication\Model\AppAuthStorage;

class LogoutController extends AbstractActionController
{
    private $ormAuthService;
    private $authStorage;

    public function __construct(
        $ormAuthService,
        AppAuthStorage $authStorage
    ) {
        $this->ormAuthService = $ormAuthService;
        $this->authStorage = $authStorage;
    }

    public function indexAction()
    {
        $this->authStorage->forgetMe();
        $this->ormAuthService->clearIdentity();

        return $this->redirect()->toRoute('home');
    }
}
