<?php
namespace Infostander\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="infostander_booking")
 */
class Booking
{
  /**
   * @ORM\Id
   * @ORM\Column(type="integer")
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  protected $id;

  /**
   * @ORM\Column(type="date", name="start_date")
   */
  protected $startDate;

  /**
   * @ORM\Column(type="date", name="end_date")
   */
  protected $endDate;

  /**
   * @ORM\Column(type="integer", name="slide_id")
   */
  protected $slideId;

  public function getId() {
    return $this->id;
  }

  public function getStartDate() {
    return $this->startDate;
  }

  public function setStartDate($startDate) {
    $this->startDate = $startDate;
  }

  public function getEndDate() {
    return $this->startDate;
  }

  public function setEndDate($startDate) {
    $this->startDate = $startDate;
  }

  public function getSlideId() {
    return $this->slideId;
  }

  public function setSlideId($slideId) {
    $this->slideId = $slideId;
  }
}