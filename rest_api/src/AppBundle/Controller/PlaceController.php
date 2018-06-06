<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\ViewHandler;
use FOS\RestBundle\View\View; 
use AppBundle\Entity\Place;
use AppBundle\Form\PlaceType;


class PlaceController extends Controller
{
     /**
     * @Rest\View(serializerGroups={"place","theme"})
     * @Rest\Patch("/places/{id}")
     */
    public function patchPlaceAction($id,Request $request)
    {
       return $this->updatePlace($request,$id,false);    

    }
     /**
     * @Rest\View(serializerGroups={"place","theme"})
     * @Rest\Put("/places/{id}")
     */
    public function putPlaceAction($id,Request $request)
    {
       return $this->updatePlace($request,$id,false);
    
    }

    private function updatePlace($request,$id,$option)
    {
        $place = $this->getDoctrine()
                ->getRepository(Place::class)
                ->find($id); 

        if (empty($place)) {
           // return new JsonResponse(['message' => 'Place not found'], Response::HTTP_NOT_FOUND);
           return View::create(['message' => 'Place not found'], Response::HTTP_NOT_FOUND);

        }

        $form = $this->createForm(PlaceType::class, $place);

        $form->submit($request->request->all(),$option);

        if ($form->isValid()) 
        {
            $em = $this->getDoctrine()->getManager()->flush();
            return $place;
        } 
        else 
        {
            return $form;
        }

    }

     /**
     * @Rest\View(statusCode=Response::HTTP_NO_CONTENT,serializerGroups={"place","theme"})
     * @Rest\Delete("/places/{id}")
     */
    public function removePlaceAction($id,Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $place = $em->getRepository(Place::class)
                    ->find($id);
        

       if (!$place) {
            return;
        }

        foreach ($place->getPrices() as $price) {
            $em->remove($price);
        }
        $em->remove($place);
        $em->flush();

       

    }
    /**
     * @Rest\View(statusCode=Response::HTTP_CREATED,serializerGroups={"place","theme"})
     * @Rest\Post("/places")
     */
    public function postPlacesAction(Request $request)
    {
        $place = new Place();
        $form = $this->createForm(PlaceType::class, $place);

        $form->submit($request->request->all()); // Validation des données

        if ($form->isValid()) 
        {
            $em = $this->getDoctrine()->getManager();
            $em->persist($place);
            $em->flush();
            return $place;
        } 
        else 
        {
            return $form;
        }
    }
     
    /**
     * @Rest\View(serializerGroups={"place","theme"})
     * @GET("/places")
     */
    public function getPlacesAction(Request $request)
    {
              
       
      $places=$this->getDoctrine()->getRepository(Place::class)->findAll();
      
       

        return ($places);

       //return new JsonResponse($formatted);
    }
    /**
     * @Rest\View(serializerGroups={"test"})
     * @GET("/places2")
     */
    public function getPlaces2Action(Request $request)
    {
              
      //header("Access-Control-Allow-Origin: *"); 
      $places=$this->getDoctrine()->getRepository(Place::class)->findAll();
      return ($places);

       //return new JsonResponse($formatted);
    }
    /**
     * @Rest\View(serializerGroups={"place","theme"})
     * @Rest\GET("/places/{id}")
     */
    public function getPlaceAction(Place $place,Request $request)
    {
              
       
     // $place=$this->getDoctrine()->getRepository(Place::class)->find($request->get('id'));
      
       if (empty($place)) {
            return new JsonResponse(['message' => "La place n'existe pas"], Response::HTTP_NOT_FOUND);
        
            
       }
       
       return ($place);
       
    }
}
