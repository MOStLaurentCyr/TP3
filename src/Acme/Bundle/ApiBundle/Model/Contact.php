<?php

namespace Acme\Bundle\ApiBundle\Model;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\ExclusionPolicy;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation\Type;

/**
 * Contact
 *
 * @ORM\Entity
 */
class Contact
{

    /**
     * @var string
     * @Type("string")
     * @Assert\NotBlank()
     * @Groups({"default"})
     */
    protected $name;

    /**
     * @var string
     * @Type("string")
     * @Assert\NotBlank()
     * @Groups({"default"})
     */
    protected $email;


    /**
     * @var string
     * @Type("string")
     * @Assert\NotBlank()
     * @Groups({"default"})
     */
    protected $body;
    

    /**
     * @var string
     * @Type("string")
     * @Assert\NotBlank()
     * @Groups({"default"})
     */
    protected $reason;


    /**
     * Set body
     *
     * @param string $email
     * @return Contact
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }


    /**
     * @param string $reason
     * @return Contact
     */
    public function setReason($reason)
    {
        $this->reason = $reason;

        return $this;
    }

    /**
     * @return string
     */
    public function getReason()
    {
        return $this->reason;
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
     * @param string $name
     * @return Comment
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }


}
