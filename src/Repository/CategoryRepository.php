<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    public function firstOrCreate(string $label): Category
    {
        $category = $this->findOneBy(['label' => $label]);
        if ($category) return $category;

        $category = new Category();
        $category->setLabel($label);

        $entityManager = $this->getEntityManager();
        $entityManager->persist($category);
        $entityManager->flush();

        return $category;
    }
}