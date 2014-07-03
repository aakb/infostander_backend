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
 * Class ApiController
 *
 * Controller for the API.
 *
 * @package Infostander\AdminBundle\Controller
 */
class ApiController extends Controller
{

    /**
     * Return a response with only a HTTP status code.
     *
     * @param $response_code
     * @return Response
     */
    protected function onlyResponseCode($response_code)
    {
        $response = new Response("", $response_code);
        return $response;
    }

    /**
     * Handler for the screenGet action.
     *
     * @return Response
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

        // Get the screen entity with the given token.
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

        // Return the json response.
        return new Response(json_encode($response_data), 200);
    }

    /**
     * Handler for the screenActivate action.
     *
     * @return Response
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

        // Set token in screen and persist the screen to the db.
        $screen->setToken($body->token);
        $manager = $this->getDoctrine()->getManager();
        $manager->persist($screen);
        $manager->flush();

        // Generate the response.
        return $this->onlyResponseCode(200);
    }
}
