<?php
/**
 * @file
 * @TODO missing descriptions.
 */

namespace Infostander\AdminBundle\Controller;

use Infostander\AdminBundle\Entity\Booking;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * @TODO missing descriptions.
 */
class BookingController extends Controller {

  /**
   * @TODO missing descriptions.
   */
  public function indexAction() {
    $bookings = $this->getDoctrine()->getRepository('InfostanderAdminBundle:Booking')->findBy(array(), array('sortOrder' => 'desc'));

    return $this->render('InfostanderAdminBundle:Booking:index.html.twig', array(
      'bookings' => $bookings,
    ));
  }

  /**
   * @TODO missing descriptions.
   */
  public function addAction(Request $request) {
    $booking = new Booking();

    $slides = $this->getDoctrine()->getRepository('InfostanderAdminBundle:Slide')->findBy(array('archived' => FALSE), array('title' => 'asc'));

    $form = $this->createForm('booking', $booking, array(
      'choice_options' => $slides,
    ));

    $form->handleRequest($request);

    if ($form->isValid()) {
      $manager = $this->getDoctrine()->getManager();

      $largest_sort_order_booking = $this->getDoctrine()->getRepository('InfostanderAdminBundle:Booking')->findOneBy(array(), array('sortOrder' => 'desc'));

      $booking->setStartDate(new \DateTime($booking->getStartDate()));
      $booking->setEndDate(new \DateTime($booking->getEndDate()));
      $booking->setSortOrder($largest_sort_order_booking->getSortOrder() + 1);

      $manager->persist($booking);
      $manager->flush();

      return $this->redirect($this->generateUrl("infostander_admin_booking"));
    }

    return $this->render('InfostanderAdminBundle:Booking:add.html.twig', array(
      'form' => $form->createView(),
      'slides' => $slides,
    ));
  }

  /**
   * @TODO missing descriptions.
   */
  public function editAction(Request $request, $id) {
    $manager = $this->getDoctrine()->getManager();
    $booking = $this->getDoctrine()->getRepository('InfostanderAdminBundle:Booking')->findOneById($id);
    if (!$booking) {
      return $this->redirect($this->generateUrl("infostander_admin_booking"));
    }

    $booking->setStartDate(date("d-m-Y H:i", date_timestamp_get($booking->getStartDate())));
    $booking->setEndDate(date("d-m-Y H:i", date_timestamp_get($booking->getEndDate())));

    $slides = $this->getDoctrine()->getRepository('InfostanderAdminBundle:Slide')->findBy(array('archived' => FALSE), array('title' => 'asc'));

    $form = $this->createForm('booking', $booking, array(
      'choice_options' => $slides,
    ));

    $form->handleRequest($request);

    if ($form->isValid()) {

      $booking->setStartDate(new \DateTime($booking->getStartDate()));
      $booking->setEndDate(new \DateTime($booking->getEndDate()));

      $manager->flush();

      return $this->redirect($this->generateUrl("infostander_admin_booking"));
    }

    return $this->render('InfostanderAdminBundle:Booking:edit.html.twig', array(
      'form' => $form->createView(),
      'slides' => $slides,
      'id' => $id,
      'slide_id' => $booking->getSlideId(),
    ));
  }

  /**
   * @TODO missing descriptions.
   */
  public function changeSortOrderAction($id, $updown) {
    $manager = $this->getDoctrine()->getManager();

    $booking = $this->getDoctrine()->getRepository('InfostanderAdminBundle:Booking')->findOneById($id);
    $booking_sort_order = $booking->getSortOrder();

    if ($updown == 'up') {
      $change = 1;
    }
    else {
      $change = -1;
    }

    $other_booking = $this->getDoctrine()->getRepository('InfostanderAdminBundle:Booking')->findOneBy(array('sortOrder' => $booking_sort_order + $change));
    if ($other_booking) {
      $booking->setSortOrder($booking_sort_order + $change);
      $manager->flush();
      $other_booking->setSortOrder($booking_sort_order);
      $manager->flush();
    }

    return $this->redirect($this->generateUrl("infostander_admin_booking"));
  }
}
