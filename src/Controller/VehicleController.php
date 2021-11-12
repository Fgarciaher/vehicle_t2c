<?php

namespace App\Controller;

use App\Entity\Model;
use App\Entity\Store;
use App\Entity\Vehicle;
use DateTime;
use Doctrine\DBAL\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class VehicleController
 * @package App\Controller
 *
 * @Route(path="/api/")
 */
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
                'sale_price' => $vehicle["price"]
            ];
        }

        return new JsonResponse($vehicleData);
    }


    /**
     * @Route("vehicle/{id}", methods="GET")
     */
    public function getById($id): JsonResponse
    {
        $em = $this->getDoctrine()->getManager();
        $vehicle = $em->getRepository(Vehicle::class)->Find($id);

        $vehicleData = [
            'id' => $vehicle->getId(),
            'license' => $vehicle->getLicense(),
            'model_name' => $vehicle->getModel()->getName(),
            'brand_name' => $vehicle->getModel()->getBrand()->getName(),
            'entry_date' => $vehicle->getEntryDate(),
            'cost' => $vehicle->getCost(),
            'store' => ['name' => $vehicle->getStore()->getName(),'id' => $vehicle->getStore()->getId()],
            'is_sold' => $vehicle->getIsSold(),
            'last_sale_date' => $vehicle->getLastSaleDate()
        ];

        return new JsonResponse($vehicleData);
    }


    /**
     * @Route("vehicle", methods="POST")
     * @throws \Exception
     */
    function create(Request $request): JsonResponse {
        $vehicleData = json_decode($request->getContent(),true);

        $em = $this->getDoctrine()->getManager();
        $model = $em->getRepository(Model::class)->Find($vehicleData['model']);
        $store = $em->getRepository(Store::class)->Find($vehicleData['store_id']);

        $vehicleLicense = $vehicleData['license'] ?? null;
        $objDateTime = new DateTime($vehicleData['entry_date']);

        $vehicle = new Vehicle();
        $vehicle->setLicense($vehicleLicense);
        $vehicle->setModel($model);
        $vehicle->setEntryDate($objDateTime);
        $vehicle->setCost($vehicleData['cost']);
        $vehicle->setStore($store);

        $em->persist($vehicle);
        $em->flush();

        return new JsonResponse('Vehicle added successfully.');
    }


    /**
     * @Route("vehicle/{id}", methods="DELETE")
     */
    public function delete($id): JsonResponse
    {
        $em = $this->getDoctrine()->getManager();
        $vehicle = $em->getRepository(Vehicle::class)->Find($id);

        if($vehicle->getIsSold()){
            return new JsonResponse(['response' => 'Vehicle has been sold, cannot be removed.']);
        }

        $em->remove($vehicle);
        $em->flush();

        return new JsonResponse(['response' => 'Vehicle deleted successfully.']);
    }


    /**
     * @Route("vehicle/{id}", methods="PUT")
     * @throws \Exception
     */
    function update($id, Request $request): JsonResponse {
        $vehicleData = json_decode($request->getContent(),true);

        $em = $this->getDoctrine()->getManager();
        $vehicle = $em->getRepository(Vehicle::class)->Find($id);

        if($vehicle->getIsSold()){
            return new JsonResponse(['response' => 'Vehicle has been sold, cannot be updated.']);
        }

        $model = $em->getRepository(Model::class)->Find($vehicleData['model']);
        $store = $em->getRepository(Store::class)->Find($vehicleData['store_id']);

        $vehicleLicense = $vehicleData['license'] ?? null;
        $objDateTime = new DateTime($vehicleData['entry_date']);

        $vehicle->setLicense($vehicleLicense);
        $vehicle->setModel($model);
        $vehicle->setEntryDate($objDateTime);
        $vehicle->setCost($vehicleData['cost']);
        $vehicle->setStore($store);

        $em->persist($vehicle);
        $em->flush();

        return new JsonResponse(['response' => 'Vehicle updated successfully.']);
    }
}
