<?php

namespace App\Controller;

use App\Entity\Projet;
use App\Entity\User;
use App\Form\ProjetType;
use App\Repository\ProjetRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/user/projet')]
class ProjetController extends AbstractController
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }
    // #[Route('/', name: 'app_projet', methods: ['GET'])]
    // public function index(ProjetRepository $projetRepository): Response
    // {
    //     return $this->render('projet/index.html.twig', [
    //         'projets' => $projetRepository->findAll(),
    //     ]);
    // }

    
    

    #[Route('/', name: 'app_projet', methods: ['GET'])]
    public function index(ProjetRepository $projetRepository): Response
    {
        $user = $this->getUser();
        
        if ($this->security->isGranted('ROLE_ENS')) {
            // Si l'utilisateur est un enseignant, afficher ses propres projets
            $projets = $projetRepository->findBy(['enseignant' => $user]);
        } else {
            // Sinon, afficher tous les projets disponibles
            $projets = $projetRepository->findAll();
        }

        return $this->render('projet/index.html.twig', [
            'projets' => $projets,
        ]);
    }

 
    #[Route('/new', name: 'app_projet_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $projet = new Projet();
        $form = $this->createForm(ProjetType::class, $projet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($projet);
            $entityManager->flush();
            $this->addFlash('success', 'Project created successfully!');

            return $this->redirectToRoute('app_projet', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('projet/new.html.twig', [
            'projet' => $projet,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_projet_show', methods: ['GET'])]
    public function show(Projet $projet): Response
    {
        return $this->render('projet/show.html.twig', [
            'projet' => $projet,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_projet_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Projet $projet, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProjetType::class, $projet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Project edited successfully!');

            return $this->redirectToRoute('app_projet', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('projet/edit.html.twig', [
            'projet' => $projet,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_projet_delete', methods: ['POST'])]
    public function delete(Request $request, Projet $projet, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$projet->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($projet);
            $entityManager->flush();
            $this->addFlash('success', 'Project deleted successfully!');
        }

        return $this->redirectToRoute('app_projet', [], Response::HTTP_SEE_OTHER);
    }
}
