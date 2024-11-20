<?php

namespace App\Controller;

use App\Entity\Animal;
use App\Repository\AnimalRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AnimalController extends AbstractController
{

    private $animalRepository;

    public function __construct(AnimalRepository$animalRepository) {
        $this->animalRepository = $animalRepository;
    }

    #[Route('/animal', name: 'app_animal')]
    public function index(): Response
    {
        $animals = $this->animalRepository->findAll();

        return $this->render('animal/index.html.twig', [
            'animals' => $animals,
        ]);
    }

    #[Route('/animal/create', name:'app_create_animal')]
    public function create(EntityManagerInterface $em): Response {

        $animal = new Animal();
        $animal->setName('CHAT');
        $animal->setBirthDate(new \DateTime('2022-09-09'));
        $animal->setHeight(100);
        $animal->setWeight(100);
        $animal->setSex("MALE");
        $animal->setBreed("-");

        $em->persist($animal);
        $em->flush();

        return $this->redirectToRoute('app_animal');
    }
}
