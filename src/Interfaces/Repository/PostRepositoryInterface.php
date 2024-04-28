<?php

namespace App\Interfaces\Repository;

use App\Entity\Post;

interface PostRepositoryInterface
{
    /**
     * @return array
     */
    public function getPostsList(): array;

    /**
     * @param Post $post
     * @return void
     */
    public function softDelete(Post $post): void;

    /**
     * @return void
     */
    public function softDeleteAllPosts(): void;
}