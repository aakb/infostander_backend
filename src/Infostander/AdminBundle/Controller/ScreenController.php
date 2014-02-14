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
            // Pick a random activation code between 100000 and 999999.
            $code = rand(100000, 999999);

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
        // Get all the Screens.
        $screens = $this->getDoctrine()->getRepository('InfostanderAdminBundle:Screen')->findAll();

        // Return the rendering of the Screen:index template.
        return $this->render(
            'InfostanderAdminBundle:Screen:index.html.twig',
            array('screens' => $screens)
        );
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
}
