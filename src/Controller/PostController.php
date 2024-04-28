<?php

namespace App\Controller;

use App\Entity\Post;
use App\Interfaces\Repository\PostRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;

class PostController extends AbstractController
{
    public function __construct(private readonly PostRepositoryInterface $postRepository)
    {}
    #[Route('/list', name: 'post_list', methods: ['GET'])]
    public function index(): Response
    {
        $postsList = $this->postRepository->getPostsList();

        return $this->render('post/list.html.twig', [
            'posts' => $postsList
        ]);
    }

    #[Route('/api/posts/{id}', name: '_api_/posts/{id}{._format}_delete', methods: ['DELETE'])]
    public function softDelete(Post $post): Response
    {
        $this->postRepository->softDelete($post);

        return $this->json(['status' => 'Post soft deleted'], 200);
    }

    #[Route('/api/posts', name: '_api_/posts/remove', methods: ['DELETE'])]
    public function softDeleteAllPosts(): Response
    {
        $this->postRepository->softDeleteAllPosts();

        return $this->json(['status' => 'All post soft deleted'], 200);
    }
}