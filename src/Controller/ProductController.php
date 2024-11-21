<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\PetshopRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductController extends AbstractController
{

    private $petshopRepository;
    private $productRepository;

    public function __construct(PetshopRepository $petshopRepository, ProductRepository $productRepository) {
        $this->petshopRepository = $petshopRepository;
        $this->productRepository = $productRepository;
    }

    #[Route('/product', name: 'app_product')]
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        $product = new Product();

        /* $form = $this->createFormBuilder($product)
                ->setMethod('POST')
                ->add('name', null, ['required' => true])
                ->add('price', MoneyType::class, [
                    'required' => false,
                    'label' => "prix"
                ])
                ->add('save', SubmitType::class)
                ->getForm(); */

        $form = $this->createForm(ProductType::class, $product);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $productData = $form->getData();
                $productData->setActive(true);
                $productData->setCreatedAt(new \DateTimeImmutable());

                $em->persist($productData);
                $em->flush();

                return $this->redirectToRoute('app_product_show');
            }
        }

        return $this->render('product/index.html.twig', [
            'controller_name' => 'ProductController',
            'form'=> $form,
        ]);
    }

    #[Route('/product/show', name:'app_product_show')]
    public function show(): Response {
        $products = $this->productRepository->findAll();

        return $this->render('product/show.html.twig', [
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
