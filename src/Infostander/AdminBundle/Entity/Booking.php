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
   * @ORM\Column(type="integer", name="slide_id")
   */
  protected $slideId;

  /**
   * @ORM\Column(type="string", length=255)
   */
  protected $title;

  /**
   * @ORM\Column(type="datetime", name="start_date")
   */
  protected $startDate;

  /**
   * @ORM\Column(type="datetime", name="end_date")
   */
  protected $endDate;



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
    return $this->endDate;
  }

  public function setEndDate($endDate) {
    $this->endDate = $endDate;
  }

  public function getTitle() {
    return $this->title;
  }

  public function setTitle($title) {
    $this->title = $title;
  }

  public function getSlideId() {
    return $this->slideId;
  }

  public function setSlideId($slideId) {
    $this->slideId = $slideId;
  }
}