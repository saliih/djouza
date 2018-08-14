<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Posts
 *
 * @ORM\Table(name="posts")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PostsRepository")
 */
class Posts
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
     * @var \DateTime
     *
     * @ORM\Column(name="dcr", type="datetime")
     */
    private $dcr;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dmj", type="datetime", nullable=true)
     */
    private $dmj;

    /**
     * @var string
     *
     * @ORM\Column(name="body", type="text")
     */
    private $body;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255, unique=false)
     */
    private $title;

    /**
     * @var boolean
     *
     * @ORM\Column(name="status", type="boolean", nullable=true)
     */
    private $status;

    /**
     * @var bool
     *
     * @ORM\Column(name="commentStatus", type="boolean")
     */
    private $commentStatus;

    /**
     * @var bool
     *
     * @ORM\Column(name="nofollow", type="boolean")
     */
    private $nofollow;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="text", unique=false)
     */
    private $slug;
    /**
     * @var string
     *
     * @ORM\Column(name="seo_keywords", type="text", unique=false, nullable=true)
     */
    private $seoKeywords;
    /**
     * @var string
     *
     * @ORM\Column(name="seo_title", type="string", length=255, unique=false, nullable=true)
     */
    private $seoTitle;
    /**
     * @var string
     *
     * @ORM\Column(name="seo_description", type="text", unique=false, nullable=true)
     */
    private $seoDescription;
    /**
     * @var string
     *
     * @ORM\Column(name="preptime", type="string", length=25 , nullable=true)
     */
    private $preptime;
    /**
     * @var string
     *
     * @ORM\Column(name="cooktime", type="string", length=25 , nullable=true)
     */
    private $cooktime;
    /**
     * @var string
     *
     * @ORM\Column(name="totaltime", type="string", length=25 , nullable=true)
     */
    private $totaltime;
    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=255 , nullable=true)
     */
    private $image;

    /**
     * @var int
     *
     * @ORM\Column(name="old_id", type="integer", nullable=true)
     */
    private $oldId;
    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Categories")
     * @ORM\JoinTable(name="post_categories",
     *      joinColumns={@ORM\JoinColumn(name="post_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="categories_id", referencedColumnName="id", unique=false)}
     *      )
     */
    private $categories;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Comments", mappedBy="post", cascade={"persist"})
     */
    private $comments;
    public function __toString()
    {
        return (string)$this->getTitle();
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->comments = new \Doctrine\Common\Collections\ArrayCollection();
        $this->categories = new \Doctrine\Common\Collections\ArrayCollection();
        $this->status = 0;
        $this->commentStatus = true;
        $this->nofollow = false;
    }
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
     * Set dcr
     *
     * @param \DateTime $dcr
     *
     * @return Posts
     */
    public function setDcr($dcr)
    {
        $this->dcr = $dcr;

        return $this;
    }

    /**
     * Get dcr
     *
     * @return \DateTime
     */
    public function getDcr()
    {
        return $this->dcr;
    }

    /**
     * Set dmj
     *
     * @param \DateTime $dmj
     *
     * @return Posts
     */
    public function setDmj($dmj)
    {
        $this->dmj = $dmj;

        return $this;
    }

    /**
     * Get dmj
     *
     * @return \DateTime
     */
    public function getDmj()
    {
        return $this->dmj;
    }

    /**
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param string $body
     */
    public function setBody($body)
    {
        $this->body = $body;
    }
    /**
     * Set title
     *
     * @param string $title
     *
     * @return Posts
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set status
     *
     * @param integer $status
     *
     * @return Posts
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set commentStatus
     *
     * @param boolean $commentStatus
     *
     * @return Posts
     */
    public function setCommentStatus($commentStatus)
    {
        $this->commentStatus = $commentStatus;

        return $this;
    }

    /**
     * Get commentStatus
     *
     * @return bool
     */
    public function getCommentStatus()
    {
        return $this->commentStatus;
    }

    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return Posts
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set oldId
     *
     * @param integer $oldId
     *
     * @return Posts
     */
    public function setOldId($oldId)
    {
        $this->oldId = $oldId;

        return $this;
    }

    /**
     * Get oldId
     *
     * @return integer
     */
    public function getOldId()
    {
        return $this->oldId;
    }

    /**
     * Add category
     *
     * @param \AppBundle\Entity\Categories $category
     *
     * @return Posts
     */
    public function addCategory(\AppBundle\Entity\Categories $category)
    {
        $this->categories[] = $category;

        return $this;
    }

    /**
     * Remove category
     *
     * @param \AppBundle\Entity\Categories $category
     */
    public function removeCategory(\AppBundle\Entity\Categories $category)
    {
        $this->categories->removeElement($category);
    }

    /**
     * Get categories
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * @return string
     */
    public function getSeoKeywords()
    {
        return $this->seoKeywords;
    }

    /**
     * @param string $seoKeywords
     */
    public function setSeoKeywords($seoKeywords)
    {
        $this->seoKeywords = $seoKeywords;
    }

    /**
     * @return string
     */
    public function getSeoTitle()
    {
        return $this->seoTitle;
    }

    /**
     * @param string $seoTitle
     */
    public function setSeoTitle($seoTitle)
    {
        $this->seoTitle = $seoTitle;
    }

    /**
     * @return string
     */
    public function getSeoDescription()
    {
        return $this->seoDescription;
    }

    /**
     * @param string $seoDescription
     */
    public function setSeoDescription($seoDescription)
    {
        $this->seoDescription = $seoDescription;
    }

    /**
     * @return bool
     */
    public function isNofollow()
    {
        return $this->nofollow;
    }

    /**
     * @param bool $nofollow
     */
    public function setNofollow($nofollow)
    {
        $this->nofollow = $nofollow;
    }

    /**
     * @return string
     */
    public function getPreptime()
    {
        return $this->preptime;
    }

    /**
     * @param string $preptime
     */
    public function setPreptime($preptime)
    {
        $this->preptime = $preptime;
    }

    /**
     * @return string
     */
    public function getCooktime()
    {
        return $this->cooktime;
    }

    /**
     * @param string $cooktime
     */
    public function setCooktime($cooktime)
    {
        $this->cooktime = $cooktime;
    }

    /**
     * @return string
     */
    public function getTotaltime()
    {
        return $this->totaltime;
    }

    /**
     * @param string $totaltime
     */
    public function setTotaltime($totaltime)
    {
        $this->totaltime = $totaltime;
    }

    /**
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param string $image
     */
    public function setImage($image)
    {
        $this->image = $image;
    }

    /**
     * Get nofollow
     *
     * @return boolean
     */
    public function getNofollow()
    {
        return $this->nofollow;
    }

    /**
     * Add comment
     *
     * @param \AppBundle\Entity\Comments $comment
     *
     * @return Posts
     */
    public function addComment(\AppBundle\Entity\Comments $comment)
    {
        $this->comments[] = $comment;

        return $this;
    }

    /**
     * Remove comment
     *
     * @param \AppBundle\Entity\Comments $comment
     */
    public function removeComment(\AppBundle\Entity\Comments $comment)
    {
        $this->comments->removeElement($comment);
    }

    /**
     * Get comments
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getComments()
    {
        return $this->comments;
    }
}
