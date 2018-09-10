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
     * @ORM\ManyToOne(targetEntity="Categories", inversedBy="posts")
     * @ORM\JoinColumn(name="cat_id", referencedColumnName="id", onDelete="CASCADE", nullable=true)
     **/
    private $category;
    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Tags")
     * @ORM\JoinTable(name="post_tags",
     *      joinColumns={@ORM\JoinColumn(name="post_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="tags_id", referencedColumnName="id", unique=false)}
     *      )
     */
    private $tags;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Comments", mappedBy="post", cascade={"persist"})
     */
    private $comments;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Redirection", mappedBy="post", cascade={"persist"})
     */
    private $redirections;
    public function __toString()
    {
        return (string)$this->getTitle();
    }

    public function removeAllTags(){
        $this->tags = [];
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->redirections = new \Doctrine\Common\Collections\ArrayCollection();
        $this->tags = new \Doctrine\Common\Collections\ArrayCollection();
        $this->comments = new \Doctrine\Common\Collections\ArrayCollection();
        $this->status = 0;
        $this->commentStatus = true;
        $this->nofollow = false;
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
     * Set body
     *
     * @param string $body
     *
     * @return Posts
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
     * @param boolean $status
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
     * @return boolean
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
     * @return boolean
     */
    public function getCommentStatus()
    {
        return $this->commentStatus;
    }

    /**
     * Set nofollow
     *
     * @param boolean $nofollow
     *
     * @return Posts
     */
    public function setNofollow($nofollow)
    {
        $this->nofollow = $nofollow;

        return $this;
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
     * Set seoKeywords
     *
     * @param string $seoKeywords
     *
     * @return Posts
     */
    public function setSeoKeywords($seoKeywords)
    {
        $this->seoKeywords = $seoKeywords;

        return $this;
    }

    /**
     * Get seoKeywords
     *
     * @return string
     */
    public function getSeoKeywords()
    {
        return $this->seoKeywords;
    }

    /**
     * Set seoTitle
     *
     * @param string $seoTitle
     *
     * @return Posts
     */
    public function setSeoTitle($seoTitle)
    {
        $this->seoTitle = $seoTitle;

        return $this;
    }

    /**
     * Get seoTitle
     *
     * @return string
     */
    public function getSeoTitle()
    {
        return $this->seoTitle;
    }

    /**
     * Set seoDescription
     *
     * @param string $seoDescription
     *
     * @return Posts
     */
    public function setSeoDescription($seoDescription)
    {
        $this->seoDescription = $seoDescription;

        return $this;
    }

    /**
     * Get seoDescription
     *
     * @return string
     */
    public function getSeoDescription()
    {
        return $this->seoDescription;
    }

    /**
     * Set preptime
     *
     * @param string $preptime
     *
     * @return Posts
     */
    public function setPreptime($preptime)
    {
        $this->preptime = $preptime;

        return $this;
    }

    /**
     * Get preptime
     *
     * @return string
     */
    public function getPreptime()
    {
        return $this->preptime;
    }

    /**
     * Set cooktime
     *
     * @param string $cooktime
     *
     * @return Posts
     */
    public function setCooktime($cooktime)
    {
        $this->cooktime = $cooktime;

        return $this;
    }

    /**
     * Get cooktime
     *
     * @return string
     */
    public function getCooktime()
    {
        return $this->cooktime;
    }

    /**
     * Set totaltime
     *
     * @param string $totaltime
     *
     * @return Posts
     */
    public function setTotaltime($totaltime)
    {
        $this->totaltime = $totaltime;

        return $this;
    }

    /**
     * Get totaltime
     *
     * @return string
     */
    public function getTotaltime()
    {
        return $this->totaltime;
    }

    /**
     * Set image
     *
     * @param string $image
     *
     * @return Posts
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
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
     * Set category
     *
     * @param \AppBundle\Entity\Categories $category
     *
     * @return Posts
     */
    public function setCategory(\AppBundle\Entity\Categories $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \AppBundle\Entity\Categories
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Add tag
     *
     * @param \AppBundle\Entity\Tags $tag
     *
     * @return Posts
     */
    public function addTag(\AppBundle\Entity\Tags $tag)
    {
        $this->tags[] = $tag;

        return $this;
    }

    /**
     * Remove tag
     *
     * @param \AppBundle\Entity\Tags $tag
     */
    public function removeTag(\AppBundle\Entity\Tags $tag)
    {
        $this->tags->removeElement($tag);
    }

    /**
     * Get tags
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTags()
    {
        return $this->tags;
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

    /**
     * Add redirection
     *
     * @param \AppBundle\Entity\Redirection $redirection
     *
     * @return Posts
     */
    public function addRedirection(\AppBundle\Entity\Redirection $redirection)
    {
        $this->redirections[] = $redirection;

        return $this;
    }

    /**
     * Remove redirection
     *
     * @param \AppBundle\Entity\Redirection $redirection
     */
    public function removeRedirection(\AppBundle\Entity\Redirection $redirection)
    {
        $this->redirections->removeElement($redirection);
    }

    /**
     * Get redirections
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRedirections()
    {
        return $this->redirections;
    }
}
