<?php

namespace Infostander\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ScreenController extends Controller
{
    public function indexAction()
    {
        return $this->render('InfostanderAdminBundle:Screen:index.html.twig');
    }
}
