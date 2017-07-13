<?php

namespace Appartoo\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\Common\Collections\ArrayCollection;


/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="Appartoo\UserBundle\Repository\UserRepository")
 */
class User extends BaseUser {

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
    * @ORM\ManyToMany(targetEntity="Appartoo\UserBundle\Entity\User")
    */
    private $friends;

    /**
    *
    *
    * @ORM\Column(name="race", type="string", length=255, nullable=true)
    */
    private $race=null;

    /**
    *
    *
    * @ORM\Column(name="famille", type="string", length=255, nullable=true)
    */
    private $famille=null;

    /**
    *
    *
    * @ORM\Column(name="nourriture", type="string", length=255, nullable=true)
    */
    private $nourriture=null;

    /**
    *
    *
    * @ORM\Column(name="birthday", type="date", nullable=true)
    */
    private $birthday=null;

    /**
     * Add friend
     *
     * @param \Appartoo\UserBundle\Entity\User $friend
     *
     * @return User
     */
    public function addFriend(\Appartoo\UserBundle\Entity\User $friend)
    {
        $this->friends[] = $friend;

        return $this;
    }

    /**
     * Remove friend
     *
     * @param \Appartoo\UserBundle\Entity\User $friend
     */
    public function removeFriend(\Appartoo\UserBundle\Entity\User $friend)
    {
        $this->friends->removeElement($friend);
    }


    public function getFriends()
    {
        return $this->friends;
    }

    /**
     * Set race
     *
     * @param string $race
     *
     * @return User
     */
    public function setRace($race)
    {
        $this->race = $race;

        return $this;
    }

    /**
     * Get race
     *
     * @return string
     */
    public function getRace()
    {
        return $this->race;
    }

    /**
     * Set famille
     *
     * @param string $famille
     *
     * @return User
     */
    public function setFamille($famille)
    {
        $this->famille = $famille;

        return $this;
    }

    /**
     * Get famille
     *
     * @return string
     */
    public function getFamille()
    {
        return $this->famille;
    }

    /**
     * Set nourriture
     *
     * @param string $nourriture
     *
     * @return User
     */
    public function setNourriture($nourriture)
    {
        $this->nourriture = $nourriture;

        return $this;
    }

    /**
     * Get nourriture
     *
     * @return string
     */
    public function getNourriture()
    {
        return $this->nourriture;
    }

    /**
     * Set birthday
     *
     * @param \DateTime $birthday
     *
     * @return User
     */
    public function setBirthday($birthday)
    {
        $this->birthday = $birthday;

        return $this;
    }

    /**
     * Get birthday
     *
     * @return \DateTime
     */
    public function getBirthday()
    {
        return $this->birthday;
    }
}
