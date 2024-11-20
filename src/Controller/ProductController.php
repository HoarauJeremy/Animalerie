<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\PetshopRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProductController extends AbstractController
{

    private $petshopRepository;
    private $productRepository;

    public function __construct(PetshopRepository $petshopRepository, ProductRepository $productRepository) {
        $this->petshopRepository = $petshopRepository;
        $this->productRepository = $productRepository;
    }

    #[Route('/product', name: 'app_product')]
    public function index(): Response
    {

        $products = $this->productRepository->findAll();
        // $petShops = $this->petshopRepository->findAll();

        // dd($products);

        return $this->render('product/index.html.twig', [
            'controller_name' => 'ProductController',
            'products'=> $products,
        ]);
    }

    #[Route('/createProduct', name:'app_create_produit')]
    public function create(EntityManagerInterface $em): Response {
        $croquette = new Product();
        $croquette->setName('Croquette DELUXE++');
        $croquette->setPrice(1999.99);
        $croquette->setCreatedAt(new \DateTimeImmutable());
        $croquette->setActive(true);    

        $animalerie = $this->petshopRepository->find(1);
        // $animalerieId = $animalerie->getId();

        $croquette->addPetShops($animalerie);

        $em->persist($croquette);
        $em->flush();

        return new Response('Enregistrer: '.$croquette->getId() ." ". $croquette->getName() , Response::HTTP_CREATED);
        // return new Response('ID: '. $animalerieId);

    }
}
