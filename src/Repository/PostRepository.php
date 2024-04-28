<?php

namespace App\Repository;

use App\Entity\Post;
use App\Interfaces\Repository\PostRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class PostRepository extends EntityRepository implements PostRepositoryInterface
{

    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
        $class = $this->entityManager->getClassMetadata(Post::class);
        parent::__construct($entityManager, $class);
    }

    public function getPostsList(): array
    {
        return $this->createQueryBuilder('p')
            ->select('p')
            ->where('p.deletedAt IS NULL')
            ->getQuery()
            ->getResult();
    }

    public function softDelete(Post $post): void
    {
        $post->setDeletedAt(new \DateTime());
        $this->entityManager->persist($post);
        $this->entityManager->flush();
    }

    public function softDeleteAllPosts(): void
    {
        $this->createQueryBuilder('p')
            ->update()
            ->set('p.deletedAt', ':now')
            ->where('p.deletedAt IS NULL')
            ->setParameter('now',  new \DateTime())
            ->getQuery()
            ->execute();
    }
}