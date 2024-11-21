<?php

namespace App\Controller;

use App\Form\AddressType;
use App\Repository\AddressRepository;
use Doctrine\ORM\EntityManagerInterface;
use Proxies\__CG__\App\Entity\Address;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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

    #[Route('/address/create', name:'app_address_create')]
    public function create(Request $request, EntityManagerInterface $em): Response {
        $address = new Address();

        $formAddress = $this->createForm(AddressType::class, $address);

        if ($request->isMethod('POST')) {
            $formAddress->handleRequest($request);

            if ($formAddress->isSubmitted() && $formAddress->isValid()) {
                $addressData = $formAddress->getData();

                $em->persist($addressData);
                $em->flush();

                return $this->redirectToRoute('app_address');
            }
        }

        return $this->render('address/create.html.twig', [
            'formAddress' => $formAddress,
        ]);
    }

    /* #[Route('/address/create/{streetNumber}/{streetName}/{zipCode}', name:'app_address_create', methods: ['GET'])]
    public function create(EntityManagerInterface $em, int $streetNumber, string $streetName, string $zipCode): Response {
        $address = new Address();
        $address->setZipCode($zipCode);
        $address->setStreetName($streetName);
        $address->setStreetNumber($streetNumber);

        $em->persist($address);
        $em->flush();

        return $this->redirectToRoute('app_address');
    } */
}
