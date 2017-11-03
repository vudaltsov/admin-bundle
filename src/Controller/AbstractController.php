<?php

namespace Ruvents\AdminBundle\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as SymfonyAbstractController;

abstract class AbstractController extends SymfonyAbstractController
{
    /**
     * @param object $entity
     *
     * @return mixed
     */
    protected function getEntityId($entity)
    {
        $class = get_class($entity);

        $metadata = $this->getDoctrine()
            ->getManagerForClass($class)
            ->getClassMetadata($class);

        $field = $metadata->getIdentifierFieldNames()[0];

        $reflection = $metadata->getReflectionClass()->getProperty($field);
        $reflection->setAccessible(true);

        return $reflection->getValue($entity);
    }

    protected function getEntityManager(string $class): EntityManagerInterface
    {
        $manager = $this->getDoctrine()->getManagerForClass($class);

        if (!$manager instanceof EntityManagerInterface) {
            throw new \InvalidArgumentException(sprintf('%s is not an entity.', $class));
        }

        return $manager;
    }
}
