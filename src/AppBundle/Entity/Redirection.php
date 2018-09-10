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
     * @ORM\Column(name="old", type="string", length=255)
     */
    private $old;

    /**
     * @var string
     *
     * @ORM\Column(name="new", type="string", length=255)
     */
    private $new;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Posts", inversedBy="redirections")
     * @ORM\JoinColumn(name="post_id", referencedColumnName="id", onDelete="CASCADE", nullable=true)
     **/
    private $post;

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

    /**
     * Set post
     *
     * @param \AppBundle\Entity\Posts $post
     *
     * @return Redirection
     */
    public function setPost(\AppBundle\Entity\Posts $post = null)
    {
        $this->post = $post;

        return $this;
    }

    /**
     * Get post
     *
     * @return \AppBundle\Entity\Posts
     */
    public function getPost()
    {
        return $this->post;
    }
}
