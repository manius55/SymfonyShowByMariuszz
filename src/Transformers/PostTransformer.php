<?php

namespace App\Transformers;

use App\Entity\Post;
use App\Interfaces\Transformers\PostTransformerInterface;
use Doctrine\ORM\EntityManagerInterface;

class PostTransformer implements PostTransformerInterface
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    public function transformPostsAndPersist(array $postData, array $userMap): void
    {
        foreach ($postData as $data) {
            $post = new Post();
            $post->setTitle($data['title']);
            $post->setBody($data['body']);
            $post->setUserId($data['userId']);
            $post->setAuthorName($userMap[$data['userId']] ?? 'Unknown');

            $this->entityManager->persist($post);
        }
    }

    public function mapUsers(array $users): array
    {
        $userMap = [];
        foreach ($users as $user) {
            $userMap[$user['id']] = $user['name'];
        }

        return $userMap;
    }
}