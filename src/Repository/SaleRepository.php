<?php

namespace App\Repository;

use App\Entity\Sale;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\Parameter;
use Doctrine\Persistence\ManagerRegistry;

class SaleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sale::class);
    }

    public function getSalesByStoreId($id){
        return $this->getEntityManager()->createQuery(
            "SELECT sale.price
            FROM App:Sale sale 
            INNER JOIN sale.vehicle vehicle 
            WHERE vehicle.store = :id"
        )->setParameters(new ArrayCollection([
            new Parameter('id', $id)
        ]))->getResult();
    }

    public function getCostsByStoreId($id){
        return $this->getEntityManager()->createQuery(
            "SELECT vehicle.cost
            FROM App:Vehicle vehicle 
            WHERE vehicle.store = :id"
        )->setParameters(new ArrayCollection([
            new Parameter('id', $id)
        ]))->getResult();
    }
}
