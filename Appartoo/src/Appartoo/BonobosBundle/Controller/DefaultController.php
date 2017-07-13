<?php

namespace Appartoo\BonobosBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('AppartooBonobosBundle:Default:index.html.twig');
    }
}
