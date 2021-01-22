<?php

namespace App\Controller;

use App\Entity\Amis;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use phpDocumentor\Reflection\Type;
use phpDocumentor\Reflection\Types\Collection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Json;
use function Sodium\add;

class AmisController extends AbstractController
{
    /**
     * @Route("/amis", name="amis")
     */
    public function index(Request $request, UserRepository $userRepository): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $user = $this->getUser();

        if($request->get('user')){
            $allusers = $userRepository->findUsersByName($user->getId(), $request->get('user'));
            $users = new ArrayCollection();

            foreach ($allusers as $u){
                if($users->count() < 5 && $user->AucuneRelationAvec($u->getId())){
                    $users->add($u);
                }
                elseif ($users->count() >= 5){
                    break;
                }
            }

            if ($request->isXmlHttpRequest() || $request->query->get('showJson') == 1) {
                $jsonData = array();
                foreach($users as $user) {
                    $temp = array(
                        'id' => $user->GetId(),
                        'username' => $user->getUsername(),
                        'avatar' => $user->getAvatar(),
                    );
                    $jsonData[] = $temp;
                }
                return new JsonResponse($jsonData);
            }
        }

        if($request->get('invite')){
            $id = $request->get('invite');

            if($userRepository->ifUserExist($id)){
                if($id != $user->getId() && $user->AucuneRelationAvec($id)){
                    $userToInvite = $userRepository->getUserAsId($id);

                    $ami = new Amis($user, $userToInvite);

                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->persist($ami);
                    $entityManager->flush();
                }
            }

            return $this->redirectToRoute('amis');
        }

        if($request->get('profile')){
            $id = $request->get('profile');

            if($id != $user->getId() && $user->estEnRelationAvec($this->getParameter('friend_status'), $id)){
                return $this->redirectToRoute(
                    'accueil',
                    ['user' => $id]
                );
            }

            return $this->redirectToRoute('amis');
        }

        elseif($request->get('blocked')){
            $id = $request->get('blocked');

            if($id != $user->getId() && !$user->estEnRelationAvec($this->getParameter('blocked_status'), $id)){
                if($user->getAmiById($id)){
                    $user->getAmiById($id)->setStatus($this->getParameter('blocked_status'));

                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->persist($user);
                    $entityManager->flush();
                }
                else{
                    $userToBlock = $userRepository->getUserAsId($id);

                    $ami = new Amis($user, $userToBlock, true);

                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->persist($ami);
                    $entityManager->flush();
                }
            }

            return $this->redirectToRoute('amis');
        }

        elseif ($request->get('remove')){
            $id = $request->get('remove');

            if($id != $user->getId() && $user->estEnRelationAvec($this->getParameter('friend_status'), $id)){
                $user->removeAmi($id);

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();

                return $this->redirectToRoute('amis');
            }
        }

        elseif ($request->get('accept')){
            $id = $request->get('accept');

            if($id != $user->getId() && $user->estEnRelationAvec($this->getParameter('pending_status'), $id) && !$user->aDemanderEnAmi($id)){
                $user->getAmiById($id)->setStatus($this->getParameter('friend_status'));

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();

                return $this->redirectToRoute('amis');
            }
        }

        elseif($request->get('refuse')){
            $id = $request->get('refuse');

            if($id != $user->getId() && $user->estEnRelationAvec($this->getParameter('pending_status'), $id) && !$user->aDemanderEnAmi($id)){
                $user->removeAmi($id);

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();

                return $this->redirectToRoute('amis');
            }
        }

        elseif($request->get('cancel')){
            $id = $request->get('cancel');

            if($id != $user->getId() && $user->estEnRelationAvec($this->getParameter('pending_status'), $id) && $user->aDemanderEnAmi($id)){
                $user->removeAmi($id);

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();

                return $this->redirectToRoute('amis');
            }
        }

        return $this->render('amis/index.html.twig', [
            'controller_name' => 'AmisController',
        ]);
    }
}
