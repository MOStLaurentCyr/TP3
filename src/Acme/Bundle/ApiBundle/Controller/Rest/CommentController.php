<?php

namespace Acme\Bundle\ApiBundle\Controller\Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as FOSRest;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\RequestParam;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Request\ParamFetcher;

use JMS\Serializer\SerializationContext;

use Acme\Bundle\ApiBundle\Entity\Comment;

/**
 * CommentController 
 * @author G. Simard
 * 
   $this->getUser()->getId();
   $this->getUser()->getUsername();
   $this->getUser()->getRoles();
 * @Security("has_role('ROLE_API')")
 * @FOSRest\NamePrefix("api_")
 */
class CommentController extends FOSRestController
{
    /**
     * @throws AccessDeniedException
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Get all comments",
     *  statusCodes={
     *      200="Successful",
     *      401="Unauthorized",
     *     },
     * )
     *
     * @QueryParam(name="movie_id", default="", description="Movie related to the comment.")
     * @Security("has_role('ROLE_API')")
     * @FOSRest\View(serializerGroups={"comment"})
     * @return array
     *
     */
    public function getCommentsAction(ParamFetcher $paramFetcher)
    {
        $movieId = $paramFetcher->get('movie_id');

        $em = $this->getDoctrine()->getManager();
        
        if(strlen($movieId) == 0)
        {
            $entities = $em->getRepository('AcmeApiBundle:Comment')->findAllComments(); 
        }
        else
        {
            $entities = $em->getRepository('AcmeApiBundle:Comment')->findBy(
                array('movieId'=>$movieId)); 
        }

        return $entities;
    }


    /**
     * @param int $id
     * 
     * @ApiDoc(
     *  description="Get one comment",
     *  authentication=true,
     *  authenticationRoles={"ROLE_API"},
     *  output= {
     *     "class"="Acme\Bundle\ApiBundle\Entity\Comment",
     *     "groups"={"comment"},
     *     "parsers"={"Nelmio\ApiDocBundle\Parser\JmsMetadataParser"},
     *  },
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
     * @return Comment
     *
     * @FOSRest\View(serializerGroups={"comment"})
     */
    public function getCommentAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $comment = $em->getRepository('AcmeApiBundle:Comment')->find($id);

        if ($comment == null) 
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
            return $comment;
        }
    }

    /**
     * @throws AccessDeniedException
     * @ApiDoc(
     *  description="Create one comment",
     *  authentication=true,
     *  authenticationRoles={"ROLE_API"},
     *  input={
     *      "class"="Acme\Bundle\ApiBundle\Entity\Comment",
     *       "groups"={"create"},
     *      "parsers"={"Nelmio\ApiDocBundle\Parser\JmsMetadataParser"},
     *     },
     *  output= {
     *     "class"="Acme\Bundle\ApiBundle\Entity\Comment",
     *     "groups"={"created"},
     *     "parsers"={"Nelmio\ApiDocBundle\Parser\JmsMetadataParser"},
     *  },
     *  statusCodes={
     *     201="Created successfully",
     *     401="Unauthorized",
     *     403="Validation errors"
     *  }
     * )
     * @FOSRest\View(statusCode=201, serializerGroups={"created"})
     * @ParamConverter("comment", class="Acme\Bundle\ApiBundle\Entity\Comment", converter="fos_rest.request_body")
     * @Post("/comments")
     */
    public function postCommentAction(Request $request, Comment $comment)
    {
        $comment->setUser($this->getUser());
        $comment->setDateCreated(new \DateTime());
        $comment->setStatus(1);

        $validator = $this->get('validator');
        $errors = $validator->validate($comment);

        if (count($errors) > 0) 
        {
            $view = $this->view($errors, 403);
            return $this->handleView($view);
        } 
        else 
        {
            $em = $this->getDoctrine()->getManager();
            $em->persist($comment);
            $em->flush();
            return $comment;
        }
    }

    /**
     * @throws AccessDeniedException
     * @ApiDoc(
     *  description="Update one comment",
     *  authentication=true,
     *  authenticationRoles={"ROLE_API"},
     *  input={
     *      "class"="Acme\Bundle\ApiBundle\Entity\Comment",
     *      "groups"={"create"},
     *      "parsers"={"Nelmio\ApiDocBundle\Parser\JmsMetadataParser"},
     *     },
     *   output= {
     *     "class"="Acme\Bundle\ApiBundle\Entity\Comment",
     *     "groups"={"created"},
     *     "parsers"={"Nelmio\ApiDocBundle\Parser\JmsMetadataParser"},
     *   },
     *   statusCodes={
     *     201="Created successfully",
     *     401="Unauthorized",
     *     403="Validation errors"
     *   }
     * )
     * @ParamConverter("comment", class="Acme\Bundle\ApiBundle\Entity\Comment", converter="fos_rest.request_body")
     * @FOSRest\View(serializerGroups={"comment"})
     */
    public function putCommentAction($id, Comment $comment)
    {
        $comment->setDateCreated(new \DateTime());

        $validator = $this->get('validator');
        $errors = $validator->validate($comment);

        if (count($errors) > 0) 
        {
            $view = $this->view($errors, 403);
            return $this->handleView($view);
        } 
        else 
        {
            $em = $this->getDoctrine()->getManager();
            $currentComment = $em->getRepository('AcmeApiBundle:Comment')->find($id);

            if ($this->getUser()->getId() != $currentComment->getUser()->getId()) {
                throw new AccessDeniedException();
            }

            $currentComment->setBody($comment->getBody());
            $currentComment->setStatus($comment->getStatus());
            $currentComment->setUser($this->getUser());
            $currentComment->setDateCreated($comment->getDateCreated());

            $em->merge($currentComment);
            $em->flush();
            return $currentComment;
        }
    }

    /**
     * @throws AccessDeniedException
     * @ApiDoc(
     *  description="Delete one comment",
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
    public function deleteCommentAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entityToDelete = $em->getRepository('AcmeApiBundle:Comment')->find($id);

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
            if ($this->getUser()->getId() != $entityToDelete->getUser()->getId()) {
                throw new AccessDeniedException();
            }
            $em->remove($entityToDelete);
            $em->flush();
        }
    }

}
