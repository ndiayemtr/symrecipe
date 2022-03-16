<?php

namespace App\Controller;

use App\Entity\Recette;
use App\Form\RecipeType;
use App\Repository\RecetteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RecipeController extends AbstractController
{
    #[Route('/recipe', name: 'app_recipe', methods: ['GET'])]
    public function index(Request $request, RecetteRepository $repository,  PaginatorInterface $paginator): Response
    {
        $recettes = $paginator->paginate(
            $repository->findAll(), /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );

        return $this->render('pages/recipe/index.html.twig', [
            'recettes' => $recettes
        ]);
    }
    
    #[Route('recipe/new_recipe', name: 'new_recipe', methods: ['GET', 'POST'])]
    public function newRecette(Request $request, EntityManagerInterface $manager):Response {
       $recette = new Recette();
        $form = $this->createForm(RecipeType::class, $recette);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $recette = $form->getData();

            $manager->persist($recette);
            $manager->flush();

            $this->addFlash('success', 'Recette entrgistré !');

            return $this->redirectToRoute('app_recipe');
        }

        return $this->render('pages/recipe/new.html.twig', [
            'form'=> $form->createView()
        ]);

    }
}
