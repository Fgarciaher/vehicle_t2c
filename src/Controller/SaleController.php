<?php

namespace App\Controller;

use App\Entity\Sale;
use App\Entity\Vehicle;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class VehicleController
 * @package App\Controller
 *
 * @Route(path="/api/")
 */
class SaleController extends AbstractController
{
    /**
     * @Route("profit", methods="GET")
     */
    public function calculateProfit(): JsonResponse
    {
        $total_sales = $this->calculateSales();
        $total_costs = $this->calculateCosts();
        $profit = round($total_sales - $total_costs,2);

        return new JsonResponse(['profit' => $profit]);
    }

    /**
     * @Route("profit/{id}", methods="GET")
     */
    public function calculateProfitByStoreId($id): JsonResponse
    {
        $total_sales = $this->calculateSalesByStoreId($id);
        $total_costs = $this->calculateCostsByStoreId($id);
        $profit = round($total_sales - $total_costs,2);

        return new JsonResponse(['profit' => $profit]);
    }


    public function calculateSales(): float
    {
        $em = $this->getDoctrine()->getManager();
        $sales = $em->getRepository(Sale::class)->findAll();

        $total_sales = 0;

        foreach ($sales as $sale){
            $total_sales += $sale->getPrice();
        }

        return $total_sales;
    }

    public function calculateSalesByStoreId($id): float
    {
        $em = $this->getDoctrine()->getManager();
        $sales = $em->getRepository(Sale::class)->getSalesByStoreId($id);

        $total_sales = 0;

        foreach ($sales as $sale){
            $total_sales += $sale["price"];

        }

        return $total_sales;
    }

    public function calculateCosts(): float
    {
        $em = $this->getDoctrine()->getManager();
        $costs = $em->getRepository(Vehicle::class)->findAll();

        $total_costs = 0;

        foreach ($costs as $cost){
            $total_costs += $cost->getCost();
        }
        return $total_costs;
    }


    public function calculateCostsByStoreId($id): float
    {
        $em = $this->getDoctrine()->getManager();
        $costs = $em->getRepository(Sale::class)->getCostsByStoreId($id);

        $total_costs = 0;

        foreach ($costs as $cost){
            $total_costs += $cost["cost"];
        }

        return $total_costs;
    }
}
