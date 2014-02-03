<?php
namespace Infostander\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="Screens")
 */
class Screen
{
  /**
   * @ORM\Id
   * @ORM\Column(type="integer")
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  protected $id;

  /**
   * @ORM\Column(type="string", length=255)
   * @Assert\NotBlank()
   */
  protected $title;

  /**
   * @ORM\Column(type="text")
   */
  protected $description;

  /**
   * @ORM\Column(type="integer")
   */
  protected $token;

  public function getId() {
    return $this->id;
  }

  public function getTitle() {
    return $this->title;
  }

  public function setTitle($title) {
    $this->title = $title;
  }

  public function getDescription() {
    return $this->description;
  }

  public function setDescription($description) {
    $this->description = $description;
  }

  public function getToken() {
    return $this->$token;
  }

  public function setToken($token) {
    $this->token = $token;
  }
}