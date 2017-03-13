<?php

namespace Authentication\Service;

interface ValidationServiceInterface
{
    public function isObjectExists($value, $fields);
}
