<?php

namespace Ruvents\AdminBundle\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as SymfonyAbstractController;

abstract class AbstractController extends SymfonyAbstractController
{
    protected function getEntityManager(string $class): EntityManagerInterface
    {
        $manager = $this->getDoctrine()->getManagerForClass($class);

        if (!$manager instanceof EntityManagerInterface) {
            throw new \InvalidArgumentException(sprintf('%s is not an entity.', $class));
        }

        return $manager;
    }
}
