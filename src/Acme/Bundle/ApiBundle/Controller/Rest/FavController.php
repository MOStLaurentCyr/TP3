<?php

namespace Acme\Bundle\ApiBundle\Controller\Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Acme\Bundle\ApiBundle\Entity\Fav;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as FOSRest;
use FOS\RestBundle\Controller\Annotations\NoRoute;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Get;
use Symfony\Component\HttpFoundation\Request;

use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\Controller\Annotations\RequestParam;
use FOS\RestBundle\Controller\Annotations\QueryParam;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
/**
 * FavController
 * @author G. Simard
 * 
 * @Security("has_role('ROLE_API')")
 * @FOSRest\NamePrefix("api_")
 */
class FavController extends FOSRestController
{
    
    /**
     * @throws AccessDeniedException
     *
     * @ApiDoc(
     *  resource=true,
     *  resource="/api/favs",
     *  description="Get all favorites for the user",
     *  tags={"Special route"},
     *  statusCodes={
     *      200="Successful",
     *      401="Unauthorized",
     *     },
     * )
     * @Security("has_role('ROLE_API')")
     * @return array
     *
     */
    public function getFavsMeAction()
    {
   		$userId = $this->getUser()->getId();

        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('AcmeApiBundle:Fav')->findBy(
            array('userId'=>$userId)); 

        $view = $this->view($entities, 200);
        return $this->handleView($view);
    }

    /**
     * @param int $id
     * 
     * @ApiDoc(
     *  description="Get one fav item",
     *  authentication=true,
     *  authenticationRoles={"ROLE_API"},
     *  statusCodes={
     *      200="Successful",
     *      401="Unauthorized",
     *      410={
     *        "Returned when the comment is not found",
     *        "Returned when something else is not found"
     *      }
     *   }
     * )
     *
     * @throws AccessDeniedException
     * @return Fav
     *
     * @FOSRest\View()
     */
    public function getFavAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $fav = $em->getRepository('AcmeApiBundle:Fav')->find($id);

        if ($fav == null) 
        {
            $data=array(
                "code"=> 410,
                "message"=> "Not found",
                );
            $view = $this->view($data, 410);
            return $this->handleView($view);
        } 
        else 
        {
            return $fav;
        }
    }

    /**
     * @throws AccessDeniedException
     * @ApiDoc(
     *  description="Create one fav for the user",
     *  authentication=true,
     *  authenticationRoles={"ROLE_API"},
     *  input={
     *     "class"="Acme\Bundle\ApiBundle\Form\FavType",
     *     },
     *     statusCodes={
     *         201="Created successfully",
     *         401="Unauthorized",
     *         403="Validation errors"
     *     }
     * )
     * @FOSRest\View(statusCode=201)
     * @ParamConverter("fav", class="Acme\Bundle\ApiBundle\Entity\Fav", converter="fos_rest.request_body")
     * @Post("/favs")
     */
    public function postFavAction(Request $request, Fav $fav)
    {
        $fav->setUserId($this->getUser()->getId());
        $fav->setStatus(1);

        $validator = $this->get('validator');
        $errors = $validator->validate($fav);

        if (count($errors) > 0) 
        {
            $view = $this->view($errors, 403);
            return $this->handleView($view);
        } 
        else 
        {
            $em = $this->getDoctrine()->getManager();
            $em->persist($fav);
            $em->flush();
            return $fav;
        }
    }

    /**
     * @throws AccessDeniedException
     * @ApiDoc(
     *  description="Update one fav",
     *  authentication=true,
     *  authenticationRoles={"ROLE_API"},
     *  input={
     *     "class"="Acme\Bundle\ApiBundle\Form\FavType",
     *     },
     *     statusCodes={
     *         201="Created successfully",
     *         401="Unauthorized",
     *         403="Validation errors"
     *     }
     * )
     * @ParamConverter("fav", class="Acme\Bundle\ApiBundle\Entity\Fav", converter="fos_rest.request_body")
     * @FOSRest\View
     */
    public function putFavAction($id, Fav $fav)
    {
        // $fav->setDateCreated(new \DateTime());

        $validator = $this->get('validator');
        $errors = $validator->validate($fav);

        if (count($errors) > 0) 
        {
            $view = $this->view($errors, 403);
            return $this->handleView($view);
        } 
        else 
        {
            $em = $this->getDoctrine()->getManager();
            $currentFav = $em->getRepository('AcmeApiBundle:Fav')->find($id);

            if ($this->getUser()->getId() != $currentFav->getUserId()) {
                throw new AccessDeniedException();
            }

            $currentFav->setStatus($fav->getStatus());
            $currentFav->setUserId($currentFav->getUserId());

            $em->merge($currentFav);
            $em->flush();
            return $currentFav;
        }
    }

    /**
     * @throws AccessDeniedException
     * @ApiDoc(
     *  description="Delete one fav",
     *  authentication=true,
     *  authenticationRoles={"ROLE_API"},
     *  statusCodes={
     *      200="Deleted successfully",
     *      401="Unauthorized",
     *      410="Not found or already deleted"
     *  }
     * )
     * @FOSRest\View(statusCode=200)
     */
    public function deleteFavAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entityToDelete = $em->getRepository('AcmeApiBundle:Fav')->find($id);

        if ($entityToDelete == null) 
        {
            $data=array(
                "code"=> 410,
                "message"=> "Not found",
                );
            $view = $this->view($data, 410);
            return $this->handleView($view);
        } 
        else 
        {
            if ($this->getUser()->getId() != $entityToDelete->getUserId()) {
                throw new AccessDeniedException();
            }
            $em->remove($entityToDelete);
            $em->flush();
        }
    }

}
