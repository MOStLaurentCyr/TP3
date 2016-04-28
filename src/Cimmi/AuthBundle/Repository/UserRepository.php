<?php

namespace Cimmi\AuthBundle\Repository;

use Cimmi\AppBundle\Entity\Cegep;
use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
{
    public function findAllACUser() {
        $queryBuilder = $this->createQueryBuilder('u')
            ->innerJoin('u.cegep', 'c')
            ->where("u.roles LIKE '%ROLE_AC%'");

        return $queryBuilder->getQuery()/*->getResult()*/;
    }

    public function findAllCSAUser() {
        $queryBuilder = $this->createQueryBuilder('u')
            ->innerJoin('u.cegep', 'c')
            ->where("u.roles LIKE '%ROLE_CSA%'");

        return $queryBuilder->getQuery()/*->getResult()*/;
    }

    public function findAllStudentForCegep(Cegep $cegep = null) {
        $queryBuilder = $this->createQueryBuilder('u')
            ->innerJoin('u.cegep', 'c')
            ->leftJoin('u.studentAssignedCSA', 'csa')
            ->where("u.roles LIKE '%ROLE_ETU%'");

        if($cegep != null) {
            $queryBuilder
                ->andWhere("c.id = :cegepId")
                ->setParameter('cegepId', $cegep->getId());
        }

        return $queryBuilder->getQuery()/*->getResult()*/;
    }
}