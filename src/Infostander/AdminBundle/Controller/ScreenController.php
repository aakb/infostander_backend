<?php
/**
 * @file
 * This file is a part of the Infostander AdminBundle.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Infostander\AdminBundle\Controller;

use Infostander\AdminBundle\Entity\Screen;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\Query;

/**
 * Class ScreenController
 *
 * Controller for screens.
 *
 * @package Infostander\AdminBundle\Controller
 */
class ScreenController extends Controller
{

    /**
     * Generates a new unique activation code in the interval between 100000 and 999999.
     *
     * @return int
     */
    protected function getNewActivationCode()
    {
        do {
            // Pick a random activation code between 100000000 and 999999999.
            $code = rand(100000000, 999999999);

            // Test if the activation code already exists in the db.
            $screen = $this->getDoctrine()->getRepository('InfostanderAdminBundle:Screen')->findByActivationCode($code);
        } while ($screen != null);

        return $code;
    }

    /**
     * Handler for the index action.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        // Get all tokens for screens that have tokens.
        $em = $this->getDoctrine()->getManager();
        $qb = $em->getRepository('InfostanderAdminBundle:Screen')->createQueryBuilder('s');
        $query = $qb->select('s.token')
           ->where('s.token != :str')
           ->setParameter('str', '')
           ->getQuery();
        $tokens = $query->getArrayResult();

        // Flatten the array.
        $tokens = array_map('current', $tokens);

        // Get heartbeats from middleware.
        $beats = json_decode($this->getHeartbeats($tokens));

        // Get all the Screens.
        $screens = $this->getDoctrine()->getRepository('InfostanderAdminBundle:Screen')->findAll();

        // Add times to screens
        foreach ($screens as $screen) {
          if (property_exists($beats, $screen->getToken()) && !empty($beats->{$screen->getToken()})) {
            $screen->setHeartbeat($beats->{$screen->getToken()});
          }
        }

        // Return the rendering of the Screen:index template.
        return $this->render(
            'InfostanderAdminBundle:Screen:index.html.twig',
            array('screens' => $screens)
        );
    }

    private function getHeartbeats($tokens) {
      $json = json_encode(array(
        'screens' => $tokens,
      ));

      // Send post request to middleware (/screen/reload).
      $url = $this->container->getParameter("middleware_host") . "/status";
      $ch = curl_init($url);
      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
      curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-type: application/json',
        'Content-Length: ' . strlen($json),
      ));

      curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
      curl_setopt($ch, CURLOPT_TIMEOUT, 5);

      // Get information.
      $content = curl_exec($ch);
      $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

      // Close connection.
      curl_close($ch);

      if ($http_status == 200) {
        return $content;
      }

      return array();
    }

    /**
     * Handler for the add action.
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function addAction(Request $request)
    {
        // Make a new screen.
        $screen = new Screen();

        // Generate the form for the screen.
        $form = $this->createForm('screen', $screen);

        // Handle the request.
        $form->handleRequest($request);

        // If the form has been submitted, make persist the screen.
        if ($form->isValid()) {
            // Set default values for the screen.
            $screen->setActivationCode($this->getNewActivationCode());
            $screen->setToken("");
            $screen->setGroups(array("infostander"));

            if (is_null($screen->getDescription())) {
                $screen->setDescription("");
            }

            // Persist the screen to the db.
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($screen);
            $manager->flush();

            // Redirect to the Screen:index page.
            return $this->redirect($this->generateUrl("infostander_admin_screen"));
        }

        // Return the rendering of the Screen:add template.
        return $this->render('InfostanderAdminBundle:Screen:add.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * Handler for the delete action.
     *
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction($id)
    {
        $screen = $this->getDoctrine()
            ->getRepository('InfostanderAdminBundle:Screen')->find($id);

        if ($screen->getToken() != "") {
            $json = json_encode(array(
                'token' => $screen->getToken(),
            ));

            // Send  post request to middleware (/screen/remove).
            $url = $this->container->getParameter("middleware_host") . "/screen/remove";
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
            curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-type: application/json',
                'Content-Length: ' . strlen($json),
            ));

            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_exec($ch);
            curl_close($ch);
        }

        if ($screen != null) {
            $manager = $this->getDoctrine()->getManager();
            $manager->remove($screen);
            $manager->flush();
        }

        return $this->redirect($this->generateUrl("infostander_admin_screen"));
    }

    /**
     * Handler for the new activation code action.
     *
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function newActivationCodeAction($id)
    {
        $screen = $this->getDoctrine()
            ->getRepository('InfostanderAdminBundle:Screen')->find($id);

        if ($screen != null) {
            $screen->setActivationCode($this->getNewActivationCode());

            $manager = $this->getDoctrine()->getManager();
            $manager->persist($screen);
            $manager->flush();
        }
        return $this->redirect($this->generateUrl("infostander_admin_screen"));
    }

    /**
     * Handler for the reload action
     *
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function reloadAction($id)
    {
        $screen = $this->getDoctrine()->getRepository('InfostanderAdminBundle:Screen')->find($id);

        $tokens = array(
            $screen->getToken(),
        );

        $json = json_encode(array(
            'screens' => $tokens,
            'groups'  => array()
        ));

        // Send post request to middleware (/screen/reload).
        $url = $this->container->getParameter("middleware_host") . "/screen/reload";
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-type: application/json',
            'Content-Length: ' . strlen($json),
        ));

        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_exec($ch);
        curl_close($ch);

        return $this->redirect($this->generateUrl("infostander_admin_screen"));
    }
}
