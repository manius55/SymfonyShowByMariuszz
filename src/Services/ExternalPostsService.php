<?php

namespace App\Services;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class ExternalPostsService
{
    public function __construct(private readonly HttpClientInterface $httpClient) {}

    /**
     * @return array
     */
    public function fetchUsers(): array
    {
        $response = $this->httpClient->request('GET', 'https://jsonplaceholder.typicode.com/users');
        return $response->toArray();
    }

    /**
     * @return array
     */
    public function fetchPosts(): array
    {
        $response = $this->httpClient->request('GET', 'https://jsonplaceholder.typicode.com/posts');
        return $response->toArray();
    }
}