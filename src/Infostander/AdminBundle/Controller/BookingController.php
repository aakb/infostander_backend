<?php

namespace Infostander\AdminBundle\Controller;

use Infostander\AdminBundle\Entity\Booking;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class BookingController extends Controller {
  public function indexAction() {
    return $this->render('InfostanderAdminBundle:Booking:index.html.twig');
  }

  public function addAction(Request $request) {
    $booking = new Booking();

    $slides = $this->getDoctrine()->getRepository('InfostanderAdminBundle:Slide')->findBy(array('archived' => false), array('title' => 'asc'));

    $form = $this->createForm('booking', $booking, array(
      'choice_options' => $slides
    ));

    $form->handleRequest($request);

    if ($form->isValid()) {
      $manager = $this->getDoctrine()->getManager();

      $booking->setStartDate(new \DateTime($booking->getStartDate()));
      $booking->setEndDate(new \DateTime($booking->getEndDate()));

      $manager->persist($booking);
      $manager->flush();

      return $this->redirect($this->generateUrl("infostander_admin_booking"));
    }

    return $this->render('InfostanderAdminBundle:Booking:add.html.twig', array(
      'form' => $form->createView(), 'slides'=>$slides
    ));
  }
}
