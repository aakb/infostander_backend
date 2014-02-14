<?php
/**
 * @file
 * This file is a part of the Infostander AdminBundle.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Infostander\AdminBundle\Controller;

use Infostander\AdminBundle\Entity\Booking;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class BookingController
 *
 * Controller for bookings.
 *
 * @package Infostander\AdminBundle\Controller
 */
class BookingController extends Controller
{

    /**
     * Handler for the index action.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        // Get all bookings sorted by sortOrder.
        $bookings = $this->getDoctrine()->getRepository('InfostanderAdminBundle:Booking')
            ->findBy(
                array(),
                array('sortOrder' => 'desc')
            );

        // Return the rendering of the Booking:index template.
        return $this->render('InfostanderAdminBundle:Booking:index.html.twig', array(
            'bookings' => $bookings,
        ));
    }

    /**
     * Handler for the add action.
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function addAction(Request $request)
    {
        // Make a new Booking.
        $booking = new Booking();

        // Find all slides that are not archived.
        $slides = $this->getDoctrine()->getRepository('InfostanderAdminBundle:Slide')
            ->findBy(
                array('archived' => false),
                array('title' => 'asc')
            );

        // Create the form for the booking, with the non-archived slides as option.
        $form = $this->createForm('booking', $booking, array(
            'choice_options' => $slides,
        ));

        // Handle the request.
        $form->handleRequest($request);

        // If this is a submit of the form, persist the new booking.
        if ($form->isValid()) {
            // Find the largest sort order number in the db.
            $largest_sort_order_booking = $this->getDoctrine()
                ->getRepository('InfostanderAdminBundle:Booking')
                ->findOneBy(
                    array(),
                    array('sortOrder' => 'desc')
                );
            // Set sortOrder to 1 higher than previous largest sortOrder.
            $booking->setSortOrder($largest_sort_order_booking->getSortOrder() + 1);

            // Change dates to DateTime.
            $booking->setStartDate(new \DateTime($booking->getStartDate()));
            $booking->setEndDate(new \DateTime($booking->getEndDate()));

            // Persist to the db.
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($booking);
            $manager->flush();

            // Redirect to Booking:index page.
            return $this->redirect($this->generateUrl("infostander_admin_booking"));
        }

        // Return the rendering of the Booking:add template.
        return $this->render('InfostanderAdminBundle:Booking:add.html.twig', array(
            'form' => $form->createView(),
            'slides' => $slides,
        ));
    }

    /**
     * Handler for the edit action.
     *
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, $id)
    {
        // Get booking with $id.
        $booking = $this->getDoctrine()->getRepository('InfostanderAdminBundle:Booking')->findOneById($id);

        // If no booking is found, redirect to Booking:index page.
        if (!$booking) {
            return $this->redirect($this->generateUrl("infostander_admin_booking"));
        }

        // Change format from DateTime to "d-m-Y H:i".
        $booking->setStartDate(date("d-m-Y H:i", date_timestamp_get($booking->getStartDate())));
        $booking->setEndDate(date("d-m-Y H:i", date_timestamp_get($booking->getEndDate())));

        // Get all slides that are not archived.
        $slides = $this->getDoctrine()->getRepository('InfostanderAdminBundle:Slide')
            ->findBy(
                array('archived' => false),
                array('title' => 'asc')
            );

        // Create the form for the booking.
        $form = $this->createForm('booking', $booking, array(
            'choice_options' => $slides,
        ));

        // Handle the request.
        $form->handleRequest($request);

        // If this is a submit, persist the changes.
        if ($form->isValid()) {
            // Change dates to DateTime.
            $booking->setStartDate(new \DateTime($booking->getStartDate()));
            $booking->setEndDate(new \DateTime($booking->getEndDate()));

            // Persist to the db.
            $manager = $this->getDoctrine()->getManager();
            $manager->flush();

            // Redirect to the Booking:index page.
            return $this->redirect($this->generateUrl("infostander_admin_booking"));
        }

        // Return the rendering of the Booking:edit template.
        return $this->render('InfostanderAdminBundle:Booking:edit.html.twig', array(
            'form' => $form->createView(),
            'slides' => $slides,
            'id' => $id,
            'slide_id' => $booking->getSlideId(),
        ));
    }

    /**
     * Handler for the changeSortOrder action.
     *
     * @param $id
     * @param $updown
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function changeSortOrderAction($id, $updown)
    {
        // Get the booking with $id.
        $booking = $this->getDoctrine()->getRepository('InfostanderAdminBundle:Booking')->findOneById($id);

        // Get the current sort order of the booking.
        $booking_sort_order = $booking->getSortOrder();

        // Find the change to the sort order dependant on the $updown parameter.
        if ($updown == 'up') {
            $change = 1;
        } else {
            $change = -1;
        }

        // Get the booking to change sort order with.
        $other_booking = $this->getDoctrine()->getRepository('InfostanderAdminBundle:Booking')
            ->findOneBy(
                array('sortOrder' => $booking_sort_order + $change)
            );

        // If there is a booking to change order with, do it.
        if ($other_booking) {
            // Set the sortOrder of the booking to the sort order of the other booking + the change.
            $booking->setSortOrder($booking_sort_order + $change);

            // Persist the change to the booking.
            $manager = $this->getDoctrine()->getManager();
            $manager->flush();

            // Persist the change to the other booking.
            $other_booking->setSortOrder($booking_sort_order);
            $manager->flush();
        }

        // Redirect to the Booking:index page.
        return $this->redirect($this->generateUrl("infostander_admin_booking"));
    }

    /**
     * Handler for the delete action.
     *
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction($id)
    {
        // Get the booking with $id.
        $booking = $this->getDoctrine()->getRepository('InfostanderAdminBundle:Booking')->findOneById($id);

        // Remove the booking, if it exists
        if ($booking) {
            $manager = $this->getDoctrine()->getManager();
            $manager->remove($booking);
            $manager->flush();
        }

        // Redirect to the Booking:index page.
        return $this->redirect($this->generateUrl("infostander_admin_booking"));
    }
}
