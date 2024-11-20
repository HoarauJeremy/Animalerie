<?php

namespace App\Controller;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProductController extends AbstractController
{
    #[Route('/product', name: 'app_product')]
    public function index(): Response
    {
        return $this->render('product/index.html.twig', [
            'controller_name' => 'ProductController',
        ]);
    }

    #[Route('/createProduct', name:'app_create_produit')]
    public function create(EntityManagerInterface $em): Response {
        $croquette = new Product();
        $croquette->setName('Croquette');
        $croquette->setPrice(999.99);
        $croquette->setCreatedAt(new \DateTimeImmutable());

        $em->persist($croquette);
        $em->flush();

        return new Response('Enregistrer: '.$croquette->getId() ." ". $croquette->getName() , Response::HTTP_CREATED);

    }
}
