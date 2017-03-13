<?php

namespace Application\Service;

use Doctrine\ORM\EntityManagerInterface;

interface FormServiceInterface
{
    public function getAnnotationForm(EntityManagerInterface $entityManager, $objForm);
}
