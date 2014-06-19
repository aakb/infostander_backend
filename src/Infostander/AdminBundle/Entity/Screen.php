<?php
/**
 * @file
 * This file is a part of the Infostander AdminBundle.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Infostander\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Screen
 *
 * Doctrine entity of a Screen.
 *
 * @ORM\Entity
 * @ORM\Table(name="infostander_screen")
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
     * @ORM\Column(type="text")
     */
    protected $token;

    /**
     * @ORM\Column(type="integer", name="activation_code")
     */
    protected $activationCode;

    /**
     * @ORM\Column(type="json_array", name="groups")
     */
    protected $groups;

    /**
     * @ORM\Column(type="integer", name="heartbeat")
     */
    protected $heartbeat = 0;

    public function getId()
    {
        return $this->id;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getToken()
    {
        return $this->token;
    }

    public function setToken($token)
    {
        $this->token = $token;
    }

    public function getActivationCode()
    {
        return $this->activationCode;
    }

    public function setActivationCode($activationCode)
    {
        $this->activationCode = $activationCode;
    }

    public function getGroups()
    {
        return $this->groups;
    }

    public function setGroups($groups)
    {
        $this->groups = $groups;
    }

    /**
     * Set heartbeat
     *
     * @param integer $heartbeat
     * @return Screen
     */
    public function setHeartbeat($heartbeat)
    {
        $this->heartbeat = $heartbeat;

        return $this;
    }

    /**
     * Get heartbeat
     *
     * @return integer 
     */
    public function getHeartbeat()
    {
        return $this->heartbeat;
    }
}
