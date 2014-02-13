<?php
/**
 * @file
 * @TODO missing descriptions.
 */

namespace Infostander\AdminBundle\Controller;

use Infostander\AdminBundle\Entity\Slide;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Cookie;

/**
 * @TODO missing descriptions.
 */
class SlideController extends Controller {

  /**
   * @TODO missing descriptions.
   */
  public function indexAction(Request $request) {
    $slides = $this->getDoctrine()
      ->getRepository('InfostanderAdminBundle:Slide')->findBy(array(), array('title' => 'asc'));

    // Get show archived cookie.
    $show_archived = 0;
    $show_archived_cookie = $request->cookies->get('SHOW_ARCHIVED');
    if (isset($show_archived_cookie)) {
      $show_archived = $show_archived_cookie;
    }

    return $this->render(
      'InfostanderAdminBundle:Slide:index.html.twig',
      array('slides' => $slides, 'showarchived' => $show_archived)
    );
  }

  /**
   * @TODO missing descriptions.
   */
  public function toggleShowArchivedAction(Request $request) {
    // Get show archived cookie.
    $show_archived = 1;
    $show_archived_cookie = $request->cookies->get('SHOW_ARCHIVED');
    if (isset($show_archived_cookie)) {
      if ($show_archived_cookie == 1) {
        $show_archived = 0;
      }
      else {
        $show_archived = 1;
      }
    }

    $cookie = new Cookie('SHOW_ARCHIVED', $show_archived, 0, '/', NULL, FALSE, FALSE);
    $response = new RedirectResponse($this->generateUrl("infostander_admin_slide"));
    $response->headers->setCookie($cookie);
    return $response;
  }

  /**
   * @TODO missing descriptions.
   */
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

  /**
   * @TODO missing descriptions.
   */
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

  /**
   * @TODO missing descriptions.
   */
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
