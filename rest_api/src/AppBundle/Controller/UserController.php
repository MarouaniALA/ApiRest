<?php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\User;
use AppBundle\Form\UserType;
//use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\ViewHandler;
use FOS\RestBundle\View\View; 


class UserController extends Controller
{
     
    /**
     * @Rest\View(serializerGroups={"place"})
     * @Rest\Get("/users/{id}/suggestions")
     */
    public function getUserSuggestionsAction(Request $request)
    {
        $user = $this->get('doctrine.orm.entity_manager')
                ->getRepository('AppBundle:User')
                ->find($request->get('id'));
        /* @var $user User */

        if (empty($user)) {
            return $this->userNotFound();
        }

        $suggestions = [];

        $places = $this->get('doctrine.orm.entity_manager')
                ->getRepository('AppBundle:Place')
                ->findAll();

        foreach ($places as $place) {
            if ($user->preferencesMatch($place->getThemes())) {
                $suggestions[] = $place;
            }
        }

        return $suggestions;
    }

    // ...

    private function userNotFound()
    {
        return \FOS\RestBundle\View\View::create(['message' => 'User not found'], Response::HTTP_NOT_FOUND);
    }
    /****************************************/
     /**
     * @Rest\View(serializerGroups={"user"})
     * @Rest\Put("/users/{id}")
     */
    public function putUserAction($id,Request $request)
    {
       return $this->updateUser($request,$id,true);  

    }
    /**
     * @Rest\View(serializerGroups={"user"})
     * @Rest\Patch("/users/{id}")
     */
    public function patchUserAction($id,Request $request)
    {
       return $this->updateUser($request,$id,false);  

    }
    private function updateUser($request,$id, $clearMissing)
    {
      $user = $this->getDoctrine()
                ->getRepository(User::class)
                ->find($id); 

        if (empty($user)) {
            return new JsonResponse(['message' => 'user not found'], Response::HTTP_NOT_FOUND);
        }
        if ($clearMissing) { // Si une mise à jour complète, le mot de passe doit être validé
            $options = ['validation_groups'=>['Default', 'FullUpdate']];
        } else {
            $options = []; // Le groupe de validation par défaut de Symfony est Default
        }
        $form = $this->createForm(UserType::class, $user,$options);

        $form->submit($request->request->all(),$clearMissing);

        if ($form->isValid()) 
        {
            if (!empty($user->getPlainPassword())) {
                $encoder = $this->get('security.password_encoder');
                $encoded = $encoder->encodePassword($user, $user->getPlainPassword());
                $user->setPassword($encoded);
            }
            $em = $this->getDoctrine()->getManager();
            $em->merge($user);
            $em->flush();
            
            return $user;
        } 
        else 
        {
            return $form;
        }
    

    } 
     /**
     * @Rest\View(statusCode=Response::HTTP_NO_CONTENT,serializerGroups={"user"})
     * @Rest\Delete("/users/{id}")
     */
    public function removeUserAction($id,Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)
                    ->find($id);
        
       if($user)
       { 
         $em->remove($user);
         $em->flush();
       } 

    }
    /**
     * @Rest\View(statusCode=Response::HTTP_CREATED,serializerGroups={"user"})
     * @Rest\Post("/users")
     */
    public function postUsersAction(Request $request)
    {
        $user = new User();
        //$form = $this->createForm(UserType::class, $user);
        $form = $this->createForm(UserType::class, $user, ['validation_groups'=>['Default', 'New']]);
        $form->submit($request->request->all()); // Validation des données

        if ($form->isValid()) 
        {
            $em = $this->getDoctrine()->getManager();
            $encoder = $this->get('security.password_encoder');
            // le mot de passe en claire est encodé avant la sauvegarde
            $encoded = $encoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($encoded);
            $em->persist($user);
            $em->flush();
            return $user;
        } 
        else 
        {
            return $form;
        }
    }

    /**
     * @Rest\View(serializerGroups={"user"})
     * @Rest\GET("/users")
     * 
     */
    public function getUsersAction(Request $request)
    {
        $users = $this->getDoctrine()
                ->getRepository(User::class)
                ->findAll();
        /* @var $users User[] */

        

        return $users;
    }
    /**
     * @Rest\View(serializerGroups={"user"})
     * @Rest\GET("/users/{id}")
     * 
     */
    
    public function getUserAction(User $user,Request $request)
    {
        
        if (empty($user)) {
            return new JsonResponse(['message' => "Cet utilisateur n'existe pas"], Response::HTTP_NOT_FOUND);
        }
        
        return ($user);
    }


}
