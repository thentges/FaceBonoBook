<?php

namespace Appartoo\BonobosBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Appartoo\UserBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class FriendsController extends Controller
{
    /**
    * @Security("has_role('ROLE_USER')")
    */
    public function indexAction(){

      return $this->render('AppartooBonobosBundle:Friends:index.html.twig');
    }
    /**
    * @Security("has_role('ROLE_USER')")
    */
    public function addAction(Request $request){
      return $this->render('AppartooBonobosBundle:Friends:add.html.twig');
    }

    public function adddAction(Request $request, $id){
      $currentUser = $this->getUser();
      $userManager = $this->get('fos_user.user_manager');
      $friend = $userManager->findUserBy(array('id' => $id));
      if ($currentUser->getFriends()->contains($friend)){ // si les utilisateurs sont déjàs amis
        $request->getSession()->getFlashBag()->add('error', 'Cet utilisateur est déjà votre ami'); //message flash
        return $this->render('AppartooBonobosBundle:Friends:add.html.twig');
      } // si essaye de s'ajouter lui même
      elseif ($friend == $currentUser){
        $request->getSession()->getFlashBag()->add('error', 'Vous ne pouvez pas vous ajouter vous même'); //message flash
        return $this->render('AppartooBonobosBundle:Friends:add.html.twig');
      }
      else{
      $currentUser->addFriend($friend);
      $friend->addFriend($currentUser);
      $userManager->updateUser($currentUser);
      $userManager->updateUser($friend);
      $request->getSession()->getFlashBag()->add('success', 'Le contact a été ajouté'); //message flash
      return $this->redirectToRoute('appartoo_bonobos_friends'); //redirige vers la liste d'amis
    }

    }



    /**
    * @Security("has_role('ROLE_USER')")
    */
    public function delAction($id, Request $request){
      $currentUser = $this->getUser();
      $userManager = $this->get('fos_user.user_manager');
      $friend = $userManager->findUserBy(array('id' => $id));
      $friend->removeFriend($currentUser);
      $currentUser->removeFriend($friend);
      $userManager->updateUser($friend);
      $userManager->updateUser($currentUser);
      $request->getSession()->getFlashBag()->add('error', $friend->getUsername()." a été supprimé de votre liste d'amis"); //message flash
      return $this->redirectToRoute('appartoo_bonobos_friends'); //redirige vers la liste d'amis

    }


}
