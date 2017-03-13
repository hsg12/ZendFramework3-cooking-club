<?php

namespace Admin\Form;

use Zend\Form\Form;
use Zend\Form\Element;
use Doctrine\ORM\EntityManagerInterface;
use DoctrineModule\Form\Element\ObjectSelect;

class ArticleForm extends Form
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct('article');
        $this->entityManager = $entityManager;

        $this->setAttributes([
            'class' => 'form-horizontal',
        ]);
        $this->createElements();
    }

    private function createElements()
    {
        $title = new Element\Text('title');
        $title->setLabel('Title');
        $title->setAttributes([
            'class'    => 'form-control',
            'id'       => 'title',
            'required' => 'required',
        ]);
        $title->setOptions([
            'min' => 2,
            'max' => 100,
        ]);
        $this->add($title);

        $description = new Element\Textarea('description');
        $description->setLabel('Description');
        $description->setAttributes([
            'class' => 'form-control',
            'id'    => 'description',
        ]);
        $this->add($description);

        $ingredient = new Element\Textarea('ingredient');
        $ingredient->setLabel('Ingredients');
        $ingredient->setAttributes([
            'class'    => 'form-control',
            'id'       => 'ingredient',
            'required' => 'required',
        ]);
        $ingredient->setOptions([
            'min' => 2,
        ]);
        $this->add($ingredient);

        $instruction = new Element\Textarea('instruction');
        $instruction->setLabel('Instructions');
        $instruction->setAttributes([
            'class'    => 'form-control',
            'id'       => 'instruction',
            'required' => 'required',
        ]);
        $instruction->setOptions([
            'min' => 2,
        ]);
        $this->add($instruction);

        $category = new ObjectSelect('category');
        $category->setLabel('Categories');
        $category->setAttributes([
            'class'    => 'form-control',
            'id'       => 'category',
            'required' => 'required',
        ]);
        $category->setOptions([
            'empty_option'   => 'Select category',
            'object_manager' => $this->entityManager,
            'target_class'   => 'Application\Entity\Category',
            'property'       => 'name',
        ]);
        $this->add($category);

        $isPublic = new Element\Checkbox('isPublic');
        $isPublic->setLabel('Is Public');
        $isPublic->setAttributes([
            'id'    => 'isPublic',
        ]);
        $isPublic->setOptions([
            'set_hidden_element' => true,
            'checked_value'   => 1,
            'unchecked_value' => 0,
        ]);
        $this->add($isPublic);

        $file = new Element\File('file');
        $file->setLabel('Upload image');
        $file->setAttributes([
            'class' => 'jfilestyle',
            'id'    => 'file',
        ]);
        $this->add($file);

        $submit = new Element\Submit('submit');
        $submit->setAttributes([
            'class' => 'btn btn-default',
            'value' => 'Submit',
        ]);
        $this->add($submit);
    }
}
