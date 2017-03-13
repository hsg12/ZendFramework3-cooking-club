<?php

namespace Authentication\Service;

use Authentication\Service\ValidationServiceInterface;
use Doctrine\Common\Persistence\ObjectRepository;
use DoctrineModule\Validator\ObjectExists;

class ValidationService implements ValidationServiceInterface
{
    private $repository;

    public function __construct(ObjectRepository $repository)
    {
        $this->repository = $repository;
    }

    public function isObjectExists($value, $fields)
    {
        $object = new ObjectExists([
            'object_repository' => $this->repository,
            'fields' => $fields,
        ]);

        return $object->isValid($value);
    }
}
