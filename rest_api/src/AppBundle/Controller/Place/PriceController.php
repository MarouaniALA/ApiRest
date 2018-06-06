<?php
namespace AppBundle\Controller\Place;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest; // alias pour toutes les annotations
use AppBundle\Entity\Place;
use AppBundle\Entity\Price;

use AppBundle\Form\PriceType;

class PriceController extends Controller
{

    /**
     * @Rest\View(serializerGroups={"price"})
     * @Rest\Get("/places/{id}/prices")
     */
    public function getPricesAction($id,Request $request)
    {
        $place = $this->getDoctrine()
                ->getRepository(Place::class)
                ->find($id); // L'identifiant en tant que paramétre n'est plus nécessaire
        /* @var $place Place */

        if (empty($place)) {
            return $this->placeNotFound();
        }

        return $place->getPrices();
    }

     /**
     * @Rest\View(statusCode=Response::HTTP_CREATED,serializerGroups={"price"})
     * @Rest\Post("/places/{id}/prices")
     */
    public function postPricesAction($id,Request $request)
    {

    $place = $this->getDoctrine()
                ->getRepository(Place::class)
                ->find($id);
        /* @var $place Place */

        if (empty($place)) {
            return $this->placeNotFound();
        }

        $price = new Price();
        $price->setPlace($place); // Ici, le lieu est associé au prix
        $form = $this->createForm(PriceType::class, $price);

        // Le paramétre false dit à Symfony de garder les valeurs dans notre
        // entité si l'utilisateur n'en fournit pas une dans sa requête
        $form->submit($request->request->all());

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($price);
            $em->flush();
            return $price;
        } else {
            return $form;
        }
    }

    private function placeNotFound()
    {
        return \FOS\RestBundle\View\View::create(['message' => 'Place not found'], Response::HTTP_NOT_FOUND);
    }

}