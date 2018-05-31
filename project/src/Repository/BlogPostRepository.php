<?php

namespace App\Repository;

use App\Entity\BlogPost;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class BlogPostRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, BlogPost::class);
    }

    /**
     * @param int $page
     * @param int $limit
     *
     * @return array
     */
    public function getAllPostsWithRange($page = 1, $limit = 5)
    {
        $entityManager = $this->getEntityManager();
        $queryBuilder = $entityManager->createQueryBuilder();
        $queryBuilder
            ->select('bp')
            ->from('App:BlogPost', 'bp')
            ->orderBy('bp.id', 'DESC')
            ->setFirstResult($limit * ($page - 1))
            ->setMaxResults($limit);

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * @return array
     */
    public function getPostCount()
    {
        $entityManager = $this->getEntityManager();
        $queryBuilder = $entityManager->createQueryBuilder();
        $queryBuilder
            ->select('count(bp)')
            ->from('App:BlogPost', 'bp');

        return $queryBuilder->getQuery()->getSingleScalarResult();
    }

    /**
     * @param int $id
     * @param string $typeResult
     *
     * @return array
     */
    public function getPostById($id, $typeResult="object")
    {
        $entityManager = $this->getEntityManager();
        $queryBuilder = $entityManager->createQueryBuilder();
        $queryBuilder
            ->select('bp')
            ->from('App:BlogPost', 'bp')
            ->andWhere('bp.id = :id')
            ->setParameter('id', $id);

        switch ($typeResult) {
            case 'object':
                return $queryBuilder->getQuery()->getResult();
                break;
            case 'array':
                return $queryBuilder->getQuery()->getArrayResult();
                break;
            case 'scalar':
                return $queryBuilder->getQuery()->getScalarResult();
                break;
            default:
                return $queryBuilder->getQuery()->getResult();
                break;
        }
    }

    /**
     * @param array $fields
     * @param string $typeResult
     *
     * @return array
     */
    public function getAllPosts($fields,$typeResult="object")
    {
        $entityManager = $this->getEntityManager();
        $queryBuilder = $entityManager->createQueryBuilder();
        $queryBuilder
            ->select($fields)
            ->from('App:BlogPost', 'bp');

        switch ($typeResult) {
            case 'object':
                return $queryBuilder->getQuery()->getResult();
                break;
            case 'array':
                return $queryBuilder->getQuery()->getArrayResult();
                break;
            case 'scalar':
                return $queryBuilder->getQuery()->getScalarResult();
                break;
            default:
                return $queryBuilder->getQuery()->getResult();
                break;
        }
    }

}
