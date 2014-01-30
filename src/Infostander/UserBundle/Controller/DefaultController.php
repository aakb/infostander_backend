<?php

namespace Infostander\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('InfostanderUserBundle:Default:index.html.twig', array('name' => $name));
    }
}
