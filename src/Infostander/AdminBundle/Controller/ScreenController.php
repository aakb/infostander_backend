<?php

namespace Infostander\AdminBundle\Controller;

use Infostander\AdminBundle\Entity\Screen;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ScreenController extends Controller
{
    public function indexAction()
    {
      $screens = $this->getDoctrine()->getRepository('InfostanderAdminBundle:Screen')->findAll();
      return $this->render(
        'InfostanderAdminBundle:Screen:index.html.twig',
        array('screens' => $screens)
      );
    }

  public function addAction(Request $request)
  {
    $screen = new Screen();

    $form = $this->createForm('screen', $screen);

    $form->handleRequest($request);

    if ($form->isValid()) {
      $screen->setActivationCode(rand(10000, 99999));
      $screen->setToken("");

      $manager = $this->getDoctrine()->getManager();
      $manager->persist($screen);
      $manager->flush();

      return $this->redirect($this->generateUrl("infostander_admin_screen"));
    }

    return $this->render('InfostanderAdminBundle:Screen:add.html.twig', array(
      'form' => $form->createView(),
    ));
  }

  public function deleteAction($id) {
    $screen = $this->getDoctrine()->getRepository('InfostanderAdminBundle:Screen')->find($id);

    if ($screen != null) {
      $manager = $this->getDoctrine()->getManager();
      $manager->remove($screen);
      $manager->flush();
    }
    return $this->redirect($this->generateUrl("infostander_admin_screen"));
  }

  public function newActivationCodeAction($id) {
    $screen = $this->getDoctrine()->getRepository('InfostanderAdminBundle:Screen')->find($id);

    if ($screen != null) {
      $screen->setActivationCode(rand(10000, 99999));

      $manager = $this->getDoctrine()->getManager();
      $manager->persist($screen);
      $manager->flush();
    }
    return $this->redirect($this->generateUrl("infostander_admin_screen"));
  }
}
