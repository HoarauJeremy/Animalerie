<?php

namespace App\Controller;

use App\Entity\Address;
use App\Entity\Petshop;
use App\Repository\AddressRepository;
use App\Repository\PetshopRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PetshopController extends AbstractController
{

    private $petshopRepository;
    private $addressRepository;

    public function __construct(PetshopRepository $petshopRepository, AddressRepository $addressRepository) {
        $this->petshopRepository = $petshopRepository;
        $this->addressRepository = $addressRepository;
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

    #[Route('/petshop/createPetshop/{name}', name:'app_create_petshop')]
    public function create(EntityManagerInterface $em, string $name): Response {
        $petshop = new Petshop();
        $petshop->setName($name);

        $em->persist($petshop);
        $em->flush();

        return $this->redirectToRoute('app_petshop');
    }

    #[Route('/petshop/createPetAddress/{petshopId}/{addressId}', name:'app_create_petshopAddress')]
    public function createWithAddress(EntityManagerInterface $em, int $petshopId, int $addressId): Response {
        $petshop = $this->petshopRepository->find($petshopId);
        $address = $this->addressRepository->find($addressId);
        
        $petshop->setAddress($address);
        $address->setPetShop($petshop);

        $em->persist($petshop);
        $em->flush();

        return $this->redirectToRoute('app_petshop');
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
