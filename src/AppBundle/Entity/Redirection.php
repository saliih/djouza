<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Redirection
 *
 * @ORM\Table(name="redirection")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\RedirectionRepository")
 */
class Redirection
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="old", type="text", nullable=false)
     */
    private $old;

    /**
     * @var string
     *
     * @ORM\Column(name="new", type="text", nullable=false)
     */
    private $new;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set old
     *
     * @param string $old
     *
     * @return Redirection
     */
    public function setOld($old)
    {
        $this->old = $old;

        return $this;
    }

    /**
     * Get old
     *
     * @return string
     */
    public function getOld()
    {
        return $this->old;
    }

    /**
     * Set new
     *
     * @param string $new
     *
     * @return Redirection
     */
    public function setNew($new)
    {
        $this->new = $new;

        return $this;
    }

    /**
     * Get new
     *
     * @return string
     */
    public function getNew()
    {
        return $this->new;
    }
}
