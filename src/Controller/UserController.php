<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Form\UserPasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserController extends AbstractController
{
    #[Route('/user/edit/{id}', name: 'app_user')]
    public function edit(User $user, Request $request, EntityManagerInterface $manager, UserPasswordHasherInterface $passwordHasher): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        if ($this->getUser() !== $user) {
            return $this->redirectToRoute('app_recipe');
        }

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

       // dd($user);

        if ($form->isSubmitted() && $form->isValid()) {
             /* if ($passwordHasher->isPasswordValid($user, $form->getData()->getPassword())) {
                $user = $form->getData();

                $manager->persist($user);
                $manager->flush();

                $this->addFlash('success', 'Utilisateur modifié !');

                return $this->redirectToRoute('app_recipe');
            }else {
                $this->addFlash('warning', 'Le mot de passe renseigné est incorrecte!');
            }  */
             $user = $form->getData();

                $manager->persist($user);
                $manager->flush();

                $this->addFlash('success', 'Utilisateur modifié !');

                return $this->redirectToRoute('app_recipe'); 
            
        }

        return $this->render('pages/user/edit.html.twig',  [
            'form' =>  $form->createView()
        ]);
    }


    #[Route('/user/edit-mot-de-pass/{id}', name: 'user_edit-mot-de-pass')]
    public function editPassword(User $user, Request $request, EntityManagerInterface $manager, UserPasswordHasherInterface $passwordHasher): Response
    {
        $form = $this->createForm(UserPasswordType::class);
        $form->handleRequest($request);

    

        if ($form->isSubmitted() && $form->isValid()) {
              if ($passwordHasher->isPasswordValid($user, $form->getData()['password'])) {
                $user->setPassword(
                    $form->getData()['newPassword']
                );

                $manager->persist($user);
                $manager->flush();

                $this->addFlash('success', 'Mot de passe modifié !');

                return $this->redirectToRoute('app_recipe');
            }else {
                $this->addFlash('warning', 'Le mot de passe renseigné est incorrecte!');
            }  
             
            
        }

        return $this->render('pages/user/edit_password.html.twig',  [
            'form' =>  $form->createView()
        ]);
    }


}
