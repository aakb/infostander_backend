<?php
/**
 * @file
 * Screen controller.
 *
 * @TODO missing descriptions.
 */

namespace Infostander\AdminBundle\Controller;

use Infostander\AdminBundle\Entity\Screen;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ScreenController
 *
 * @TODO missing descriptions.
 *
 * @package Infostander\AdminBundle\Controller
 */
class ScreenController extends Controller {

  /**
   * @TODO missing descriptions.
   *
   * @return int
   */
  private function getNewActivationCode() {
    do {
      $code = rand(100000, 999999);
      $screen = $this->getDoctrine()->getRepository('InfostanderAdminBundle:Screen')->findByActivationCode($code);
    } while($screen != NULL);

    return $code;
  }

  /**
   * @TODO missing descriptions.
   */
  public function indexAction() {
    $screens = $this->getDoctrine()
      ->getRepository('InfostanderAdminBundle:Screen')->findAll();

    return $this->render(
      'InfostanderAdminBundle:Screen:index.html.twig',
      array('screens' => $screens)
    );
  }

  /**
   * @TODO missing descriptions.
   */
  public function addAction(Request $request) {
    $screen = new Screen();

    $form = $this->createForm('screen', $screen);

    $form->handleRequest($request);

    if ($form->isValid()) {
      $screen->setActivationCode($this->getNewActivationCode());
      $screen->setToken("");
      $screen->setGroups(array("infostander"));

      $manager = $this->getDoctrine()->getManager();
      $manager->persist($screen);
      $manager->flush();

      return $this->redirect($this->generateUrl("infostander_admin_screen"));
    }

    return $this->render('InfostanderAdminBundle:Screen:add.html.twig', array(
      'form' => $form->createView(),
    ));
  }

  /**
   * @TODO missing descriptions.
   */
  public function deleteAction($id) {
    $screen = $this->getDoctrine()
      ->getRepository('InfostanderAdminBundle:Screen')->find($id);

    if ($screen->getToken() != "") {
      // Send  post request to middleware (/screen/remove).
      $ch = curl_init();
      $fields = array('token' => $screen->getToken());
      $postvars = '';
      foreach ($fields as $key => $value) {
        $postvars .= $key . $value;
      }
      $url = $this->container->getParameter("middleware_host") . "/screen/remove";
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_POST, count($fields));
      curl_setopt($ch, CURLOPT_POSTFIELDS, $postvars);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
      curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
      curl_setopt($ch, CURLOPT_TIMEOUT, 20);
      curl_exec($ch);
      curl_close($ch);
    }

    if ($screen != NULL) {
      $manager = $this->getDoctrine()->getManager();
      $manager->remove($screen);
      $manager->flush();
    }

    return $this->redirect($this->generateUrl("infostander_admin_screen"));
  }

  /**
   * @TODO missing descriptions.
   */
  public function newActivationCodeAction($id) {
    $screen = $this->getDoctrine()
      ->getRepository('InfostanderAdminBundle:Screen')->find($id);

    if ($screen != NULL) {
      $screen->setActivationCode($this->getNewActivationCode());

      $manager = $this->getDoctrine()->getManager();
      $manager->persist($screen);
      $manager->flush();
    }
    return $this->redirect($this->generateUrl("infostander_admin_screen"));
  }
}
