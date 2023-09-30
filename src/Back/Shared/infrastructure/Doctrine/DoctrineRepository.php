<?php

declare(strict_types=1);


namespace App\Back\Shared\infrastructure\Doctrine;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\ORMException;

abstract class DoctrineRepository
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    protected function entityManager(): EntityManagerInterface
    {
        return $this->entityManager;
    }

    protected function persist($entity): void
    {
        try {
            $this->entityManager()->persist($entity);
            $this->entityManager()->flush($entity);
        } catch (ORMException $e) {
            dump($e->getMessage());
            die();
        }
    }

    protected function remove($entity): void
    {
        $this->entityManager()->remove($entity);
        $this->entityManager()->flush($entity);
    }

    protected function repository($entityClass): EntityRepository
    {
        return $this->entityManager->getRepository($entityClass);
    }
}
