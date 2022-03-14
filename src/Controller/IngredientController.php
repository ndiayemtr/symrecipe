<?php

namespace App\Controller;

use App\Entity\Ingredient;
use App\Form\IngredientType;
use App\Repository\IngredientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class IngredientController extends AbstractController
{
    #[Route('/ingredient', name: 'app_ingredient', methods: ['GET'])]
    public function index(Request $request, IngredientRepository $repository,  PaginatorInterface $paginator): Response
    {
        $ingredients = $paginator->paginate(
            $repository->findAll(), /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );

        return $this->render('pages/ingredient/index.html.twig', [
            'ingredients' => $ingredients
        ]);
    }

    #[Route('/new_ingredient', name: 'new_ingredient', methods: ['GET', 'POST'])]
    public function newIngredient(Request $request, EntityManagerInterface $manager): Response {
        $ingredient = new Ingredient();
        $form = $this->createForm(IngredientType::class, $ingredient);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ingredient = $form->getData();
            $manager->persist($ingredient);
            $manager->flush();

            return $this->redirectToRoute('app_ingredient');
            
        }else {
            # code...
        }

        return $this->render('pages/ingredient/new.html.twig', [
            'form'  => $form->createView()
        ]);
    }
} 
