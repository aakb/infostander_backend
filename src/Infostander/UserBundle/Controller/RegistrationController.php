<?php

namespace Infostander\UserBundle\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\Controller\RegistrationController as BaseController;

class RegistrationController extends BaseController {
  public function registerAction(Request $request) {
    /** @var $formFactory \FOS\UserBundle\Form\Factory\FactoryInterface */
    $formFactory = $this->container->get('fos_user.registration.form.factory');
    /** @var $userManager \FOS\UserBundle\Model\UserManagerInterface */
    $userManager = $this->container->get('fos_user.user_manager');
    /** @var $dispatcher \Symfony\Component\EventDispatcher\EventDispatcherInterface */
    $dispatcher = $this->container->get('event_dispatcher');

    $user = $userManager->createUser();
    $user->setEnabled(TRUE);

    $event = new GetResponseUserEvent($user, $request);
    $dispatcher->dispatch(FOSUserEvents::REGISTRATION_INITIALIZE, $event);

    if (NULL !== $event->getResponse()) {
      return $event->getResponse();
    }

    $form = $formFactory->createForm();
    $form->setData($user);

    if ('POST' === $request->getMethod()) {
      $form->bind($request);

      if ($form->isValid()) {
        $event = new FormEvent($form, $request);
        $dispatcher->dispatch(FOSUserEvents::REGISTRATION_SUCCESS, $event);

        $userManager->updateUser($user);

        if (NULL === $response = $event->getResponse()) {
          $url = $this->container->get('router')
            ->generate('infostander_admin_user');
          $response = new RedirectResponse($url);
        }

        return $response;
      }
    }

    return $this->container->get('templating')
      ->renderResponse('FOSUserBundle:Registration:register.html.' . $this->getEngine(), array(
      'form' => $form->createView(),
    ));
  }

}
