<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VehicleController extends AbstractController
{
    /**
     * @Route("vehicle/getAll", methods="POST")
     */
    public function getAll(Request $request): JsonResponse
    {
        $orderData = json_decode($request->getContent(),true);

        $em = $this->getDoctrine()->getManager();
        $vehicles = $em->getRepository(Vehicle::class)->getVehicles($orderData['field'],$orderData['order']);

        $vehicleData = [];

        foreach ($vehicles as $vehicle){

            if($vehicle["last_sale_date"] != null){
                $last_sale_date = $vehicle["last_sale_date"];
            }else{
                $last_sale_date = null;
            }

            $vehicleData[] = [
                'id' => $vehicle["id"],
                'license' => $vehicle["license"],
                'brand_name' => $vehicle["brand_name"],
                'model_name' => $vehicle["model_name"],
                'entry_date' => $vehicle["entry_date"],
                'cost' => $vehicle["cost"],
                'store_name' => $vehicle["store_name"],
                'last_sale_date' => $last_sale_date,
                'sale_price' => $vehicle["sale_price"]
            ];
        }

        return new JsonResponse($vehicleData);
    }
}
