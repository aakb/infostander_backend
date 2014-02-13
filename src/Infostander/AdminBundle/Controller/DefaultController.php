<?php
/**
 * @file
 * @TODO missing descriptions.
 */

namespace Infostander\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @TODO missing descriptions.
 */
class DefaultController extends Controller {

  /**
   * @TODO missing descriptions.
   */
  public function indexAction() {
    return $this->render('InfostanderAdminBundle:Default:index.html.twig');
  }
}
