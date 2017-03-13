<?php

namespace Admin\Form;

use Zend\Form\Form;
use Zend\Form\Element;
use Doctrine\ORM\EntityManagerInterface;
use DoctrineModule\Form\Element\ObjectSelect;

class CategoryForm extends Form
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct('category');

        $this->setAttributes([
            'class' => 'form-horizontal',
        ]);

        $this->entityManager = $entityManager;
        $this->createElements();
    }

    private function createElements()
    {
        $parentId = new ObjectSelect('parentId');
        $parentId->setLabel('Parent ID');
        $parentId->setLabelAttributes([
            'class' => 'control-label col-sm-3',
        ]);
        $parentId->setAttributes([
            'class'    => 'form-control',
            'id'       => 'parentId',
            'required' => 'required',
        ]);
        $parentId->setDisableInArrayValidator(true); // In order custom added 0 in the categories did not produce an error
        $parentId->setOptions([
            'empty_option'   => 'Select Parent ID',
            'object_manager' => $this->entityManager,
            'target_class'   => 'Application\Entity\Category',
            'property'       => 'id',

        ]);
        $this->add($parentId);

        $name = new Element\Text('name');
        $name->setLabel('Category name');
        $name->setLabelAttributes([
            'class' => 'control-label col-sm-3',
        ]);
        $name->setAttributes([
            'class'    => 'form-control',
            'id'       => 'name',
            'required' => 'required',
        ]);
        $name->setOptions([
            'min' => 2,
            'max' => 100,
        ]);
        $this->add($name);

        $submit = new Element\Submit('submit');
        $submit->setAttributes([
            'class' => 'btn btn-default',
            'value' => 'Submit',
        ]);
        $this->add($submit);
    }
}
