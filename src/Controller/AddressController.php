<?php

namespace App\Controller;

use App\Repository\AddressRepository;
use Doctrine\ORM\EntityManagerInterface;
use Proxies\__CG__\App\Entity\Address;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AddressController extends AbstractController
{

    private $addressRepository;

    public function __construct(AddressRepository $addressRepository) {
        $this->addressRepository = $addressRepository;
    }

    #[Route('/address', name: 'app_address')]
    public function index(): Response
    {
        $addresses = $this->addressRepository->findAll();

        return $this->render('address/index.html.twig', [
            'addresses' => $addresses,
        ]);
    }

    #[Route('/address/create/{streetNumber}/{streetName}/{zipCode}', name:'app_address_create', methods: ['GET'])]
    public function create(EntityManagerInterface $em, int $streetNumber, string $streetName, string $zipCode): Response {
        $address = new Address();
        $address->setZipCode($zipCode);
        $address->setStreetName($streetName);
        $address->setStreetNumber($streetNumber);

        $em->persist($address);
        $em->flush();

        return $this->redirectToRoute('app_address');
    }
}
