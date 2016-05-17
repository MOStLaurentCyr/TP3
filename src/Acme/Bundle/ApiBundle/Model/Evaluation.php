<?php
/**
 * Created by PhpStorm.
 * User: Mark Olivier
 * Date: 17-05-16
 * Time: 17:59
 */

namespace Acme\Bundle\ApiBundle\Model;


class Evaluation
{

    private $movieId;
    private $repository;

    public function Evaluation($movieId, $repository)
    {
        $this->movieId = $movieId;
        $this->repository = $repository;
    }


    public function getAverageEvaluation()
    {
        $total = 0;
        $evaluations = $this->repository->findBy(array($this->movieId => movieId));
        foreach ($evaluations as $evaluation)
        {
            $total = $evaluation.note + $total;
        }

        return $total / $evaluations.count();
    }

}