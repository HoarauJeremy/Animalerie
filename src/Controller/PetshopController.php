<?php

namespace App\Controller;

use App\Entity\Address;
use App\Entity\Petshop;
use App\Repository\PetshopRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PetshopController extends AbstractController
{

    private $petshopRepository;

    public function __construct(PetshopRepository $petshopRepository) {
        $this->petshopRepository = $petshopRepository;
    }

    #[Route('/petshop', name: 'app_petshop')]
    public function index(EntityManagerInterface $em): Response
    {
        $petshop = $this->petshopRepository->findAll();

        return $this->render('petshop/index.html.twig', [
            'controller_name' => 'PetshopController',
            'petshops' => $petshop,
        ]);
    }

    #[Route('/createPetShop', name:'app_create_petshop')]
    public function create(EntityManagerInterface $em): Response {
        $petshop = new Petshop();
        $petshop->setName('Pohstep');

        $address = new Address();
        $address->setStreetNumber(20);
        $address->setStreetName("Rue de lapaix");
        $address->setZipCode("97400");

        $petshop->setAddress($address);

        $em->persist($petshop);
        $em->flush();

        return new Response("Animalerie :". $petshop->getName() ." à été créer.");
    }

    #[Route("/petshop/{id}", name:"app_petshop_showOne")]
    public function showOne(EntityManagerInterface $em, int $id): Response {
        $petshop = $this->petshopRepository->find($id);

        // dd($petshop);

        return $this->render("petshop/showOne.html.twig", [
            "petshop"=> $petshop,
        ]);
    }
}
