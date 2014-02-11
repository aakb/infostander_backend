<?php

namespace Infostander\AdminBundle\Controller;

use Infostander\AdminBundle\Entity\Booking;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class BookingController extends Controller {
  public function indexAction() {
    $bookings = $this->getDoctrine()->getRepository('InfostanderAdminBundle:Booking')->findBy(array(), array('sortOrder' => 'desc'));

    return $this->render('InfostanderAdminBundle:Booking:index.html.twig', array(
      'bookings' => $bookings
    ));
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

      $largestSortOrderBooking = $this->getDoctrine()->getRepository('InfostanderAdminBundle:Booking')->findOneBy(array(), array('sortOrder' => 'desc'));

      $booking->setStartDate(new \DateTime($booking->getStartDate()));
      $booking->setEndDate(new \DateTime($booking->getEndDate()));
      $booking->setSortOrder($largestSortOrderBooking->getSortOrder() + 1);

      $manager->persist($booking);
      $manager->flush();

      return $this->redirect($this->generateUrl("infostander_admin_booking"));
    }

    return $this->render('InfostanderAdminBundle:Booking:add.html.twig', array(
      'form' => $form->createView(), 'slides'=>$slides
    ));
  }

  public function editAction(Request $request, $id) {
    $manager = $this->getDoctrine()->getManager();
    $booking = $this->getDoctrine()->getRepository('InfostanderAdminBundle:Booking')->findOneById($id);
    if (!$booking) {
      return $this->redirect($this->generateUrl("infostander_admin_booking"));
    }

    $booking->setStartDate(date("d-m-Y H:i", date_timestamp_get($booking->getStartDate())));
    $booking->setEndDate(date("d-m-Y H:i", date_timestamp_get($booking->getEndDate())));

    $slides = $this->getDoctrine()->getRepository('InfostanderAdminBundle:Slide')->findBy(array('archived' => false), array('title' => 'asc'));

    $form = $this->createForm('booking', $booking, array(
      'choice_options' => $slides
    ));

    $form->handleRequest($request);

    if ($form->isValid()) {

      $booking->setStartDate(new \DateTime($booking->getStartDate()));
      $booking->setEndDate(new \DateTime($booking->getEndDate()));

      $manager->flush();

      return $this->redirect($this->generateUrl("infostander_admin_booking"));
    }

    return $this->render('InfostanderAdminBundle:Booking:edit.html.twig', array(
      'form' => $form->createView(), 'slides'=>$slides, 'id'=>$id, 'slide_id'=>$booking->getSlideId()
    ));
  }

  public function changeSortOrderAction($id, $updown) {
    $manager = $this->getDoctrine()->getManager();

    $booking = $this->getDoctrine()->getRepository('InfostanderAdminBundle:Booking')->findOneById($id);
    $bookingSortOrder = $booking->getSortOrder();

    if ($updown == 'up') {
      $change = 1;
    }
    else {
      $change = -1;
    }

    $otherBooking = $this->getDoctrine()->getRepository('InfostanderAdminBundle:Booking')->findOneBy(array('sortOrder' => $bookingSortOrder + $change));
    if ($otherBooking) {
      $booking->setSortOrder($bookingSortOrder + $change);
      $manager->flush();
      $otherBooking->setSortOrder($bookingSortOrder);
      $manager->flush();
    }

    return $this->redirect($this->generateUrl("infostander_admin_booking"));
  }
}
