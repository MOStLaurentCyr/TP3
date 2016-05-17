<?php
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;


/**
    * @ORM\Entity
    * @ORM\Table(name="Evaluation")
   */

class Evaluation
{


    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(Type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(TargetEntity="User", invertedBy="Evaluation
     */
    private $user;

    private $movieid;

    public $note;


    public function __construct($user, $note, $movieId)
    {
        $this->user = $user;
        $this->note = $note;
        $this->movieid = $movieId;

        $em = $this->getDoctrine()->getManager();

        $em->persist
    }


    public static function getValue($evaluations)
    {
        $total = 0;
        foreach ($evaluations as $evaluation)
        {
            $total = $total + $evaluation->note;
        }

        return total;
    }



}