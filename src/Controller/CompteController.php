<?php

namespace App\Controller;

use App\Form\ProfileType;
use Symfony\Component\Form\FormError;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class CompteController extends AbstractController
{
    /**
     * @Route("/profile", name="compte")
     */
    public function index(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $user = $this->getUser();
        $form = $this->createForm(ProfileType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $resetAvatar = $form->get('resetAvatar')->isClicked();

            if($resetAvatar){
                $user->removeAvatar();

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();

                $this->addFlash('success', "L'avatar a été réinitialisé !");
            }
            else{
                $avatarFile = $form->get('avatar')->getData();

                if($avatarFile){
                    $newFilename = $user->getId() . '.' . $avatarFile->guessExtension();
                    $fullFilePath = '/images/avatar/' . $newFilename;
                    try {
                        $avatarFile->move(
                            $this->getParameter('avatar_directory'),
                            $newFilename
                        );
                    } catch (FileException $e) {
                        throw $e;
                        // ... handle exception if something happens during file upload
                    }

                    $user->setAvatar($fullFilePath);

                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->persist($user);
                    $entityManager->flush();

                    $this->addFlash('success', 'La photo de profile a été changée avec succes !');
                }

                if(!$form->get('oldPassword')->isEmpty())
                {
                    if($passwordEncoder->isPasswordValid($user, $form->get('oldPassword')->getData())){
                        if($form->get('newPassword')->isEmpty()){
                            $form->get('newPassword')->addError(new FormError('Le champ ne peut pas être vide'));
                        }
                        elseif ($passwordEncoder->isPasswordValid($user, $form->get('newPassword')->getData())){
                            $form->get('newPassword')->addError(new FormError('Le nouveau mot de passe doit être différent du précédent'));
                        }
                        else{
                            $user->setPassword(
                                $passwordEncoder->encodePassword(
                                    $user,
                                    $form->get('newPassword')->getData()
                                )
                            );

                            $entityManager = $this->getDoctrine()->getManager();
                            $entityManager->persist($user);
                            $entityManager->flush();

                            $this->addFlash('success', 'Le mot de passe a été changé avec succes !');
                        }
                    }
                    else{
                        $form->get('oldPassword')->addError(new FormError("Il ne s'agit pas de votre mot de passe actuel"));
                    }
                }
            }
        }

        return $this->render('compte/index.html.twig', [
            'profileForm' => $form->createView(),
        ]);
    }
}
