<?php

namespace Infostander\AdminBundle\Controller;

use Infostander\AdminBundle\Entity\Slide;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class SlideController extends Controller
{
    public function indexAction()
    {
      $slides = $this->getDoctrine()->getRepository('InfostanderAdminBundle:Slide')->findAll();
      return $this->render(
        'InfostanderAdminBundle:Slide:index.html.twig',
        array('slides' => $slides)
      );
    }

  public function addAction(Request $request)
  {
    $slide = new Slide();

    $form = $this->createForm('slide', $slide);

    $form->handleRequest($request);

    if ($form->isValid()) {
      $manager = $this->getDoctrine()->getManager();
      $manager->persist($slide);
      $manager->flush();

      return $this->redirect($this->generateUrl("infostander_admin_slide"));
    }

    return $this->render('InfostanderAdminBundle:Slide:add.html.twig', array(
      'form' => $form->createView(),
    ));
  }

  public function deleteAction($id) {
    $slide = $this->getDoctrine()->getRepository('InfostanderAdminBundle:Slide')->find($id);

    if ($slide != null) {
      $manager = $this->getDoctrine()->getManager();
      $manager->remove($slide);
      $manager->flush();
    }
    return $this->redirect($this->generateUrl("infostander_admin_slide"));
  }
}
