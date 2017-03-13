<?php

namespace Contact\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Contact\Entity\ContactUs;
use Zend\Form\Annotation\AnnotationBuilder;
use Zend\Mail\Message;

class IndexController extends AbstractActionController
{
    private $mailTransport;

    public function __construct($mailTransport)
    {
        $this->mailTransport = $mailTransport;
    }

    public function indexAction()
    {
        $contactUs = new ContactUs();
        $annotationBuilder = new AnnotationBuilder();
        $form = $annotationBuilder->createForm($contactUs);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $name  = $form->get('name')->getValue();
                $email = $form->get('email')->getValue();
                $body  = $form->get('message')->getValue();

                $message = new Message();
                $message->setEncoding('utf-8');

                $emailQuote = "'" . $email . "'";

                $message->setBody($body)
                        ->setFrom($email, $name . ' from ' . $emailQuote)
                        ->addTo('testxamppphp@gmail.com', 'Admin')
                        ->setSubject('New message');

                $this->mailTransport->send($message);

                $this->flashMessenger()->addSuccessMessage('Message successfully sent');

                $this->redirect()->refresh();
            }
        }

        return new ViewModel([
            'form' => $form,
        ]);
    }
}
