<?php

namespace Infostander\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class UserController extends Controller {
  public function indexAction() {
    $users = $this->getDoctrine()->getRepository('InfostanderAdminBundle:User')->findBy(array(), array('username' => 'asc'));

    return $this->render(
      'InfostanderAdminBundle:User:index.html.twig',
      array('users' => $users)
    );
  }
}
