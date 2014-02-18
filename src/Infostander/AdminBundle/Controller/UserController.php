<?php
/**
 * @file
 * This file is a part of the Infostander AdminBundle.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Infostander\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class UserController
 *
 * Controller for users.
 *
 * @package Infostander\AdminBundle\Controller
 */
class UserController extends Controller
{

    /**
     * Handler for the index action.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        // Get all users from the db.
        $users = $this->getDoctrine()->getRepository('InfostanderAdminBundle:User')
            ->findBy(
                array(),
                array('username' => 'asc')
            );

        // Return the rendering of the User:index template.
        return $this->render(
            'InfostanderAdminBundle:User:index.html.twig',
            array('users' => $users)
        );
    }

    public function deleteAction($id)
    {
        // Get user with $id
        $user = $this->getDoctrine()
            ->getRepository('InfostanderAdminBundle:User')->find($id);

        // Check that user exists and is not the super admin
        if (!$user || $user->hasRole('ROLE_SUPER_ADMIN')) {
            return $this->redirect($this->generateUrl("infostander_admin_user"));
        }

        // Remove user from db
        $manager = $this->getDoctrine()->getManager();
        $manager->remove($user);
        $manager->flush();

        // Redirect to User:index
        return $this->redirect($this->generateUrl("infostander_admin_user"));
    }
}
