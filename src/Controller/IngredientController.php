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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class IngredientController extends AbstractController
{
    #[Route('/ingredient', name: 'app_ingredient', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function index(Request $request, IngredientRepository $repository,  PaginatorInterface $paginator): Response
    {
        $ingredients = $paginator->paginate(
            $repository->findBy(['user' => $this->getUser() ]), /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );

        return $this->render('pages/ingredient/index.html.twig', [
            'ingredients' => $ingredients
        ]);
    }

    #[Route('/ingredient/new_ingredient', name: 'new_ingredient', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function newIngredient(Request $request, EntityManagerInterface $manager): Response {
        $ingredient = new Ingredient();
        $form = $this->createForm(IngredientType::class, $ingredient);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ingredient = $form->getData();
            $ingredient->setUser($this->getUser());
            
            $manager->persist($ingredient);
            $manager->flush();

            $this->addFlash('success', 'Ingredient entrgistré !');

            return $this->redirectToRoute('app_ingredient');
            
        }else {
            # code...
        }

        return $this->render('pages/ingredient/new.html.twig', [
            'form'  => $form->createView()
        ]);
    }

    #[Security("is_granted('ROLE_USER') and user === ingredient.getUser()")]
    #[Route('/ingredient/edi_ingredient/{id}', name: 'edit_ingredient', methods: ['GET', 'POST'])]
    public function editIngredient(Request $request,  EntityManagerInterface $manager, Ingredient $ingredient): Response {
        //$ingredient = $repository->findOneBy(['id' => $id]);
        $form = $this->createForm(IngredientType::class, $ingredient);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ingredient = $form->getData();
            $manager->persist($ingredient);
            $manager->flush();

            $this->addFlash('success', 'Ingredient modifié !');

            return $this->redirectToRoute('app_ingredient');
            
        }else {
            # code...
        }

        return $this->render('pages/ingredient/new.html.twig', [
            'form'  => $form->createView()
        ]);
    }

    #[Security("is_granted('ROLE_USER') and user === ingredient.getUser()")]
    #[Route('/ingredient/delete_ingredient/{id}', name: 'delete_ingredient', methods: ['GET', 'POST'])]
    public function deleteIngredient(Request $request,  EntityManagerInterface $manager, Ingredient $ingredient): Response {
        
        if (!$ingredient) {
            $this->addFlash('success', 'Ingredient introuvable !');
        }
        $manager->remove($ingredient);
        $manager->flush();

        $this->addFlash('success', 'Ingredient supprimé !');
        return $this->redirectToRoute('app_ingredient');
        
    }
} 
