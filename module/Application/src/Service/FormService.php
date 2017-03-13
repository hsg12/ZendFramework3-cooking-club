<?php

namespace Application\Service;

use Application\Service\FormServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use DoctrineORMModule\Form\Annotation\AnnotationBuilder;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject;

class FormService implements FormServiceInterface
{
    public function getAnnotationForm(EntityManagerInterface $entityManager, $objForm)
    {
        $builder = new AnnotationBuilder($entityManager);
        $form = $builder->createForm($objForm);
        $form->setHydrator(new DoctrineObject($entityManager));
        $form->bind($objForm);

        return ($form) ? $form : false;
    }
}
