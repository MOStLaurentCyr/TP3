<?php

namespace Acme\Bundle\ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\ExclusionPolicy;
use Symfony\Component\Validator\Constraints as Assert;
use Cimmi\AuthBundle\Entity\User;

/**
 * Comment
 *
 * @ORM\Table()
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="Acme\Bundle\ApiBundle\Entity\CommentRepository")
 * @ExclusionPolicy("all")
 */
class Comment
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
     * @ORM\Column(name="body", type="string", length=255, nullable=false)
     * @Assert\NotBlank()
     * @Groups({"default"})
     */
    protected $body;

    /**
     * @var \DateTime
     * @Assert\NotBlank()
     * @Assert\DateTime()
     * @ORM\Column(name="date_created", type="datetime")
     */
    private $dateCreated;

    /**
     * @ORM\ManyToOne(targetEntity="Cimmi\AuthBundle\Entity\User", inversedBy="comment")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;

    /**
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(name="movie_id", type="string")
     * @Groups({"default"})
     */
    private $movieId;

    /**
     * @var integer
     * @Assert\NotBlank()
     * @Assert\Type(type="Integer")
     * @ORM\Column(name="status", type="integer")
     */
    private $status;

    /**
     * @param string    $body
     * @param \DateTime $date_created
     */
    public function __construct($body = null, \DateTime $dateCreated = null)
    {
        $this->body       = $body;
        $this->dateCreated = $dateCreated;
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
     * Set body
     *
     * @param string $body
     * @return Comment
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
     * Set dateCreated
     *
     * @param \DateTime $dateCreated
     * @return Comment
     */
    public function setDateCreated($dateCreated)
    {
        $this->dateCreated = $dateCreated;

        return $this;
    }

    /**
     * Get dateCreated
     *
     * @return \DateTime
     */
    public function getDateCreated()
    {
        return $this->dateCreated;
    }

    // /**
    //  * Set userId
    //  *
    //  * @param integer $userId
    //  * @return Comment
    //  */
    // public function setUserId($userId)
    // {
    //     $this->userId = $userId;

    //     return $this;
    // }

    // /**
    //  * Get userId
    //  *
    //  * @return integer
    //  */
    // public function getUserId()
    // {
    //     return $this->userId;
    // }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     * @return Answer
     */
    public function setUser(User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }


    /**
     * Set movieId
     *
     * @param integer $movieId
     * @return Comment
     */
    public function setMovieId($movieId)
    {
        $this->movieId = $movieId;
        return $this;
    }

    /**
     * Get movieId
     *
     * @return integer
     */
    public function getMovieId()
    {
        return $this->movieId;
    }


    /**
     * Set status
     *
     * @param integer $status
     * @return Comment
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * Get status
     *
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }
}
