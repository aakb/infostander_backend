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

require_once dirname(__FILE__).('/../Services/ScheduleToPDF/pdfGenerator.php');
require_once dirname(__FILE__).('/../Services/ScheduleToPDF/pdfWrapper.php');

/**
 * Class PDFCreatorController
 *
 * Controller for bookings.
 *
 * @package Infostander\AdminBundle\Controller
 */
class PDFCreatorController extends Controller
{
    public function generateAction()
    {
        if (get_magic_quotes_gpc()) {
            $xmlString = stripslashes($_POST['mycoolxmlbody']);
        } else {
            $xmlString = $_POST['mycoolxmlbody'];
        }

        $xmlString = urldecode($xmlString);
        $xml = new \SimpleXMLElement($xmlString, LIBXML_NOCDATA);
        $scPDF = new \schedulerPDF();
        $scPDF->printScheduler($xml);

        // Redirect to the Booking:index page.
        return $this->redirect($this->generateUrl("infostander_admin_booking"));
    }
}