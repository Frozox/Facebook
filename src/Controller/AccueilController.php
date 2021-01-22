<?php

namespace App\Controller;

use App\Entity\Commentaires;
use App\Form\CommentairesType;
use App\Repository\CommentairesRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccueilController extends AbstractController
{
    /**
     * @Route("/", name="accueil")
     */
    public function index(Request $request, UserRepository $userRepository): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $user = $this->getUser();

        $form = $this->createForm(CommentairesType::class);
        $form->handleRequest($request);

        $entityManager = $this->getDoctrine()->getManager();

        if($request->get('user')){
            $id = $request->get('user');

            if($user->getId() != $id && $user->estEnRelationAvec('STATUS_FRIEND', $id)){
                $ami = $userRepository->getUserAsId($id);

                if ($form->isSubmitted() && $form->isValid()) {
                    $imageFile = $form->get('image')->getData();

                    $commentaire = new Commentaires($ami, $user, $form->get('title')->getData(), $form->get('content')->getData());

                    if($imageFile){
                        $newFilename = $ami->getId() . '_' . $user->getId() . '_' . $commentaire->getDateCrea()->format('d_m_Y_H_i_s') . '.' . $imageFile->guessExtension();
                        $fullFilePath = '/images/comment/' . $newFilename;
                        try {
                            $imageFile->move(
                                $this->getParameter('comment_directory'),
                                $newFilename
                            );
                            $commentaire->setImage($fullFilePath);
                        } catch (FileException $e) {
                            throw $e;
                        }
                    }

                    $entityManager->persist($commentaire);
                    $entityManager->flush();
                }

                if($request->get('remove')){
                    $id = $request->get('remove');
                    $commentaire =

                    $ami->removeCommentaire($id);

                    $entityManager->persist($ami);
                    $entityManager->flush();
                }

                return $this->render('accueil/index.html.twig', [
                    'user' => $ami,
                    'commentairesForm' => $this->createForm(CommentairesType::class)->createView(),
                ]);
            }
            else{
                return $this->redirectToRoute('accueil');
            }
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('image')->getData();

            $commentaire = new Commentaires($user, $user, $form->get('title')->getData(), $form->get('content')->getData());

            if($imageFile){
                $newFilename = $user->getId() . '_' . $user->getId() . '_' . $commentaire->getDateCrea()->format('d_m_Y_H_i_s') . '.' . $imageFile->guessExtension();
                $fullFilePath = '/images/comment/' . $newFilename;
                try {
                    $imageFile->move(
                        $this->getParameter('comment_directory'),
                        $newFilename
                    );
                    $commentaire->setImage($fullFilePath);
                } catch (FileException $e) {
                    throw $e;
                }
            }

            $entityManager->persist($commentaire);
            $entityManager->flush();
        }

        if($request->get('remove')){
            $id = $request->get('remove');
            $commentaire = $user->getCommentaireAsId($id);

            if($commentaire){
                $user->removeCommentaire($id);

                $entityManager->persist($user);
                $entityManager->flush();
            }
        }

        return $this->render('accueil/index.html.twig', [
            'user' => $this->getUser(),
            'commentairesForm' => $this->createForm(CommentairesType::class)->createView(),
        ]);
    }
}
