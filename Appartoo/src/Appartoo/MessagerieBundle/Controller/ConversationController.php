<?php

namespace Appartoo\MessagerieBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Appartoo\MessagerieBundle\Entity\Message;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Appartoo\UserBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class ConversationController extends Controller
{
  /**
  * @Security("has_role('ROLE_USER')")
  */
  public function listAction(){
    $user=$this->getUser();
    $repository = $this->getDoctrine()->getManager()->getRepository("AppartooMessagerieBundle:Message");
          $list = $repository->findBy(array('dest' => $user->getUsername()), array('id' => 'desc')); // trier du plus récent au plus ancien
          if (null === $list) { // si il n'y a aucun mail dans la bdd
          return $this->render('AppartooMessagerieBundle:Conversation:list.html.twig' , array('list' => null));
          }
          return $this->render('AppartooMessagerieBundle:Conversation:list.html.twig' , array('list' => $list));

  }
  /**
  * @Security("has_role('ROLE_USER')")
  */
  public function sendMsgAction(Request $request , $id){
    $msg = new Message();
    $userManager = $this->get('fos_user.user_manager');
    $friend = $userManager->findUserBy(array('id' => $id));
    $user=$this->getUser();
    if (!$user->getFriends()->contains($friend)){ // obligatoire d'être ami avec un utilisateur pour lui envoyer un message
      throw new NotFoundHttpException("Cet utilisateur n'est pas votre ami");
    }
    $formBuilder = $this->get('form.factory')->createBuilder(FormType::class , $msg);
    $formBuilder
      ->add('title', TextType::class, array('label' => "Titre"))
      ->add('message', TextareaType::class, array('label' => "Message"))
      ->add('add', SubmitType::class, array('label' => "Envoyer le message"));
    $form = $formBuilder->getForm();
    if ($request->isMethod('POST')) { // si formulaire envoyé :
      $form->handleRequest($request);
      if ($form->isValid()){
      $msg->setSender($user->getUsername());
      $msg->setDest($friend->getUsername());
      $em= $this->getDoctrine()->getManager(); //entity manager
      $em->persist($msg);
      $em->flush();
      $request->getSession()->getFlashBag()->add('success', 'Message envoyé'); //message flash
          return $this->render('AppartooMessagerieBundle:Conversation:send.html.twig', array('id' => $friend->getId() , 'form' => $form->createView(), 'friend' => $friend));
      }
    }
    return $this->render('AppartooMessagerieBundle:Conversation:send.html.twig', array('id' => $friend->getId() , 'form' => $form->createView(), 'friend' => $friend));
  }
}
