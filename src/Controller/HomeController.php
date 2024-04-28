<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(AuthenticationUtils $authenticationUtils): RedirectResponse
    {
        $user = $this->getUser();
        if ($user) {
            return $this->redirectToRoute('post_list');
        } else {
            return $this->redirectToRoute('app_login');
        }
    }
}
