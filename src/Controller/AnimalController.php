<?php

namespace App\Controller;

use App\Entity\Animal;
use App\Form\AnimalType;
use App\Repository\AnimalRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
    public function create(Request $request,EntityManagerInterface $em): Response {

        $animal = new Animal();
        
        $formAnimal = $this->createForm(AnimalType::class, $animal);

        if ($request->isMethod('POST')) {
            $formAnimal->handleRequest($request);
            if ($formAnimal->isSubmitted() && $formAnimal->isValid()) {
                $animalData = $formAnimal->getData();

                $em->persist($animalData);
                $em->flush();

                return $this->redirectToRoute('app_animal');
            }
        }

        return $this->render('/animal/create.html.twig', [
            'formAnimal' => $formAnimal,
        ]);
    }
}
