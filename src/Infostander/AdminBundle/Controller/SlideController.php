<?php

namespace Infostander\AdminBundle\Controller;

use Infostander\AdminBundle\Entity\Slide;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Cookie;

class SlideController extends Controller {
  public function indexAction(Request $request) {
    $slides = $this->getDoctrine()
      ->getRepository('InfostanderAdminBundle:Slide')->findBy(array(), array('title' => 'asc'));

    // Get show archived cookie
    $showArchived = 0;
    $showArchivedCookie = $request->cookies->get('SHOW_ARCHIVED');
    if (isset($showArchivedCookie)) {
      $showArchived = $showArchivedCookie;
    }

    return $this->render(
      'InfostanderAdminBundle:Slide:index.html.twig',
      array('slides' => $slides, 'showarchived' => $showArchived)
    );
  }

  public function toggleShowArchivedAction(Request $request) {
    // Get show archived cookie
    $showArchived = 1;
    $showArchivedCookie = $request->cookies->get('SHOW_ARCHIVED');
    if (isset($showArchivedCookie)) {
      if ($showArchivedCookie == 1)  {
        $showArchived = 0;
      }
      else {
        $showArchived = 1;
      }
    }

    $cookie = new Cookie('SHOW_ARCHIVED', $showArchived, 0, '/', null, false, false);
    $response = new RedirectResponse($this->generateUrl("infostander_admin_slide"));
    $response->headers->setCookie($cookie);
    return $response;
  }

  public function addAction(Request $request) {
    $slide = new Slide();

    $form = $this->createForm('slide', $slide);

    $form->handleRequest($request);

    if ($form->isValid()) {
      $manager = $this->getDoctrine()->getManager();

      $slide->setArchived(FALSE);

      $manager->persist($slide);
      $manager->flush();

      return $this->redirect($this->generateUrl("infostander_admin_slide"));
    }

    return $this->render('InfostanderAdminBundle:Slide:add.html.twig', array(
      'form' => $form->createView(),
    ));
  }

  public function deleteAction($id) {
    $slide = $this->getDoctrine()->getRepository('InfostanderAdminBundle:Slide')
      ->find($id);

    if ($slide != NULL) {
      $manager = $this->getDoctrine()->getManager();
      $manager->remove($slide);
      $manager->flush();
    }
    return $this->redirect($this->generateUrl("infostander_admin_slide"));
  }

  public function toggleArchivedAction($id) {
    $slide = $this->getDoctrine()->getRepository('InfostanderAdminBundle:Slide')
      ->find($id);

    if ($slide != NULL) {
      $manager = $this->getDoctrine()->getManager();

      $slide->setArchived(!$slide->getArchived());

      $manager->flush();
    }
    return $this->redirect($this->generateUrl("infostander_admin_slide"));
  }
}
