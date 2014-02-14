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
 * @TODO missing descriptions.
 */
class UserController extends Controller
{

    /**
     * @TODO missing descriptions.
     */
    public function indexAction()
    {
        $users = $this->getDoctrine()->getRepository('InfostanderAdminBundle:User')->findBy(array(), array('username' => 'asc'));

        return $this->render(
            'InfostanderAdminBundle:User:index.html.twig',
            array('users' => $users)
        );
    }
}
