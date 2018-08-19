<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Videos
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Videos
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
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=255, nullable=true)
     */
    private $url;

    /**
     * @ORM\ManyToOne(targetEntity="Channel", inversedBy="videos")
     * @ORM\JoinColumn(name="channel_id", referencedColumnName="id", onDelete="CASCADE", nullable=true)
     **/
    private $channel;
    /**
     * @var \Date
     *
     * @ORM\Column(name="created", type="date",nullable=true)
     */
    private $created;
    /**
     * @var string
     *
     * @ORM\Column(name="body", type="text",nullable=true)
     */
    private $body;
    /**
     * @var string
     *
     * @ORM\Column(name="tags", type="text", nullable=true)
     */
    private $tags;

    /**
     * @var string
     *
     * @ORM\Column(name="videoId", type="string", length=255, nullable=true, unique=true)
     */
    private $videosId;

    /**
     * @var boolean
     *
     * @ORM\Column(name="act", type="boolean")
     */
    private $act;
    /**
     * @var boolean
     *
     * @ORM\Column(name="trt", type="boolean")
     */
    private $trt;

    public function __toString()
    {
        return (string)$this->name;
    }

    public function __construct()
    {
        $this->created = new \DateTime();
        $this->act=false;
        $this->trt=false;
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
     * @return Videos
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
     * Set url
     *
     * @param string $url
     * @return Videos
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string 
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Videos
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime 
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set body
     *
     * @param string $body
     * @return Videos
     */
    public function setBody($body)
    {
        $this->body = $body;

        return $this;
    }

    /**
     * Get body
     *
     * @return string 
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Set tags
     *
     * @param string $tags
     * @return Videos
     */
    public function setTags($tags)
    {
        $this->tags = $tags;

        return $this;
    }

    /**
     * Get tags
     *
     * @return string 
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * Set videosId
     *
     * @param string $videosId
     * @return Videos
     */
    public function setVideosId($videosId)
    {
        $this->videosId = $videosId;

        return $this;
    }

    /**
     * Get videosId
     *
     * @return string 
     */
    public function getVideosId()
    {
        return $this->videosId;
    }

    /**
     * Set act
     *
     * @param boolean $act
     * @return Videos
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
     * Set trt
     *
     * @param boolean $trt
     * @return Videos
     */
    public function setTrt($trt)
    {
        $this->trt = $trt;

        return $this;
    }

    /**
     * Get trt
     *
     * @return boolean 
     */
    public function getTrt()
    {
        return $this->trt;
    }

    /**
     * Set channel
     *
     * @param \AppBundle\Entity\Channel $channel
     * @return Videos
     */
    public function setChannel(\AppBundle\Entity\Channel $channel = null)
    {
        $this->channel = $channel;

        return $this;
    }

    /**
     * Get channel
     *
     * @return \AppBundle\Entity\Channel 
     */
    public function getChannel()
    {
        return $this->channel;
    }
}
