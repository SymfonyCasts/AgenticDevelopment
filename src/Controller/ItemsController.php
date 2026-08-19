<?php

namespace App\Controller;

use App\Entity\Item;
use App\Repository\ItemRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ItemsController extends AbstractController
{
    #[Route('/', name: 'app_items')]
    public function index(ItemRepository $itemRepository): Response
    {
        return $this->render('items/index.html.twig', [
            'items' => $itemRepository->findBy([], ['id' => 'ASC']),
        ]);
    }

    #[Route('/items/{id<\d+>}', name: 'app_items_show')]
    public function show(Item $item): Response
    {
        return $this->render('items/show.html.twig', [
            'item' => $item,
        ]);
    }
}
