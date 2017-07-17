<?php

namespace Appartoo\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Appartoo\UserBundle\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;

class UserController extends Controller
{
    /**
    * @Security("has_role('ROLE_USER')")
    */
    public function userAction($username){

      $userManager = $this->get('fos_user.user_manager');
      $user = $userManager->findUserBy(array('username' => $username));
      if (null === $user) { // si l'utilisateur n'existe pas
      throw new NotFoundHttpException("Cet utilisateur n'existe pas");
    }
      return $this->render('AppartooUserBundle:User:user.html.twig', array('user' => $user));
    }

    /**
    * @Security("has_role('ROLE_USER')")
    */
    public function profilAction($username){
      $user = $this->getUser();
      return $this->render('AppartooUserBundle:User:user.html.twig', array('user' => $user , 'username' => $username));
    }

    /**
    * @Security("has_role('ROLE_USER')")
    */
    public function modifAction(Request $request){
      $user = $this->getUser();
      $formBuilder = $this->get('form.factory')->createBuilder(FormType::class, $user);
      $formBuilder
        ->add('birthday', DateType::class, array('label' => "Date de naissance" , 'years' => range(1950,2017)))
        ->add('famille', TextType::class, array('label' => "Famille"))
        ->add('race', TextType::class, array('label' => "Race"))
        ->add('nourriture', TextType::class, array('label' => "Nourriture"))
        ->add('add', SubmitType::class, array('label' => "Modifier mes informations"));
      $form = $formBuilder->getForm();
      if ($request->isMethod('POST')) { // si formulaire envoyé :
        $form->handleRequest($request);
        if ($form->isValid()){
          $user->setBirthday($form->get('birthday')->getData());
          $user->setFamille($form->get('famille')->getData());
          $user->setRace($form->get('race')->getData());
          $user->setNourriture($form->get('nourriture')->getData());
          $em = $this->getDoctrine()->getManager();
          $em->persist($user);
          $em->flush();
        $request->getSession()->getFlashBag()->add('success', 'Informations mises à jour'); //message flash
        return $this->render('AppartooUserBundle:User:user.html.twig' , array('user' => $user));
        }
      }
      return $this->render('AppartooUserBundle:User:modif.html.twig', array('form' => $form->createView() , 'user' => $user));
    }

    /**
         * @Route("/users", name="users_list")
         * @Method({"GET"})
         */
    public function getUsersAction(Request $request)
        {
            $users = $this->getDoctrine()->getManager()
                    ->getRepository('AppartooUserBundle:User')
                    ->findBy(array(), array('username' => 'asc'));

            $formatted = [];
            foreach ($users as $user) {

                $formatted[] = [
                   'id' => $user->getId(),
                   'username' => $user->getUsername(),
                   'email' => $user->getEmail(),
                ];
            }

            return new JsonResponse($formatted);
        }


}
