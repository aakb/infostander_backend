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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @TODO missing descriptions.
 */
class ApiController extends Controller
{

    /**
     * @TODO missing descriptions.
     */
    private function onlyResponseCode($response_code)
    {
        $response = new Response("", $response_code);
        return $response;
    }

    /**
     * @TODO missing descriptions.
     */
    public function screenGetAction()
    {
        // Get request body as array.
        $request = Request::createFromGlobals();
        $body = json_decode($request->getContent());

        // Test for valid request parameters.
        if (!isset($body->token)) {
            return $this->onlyResponseCode(403);
        }

        // Get the screen entity på token.
        $screen = $this->getDoctrine()->getRepository('InfostanderAdminBundle:Screen')->findOneByToken($body->token);

        // Test for valid screen.
        if (!isset($screen)) {
            return $this->onlyResponseCode(404);
        }

        // Generate the response.
        $response_data = array(
            'statusCode' => 200,
            'id' => $screen->getId(),
            'name' => $screen->getTitle(),
            'groups' => $screen->getGroups(),
        );
        return new Response(json_encode($response_data), 200);
    }

    /**
     * @TODO missing descriptions.
     */
    public function screenActivateAction()
    {
        // Get request body as array.
        $request = Request::createFromGlobals();
        $body = json_decode($request->getContent());

        // Test for valid request parameters.
        if (!isset($body->token) || !isset($body->activationCode)) {
            return $this->onlyResponseCode(403);
        }

        // Get the screen entity på activationCode.
        $screen = $this->getDoctrine()->getRepository('InfostanderAdminBundle:Screen')->findOneByActivationCode($body->activationCode);

        // Test for valid screen.
        if (!isset($screen)) {
            return $this->onlyResponseCode(403);
        }

        // Set token in screen and persist to database.
        $screen->setToken($body->token);
        $screen->setActivationCode(0);
        $manager = $this->getDoctrine()->getManager();
        $manager->persist($screen);
        $manager->flush();

        // Generate the response.
        return $this->onlyResponseCode(200);
    }
}
