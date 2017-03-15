<?php

namespace Authentication\Service;

use Doctrine\Common\Persistence\ObjectRepository;

interface ValidationServiceInterface
{
    public function isObjectExists(ObjectRepository $repository, $value, $fields);
}
