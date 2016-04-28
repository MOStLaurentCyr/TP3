<?php

namespace Cimmi\AuthBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;

use Doctrine\ORM\Mapping as ORM;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="Cimmi\AuthBundle\Repository\UserRepository")
 * @ORM\Table(name="`user`")
 *
 */
class User extends BaseUser
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     * @Assert\Regex(pattern="/\d{10}/", message="entity.user.invalid.phone_number")
     *
     * @var integer
     */
    private $phoneNumber;

    /**
     * @ORM\Column(type="string", length=125)
     * @Assert\NotBlank(message="field.required")
     *
     * @var string
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=125)
     * @Assert\NotBlank(message="field.required")
     *
     * @var string
     */
    private $lastName;


    /**
     * Set firstName
     *
     * @param string $firstName
     *
     * @return User
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * Set firstName
     *
     * @param string $firstName
     *
     * @return User
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     *
     * @return User
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }


    /**
     * Set phoneNumber
     *
     * @param integer $phoneNumber
     *
     * @return User
     */
    public function setPhoneNumber($phoneNumber)
    {
        $this->phoneNumber = preg_replace("/[^0-9]/","", $phoneNumber);

        return $this;
    }

    /**
     * Get phoneNumber
     *
     * @return integer
     */
    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    /**
     * Set email address
     *
     * @return User
     */
    public function setEmail($email)
    {
        $this->username = $email;

        return parent::setEmail($email);
    }

    /**
     * Set email canonical address
     *
     * @return User
     */
    public function setEmailCanonical($emailCanonical)
    {
        $this->usernameCanonical = $emailCanonical;

        return parent::setEmailCanonical($emailCanonical);
    }

    /**
     * Returns the user roles
     *
     * @return array The roles
     */
    public function getRoles()
    {
        //Remove ROLE_DEFAULT as we are not using it.
        return array_diff(parent::getRoles(), [static::ROLE_DEFAULT]);
    }
}
