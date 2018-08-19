<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Channel
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Channel
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;
    /**
     * @var boolean
     *
     * @ORM\Column(name="act", type="boolean")
     */
    private $act;

    /**
     * @ORM\OneToMany(targetEntity="Videos", mappedBy="channel", cascade={"persist"})
     */
    private $videos;
    /**
     * @var string
     *
     * @ORM\Column(name="channelId", type="string", length=255)
     */
    private $channelId;

    public function __toString()
    {
        return (string)$this->name;
    }

    public function __construct()
    {
        $this->act= true;
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Channel
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set channelId
     *
     * @param string $channelId
     * @return Channel
     */
    public function setChannelId($channelId)
    {
        $this->channelId = $channelId;

        return $this;
    }

    /**
     * Get channelId
     *
     * @return string 
     */
    public function getChannelId()
    {
        return $this->channelId;
    }

    /**
     * Set act
     *
     * @param boolean $act
     * @return Channel
     */
    public function setAct($act)
    {
        $this->act = $act;

        return $this;
    }

    /**
     * Get act
     *
     * @return boolean 
     */
    public function getAct()
    {
        return $this->act;
    }

    /**
     * Add videos
     *
     * @param \AppBundle\Entity\Videos $videos
     * @return Channel
     */
    public function addVideo(\AppBundle\Entity\Videos $videos)
    {
        $this->videos[] = $videos;

        return $this;
    }

    /**
     * Remove videos
     *
     * @param \AppBundle\Entity\Videos $videos
     */
    public function removeVideo(\AppBundle\Entity\Videos $videos)
    {
        $this->videos->removeElement($videos);
    }

    /**
     * Get videos
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getVideos()
    {
        return $this->videos;
    }
}
