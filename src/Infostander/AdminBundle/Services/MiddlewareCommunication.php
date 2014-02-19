<?php

namespace Infostander\AdminBundle\Services;

use Symfony\Component\DependencyInjection\ContainerAware;

class MiddlewareCommunication extends ContainerAware
{
    public function pushChannels()
    {
        // Build default channel array.
        $channel = array(
            'channelID' => '1',
            'channelContent' => array(
                'logo' => '',
            ),
            'groups' => array(
                'infostander',
            ),
        );

        $now = date_timestamp_get(date_create());

        // Get bookings where present time is between the start and end date
        $bookings = $this->container->get('doctrine')->getRepository('InfostanderAdminBundle:Booking')->findBy(array(), array('sortOrder' => 'asc'));

        $helper = $this->container->get('vich_uploader.templating.helper.uploader_helper');

        $slides = array();

        foreach ($bookings as $booking) {
            $start = date_timestamp_get($booking->getStartDate());
            $end =   date_timestamp_get($booking->getEndDate());


            // If the the slide should be shown now, add it to the bookings that should be sent to the middleware
            if ($start <= $now && $now <= $end) {
                // Load slide.
                $slide = $this->container->get('doctrine')
                    ->getRepository('InfostanderAdminBundle:Slide')
                    ->findOneById($booking->getSlideId());

                // Set basic slide information.
                $channelEntry = array(
                    'slideID' => $booking->getSlideId(),
                    'title' => $booking->getTitle(),
                    'layout' => 'infostander',
                );

                // Form absolute path to image.
                $path = $this->container->getParameter('absolute_path_to_server') . $helper->asset($slide, 'image');

                $imgArray = array(
                    'image' => array(
                        $path,
                    ),
                );

                $channelEntry['media'] = $imgArray;
                $slides[] = $channelEntry;
            }
        }

        // Add slide to channel.
        $channel['channelContent']['slides'] = $slides;

        // Encode the channel as JSON data.
        $json = json_encode($channel);

        // Send  post request to middleware (/push/channel).
        $url = $this->container->getParameter("middleware_host") . "/push/channel";
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
}

