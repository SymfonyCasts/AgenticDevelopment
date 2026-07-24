<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ItemsController extends AbstractController
{
    #[Route('/', name: 'app_items')]
    public function index(): Response
    {
        return $this->render('items/index.html.twig');
    }

    #[Route('/items/{id}', name: 'app_items_show')]
    public function show(): Response
    {
        return $this->render('items/show.html.twig');
    }
}
