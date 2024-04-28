<?php

namespace App\Interfaces;

interface PostTransformerInterface
{
    /**
     * @param array $postData
     * @param array $userMap
     * @return void
     */
    public function transformPostsAndPersist(array $postData, array $userMap): void;

    /**
     * @param array $users
     * @return array
     */
    public function mapUsers(array $users): array;
}