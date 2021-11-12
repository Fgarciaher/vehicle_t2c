<?php

namespace App\Repository;

use App\Entity\Vehicle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class VehicleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Vehicle::class);
    }

    public function getVehicles($field,$order){
        return $this->getEntityManager()->createQuery(
            'SELECT vehicle.id,vehicle.license,brand.name as brand_name,model.name as model_name,vehicle.entry_date as entry_date,vehicle.cost,store.name as store_name,sale.sale_date as last_sale_date,sale.sale_price 
            FROM App:Vehicle vehicle 
            LEFT JOIN vehicle.sales sale 
            LEFT JOIN vehicle.model model 
            LEFT JOIN model.brand brand 
            LEFT JOIN vehicle.store store  
            ORDER BY '.$field.' '.$order
        )->getResult();
    }
}
