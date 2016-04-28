<?php
namespace Acme\Bundle\ApiBundle\Controller\Rest;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as FOSRest;
use FOS\RestBundle\Controller\Annotations\Post;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use Acme\Bundle\ApiBundle\Model\Contact;

use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpFoundation\Request;

use FOS\RestBundle\Controller\Annotations\View;
use JMS\Serializer\SerializationContext;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

/**
 * RegisterController
 * @author G. Simard
 * @FOSRest\NamePrefix("api_")
 */
class ContactController extends FOSRestController
{
    /**
     * @ApiDoc(
     *  resource="/api/contact",
     *  description="Sends a contact email to movieapi@yopmail.com - see http://yopmail.com",
     *  input={
     *     "class"="Acme\Bundle\ApiBundle\Form\ContactType",
     *     },
     *  statusCodes={
     *      200="Successful",
     *      403="Validation errors"
     *     },
     * )
     * @throws AccessDeniedException
     * @FOSRest\View(statusCode=201)
     * @ParamConverter("contact", class="Acme\Bundle\ApiBundle\Model\Contact", converter="fos_rest.request_body")
     * @Post("/contact")
     */
    public function postContactAction(Request $request, Contact $contact)
    {
        $validator = $this->get('validator');
        $errors = $validator->validate($contact);

        if (count($errors) > 0) 
        {
            $data=array(
                "code"=> 403,
                "message"=> "Validation Failed",
                'errors'=>$errors,
                );

            $view = $this->view($data, 403);
            return $this->handleView($view);
        } 
        else 
        {
            $message = \Swift_Message::newInstance()
                    ->setSubject('Contact :: ' . $contact->getReason())
                    ->setFrom($contact->getEmail())
                    ->setTo('movieapi@yopmail.com')
                    ->setBody($contact->getBody())
                ;

            $this->get('mailer')->send($message);
        }
    }
}
