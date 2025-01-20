<?php

namespace App\Controller;

use App\Entity\Entreprise;
use App\Form\EntrepriseType;
use App\Repository\EntrepriseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


final class EntrepriseController extends AbstractController
{
    #[Route('/entreprise', name: 'app_entreprise')]
    // public function index(EntityManagerInterface $entityManager): Response
    public function index(EntrepriseRepository $entrepriseRepository): Response
    {

        // $entreprises = $entityManager->getRepository(Entreprise::class)->findAll();
        // SELECT * FROM entreprise ORDER BY raisonSociale ASC (ASC apr defaut)
        $entreprises = $entrepriseRepository->findBy([], ["raisonSociale" => "ASC"]);

        return $this->render('entreprise/index.html.twig', [
            'entreprises' => $entreprises
        ]);
    }

    #[Route('/entreprise/new', name: 'new_entreprise')]
    public function new (Request $request, EntityManagerInterface $entityManager): Response
    {
        $entreprise = new Entreprise();

        $form = $this->createForm(EntrepriseType::class, $entreprise);

        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {

            $entreprise = $form->getData();

            //equivalent prepare / execute de PDO
            $entityManager->persist($entreprise);
            $entityManager->flush();

            return $this->redirectToRoute('app_entreprise');

        }

        return $this->render('entreprise/new.html.twig', [
            'formAddEntreprise' => $form
        ]);
    }

    #[Route('/entreprise/{id}', name: 'show_entreprise')]

    public function show(Entreprise $entreprise): Response
    {

        return $this->render('entreprise/show.html.twig', [
            'entreprise' => $entreprise
        ]);
    }

}
