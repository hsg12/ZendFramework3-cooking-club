<?php

namespace Authentication\Service;

use Authentication\Service\ValidationServiceInterface;
use Doctrine\Common\Persistence\ObjectRepository;
use DoctrineModule\Validator\ObjectExists;

class ValidationService implements ValidationServiceInterface
{
    public function isObjectExists(ObjectRepository $repository, $value, $fields)
    {
        $object = new ObjectExists([
            'object_repository' => $repository,
            'fields' => $fields,
        ]);

        return $object->isValid($value);
    }
}
