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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RecipeController extends AbstractController
{
    #[IsGranted('ROLE_USER')]
    #[Route('/recipe', name: 'app_recipe', methods: ['GET'])]
    public function index(Request $request, RecetteRepository $repository,  PaginatorInterface $paginator): Response
    {
        $recettes = $paginator->paginate(
            $repository->findBy(['user' => $this->getUser() ]), /* query NOT result */
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
            $recette->setUser($this->getUser());

            $manager->persist($recette);
            $manager->flush();

            $this->addFlash('success', 'Recette entrgistré !');

            return $this->redirectToRoute('app_recipe');
        }

        return $this->render('pages/recipe/new.html.twig', [
            'form'=> $form->createView()
        ]);

    }
    #[Security("is_granted('ROLE_USER') and recette.getIsPublic() === true")]
    #[Route('/recipe/show_recipe/{id}', name: 'show_recipe', methods: ['GET'])]
    public function show(Recette $recette, Request $request): Response{

        return $this->render('pages/recipe/show.html.twig', [
            'recette'=> $recette
        ]);
    }

    #[Route('/recipe/public', name: 'public_recipe', methods: ['GET'])]
    public function indexPublic(Request $request, RecetteRepository $repository,  PaginatorInterface $paginator): Response{

        $recettes = $paginator->paginate(
            $repository->findPublicRecipe(null), /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );
        
        return $this->render('pages/recipe/index_public.html.twig', [
            'recettes'=> $recettes
        ]);
    }



    #[Security("is_granted('ROLE_USER') and user === recette.getUser()")]
    #[Route('/recipe/edit_recipe/{id}', name: 'edit_recipe', methods: ['GET', 'POST'])]
    public function editRecette(Request $request,  EntityManagerInterface $manager, Recette $recette): Response {
        //$ingredient = $repository->findOneBy(['id' => $id]);
        $form = $this->createForm(RecipeType::class, $recette);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $recette = $form->getData();
            $manager->persist($recette);
            $manager->flush();

            $this->addFlash('success', 'Recette modifié !');

            return $this->redirectToRoute('app_recipe');
            
        }else {
            # code...
        }

        return $this->render('pages/recipe/edit.html.twig', [
            'form'  => $form->createView()
        ]);
    }

    #[Security("is_granted('ROLE_USER') and user === recette.getUser()")]
    #[Route('/ingredient/delete_recipe/{id}', name: 'delete_recipe', methods: ['GET', 'POST'])]
    public function deleteIngredient(Request $request,  EntityManagerInterface $manager, Recette $recette): Response {
        
        if (!$recette) {
            $this->addFlash('success', 'Recette introuvable !');
        }
        $manager->remove($recette);
        $manager->flush();

        $this->addFlash('success', 'Recette supprimé !');
        return $this->redirectToRoute('app_recipe');
        
    }
}
