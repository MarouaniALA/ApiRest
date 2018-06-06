<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Appareil;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\ViewHandler;
use FOS\RestBundle\View\View; 

use AppBundle\Form\AppareilType;

class DefaultController extends Controller
{
      /**
     * @Rest\View(statusCode=Response::HTTP_NO_CONTENT)
     * @Rest\Delete("/appareils/{id}")
     */
    public function removeAppareilAction($id,Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $appareil = $em->getRepository(Appareil::class)
                    ->find($id);
        

       if (!$appareil) {
            return;
        }

       /* foreach ($appareil->getPrices() as $price) {
            $em->remove($price);
        }*/
        $em->remove($appareil);
        $em->flush();

       

    }


     /**
     * @Rest\View()
     * @Rest\Put("/appareils/{id}")
     */
    public function putAppareilAction($id,Request $request)
    {
       return $this->updateAppareil($request,$id,true);
    
    }

    private function updateAppareil($request,$id,$option)
    {
        $appareil = $this->getDoctrine()
                ->getRepository(Appareil::class)
                ->find($id); 

        if (empty($appareil)) {
           // return new JsonResponse(['message' => 'Place not found'], Response::HTTP_NOT_FOUND);
           return View::create(['message' => 'Appareil not found'], Response::HTTP_NOT_FOUND);

        }

        $form = $this->createForm(AppareilType::class, $appareil);

        $form->submit($request->request->all(),$option);

        if ($form->isValid()) 
        {
            $em = $this->getDoctrine()->getManager()->flush();
            return $appareil;
        } 
        else 
        {
            return $form;
        }

    }



    /**
     * @Rest\View()
     * @Rest\GET("/appareils/{id}")
     */
    public function getAppareilAction(Appareil $appareil,Request $request)
    {
              
       
     // $place=$this->getDoctrine()->getRepository(Place::class)->find($request->get('id'));
      
       if (empty($appareil)) {
            return new JsonResponse(['message' => "L'appareil n'existe pas"], Response::HTTP_NOT_FOUND);
        
            
       }
       
       return ($appareil);
       
    }

    /**
     * @Rest\View()
     * @Rest\Get("/appareils")
     */
    public function getAppareilsAction(Request $request)
    {
              
      //header("Access-Control-Allow-Origin: *"); 
      $appareils=$this->getDoctrine()->getRepository(Appareil::class)->findAll();
      return ($appareils);

       //return new JsonResponse($formatted);
    }
    /**
     * @Rest\View(statusCode=Response::HTTP_CREATED)
     * @Rest\Post("/appareils")
     */
    public function postAppareilsAction(Request $request)
    {
        $appareil = new Appareil();
        $form = $this->createForm(AppareilType::class, $appareil);

        $form->submit($request->request->all()); // Validation des données

        if ($form->isValid()) 
        {
            $em = $this->getDoctrine()->getManager();
            $em->persist($appareil);
            $em->flush();
            return $appareil;
        } 
        else 
        {
            return $form;
        }
    }
}
