<?php

namespace App\Controller;

use App\Entity\Address;
use App\Entity\Animal;
use App\Entity\Petshop;
use App\Form\PetshopType;
use App\Repository\AddressRepository;
use App\Repository\PetshopRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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

    #[Route('/petshop/create', name:'app_create_petshop')]
    public function create(EntityManagerInterface $em, Request $request): Response {
        $petshop = new Petshop();
        
        $petshopForm = $this->createForm(PetshopType::class, $petshop);

        if ($request->isMethod('POST')) {
            $petshopForm->handleRequest($request);

            if ($petshopForm->isSubmitted() && $petshopForm->isValid()) {
                $petshopData = $petshopForm->getData();

                $em->persist($petshopData);
                $em->flush();
                                
                return $this->redirectToRoute('app_petshop');
            }
        }

        return $this->render('petshop/create.html.twig', [
            'petshopForm' => $petshopForm,
        ]);
        
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

    #[Route('/petshop/{petshopId}/animals', name: 'app_petshop_animals')]
    public function showAnimal(EntityManagerInterface $em, int $petshopId): Response
    {
        $petshop = $this->petshopRepository->find($petshopId);

        return $this->render('petshop/showAnimal.html.twig', [
            'petshop' => $petshop,
            // 'animals' => $animals
        ]);
    }
}
