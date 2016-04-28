<?php
namespace Acme\Bundle\ApiBundle\Controller\Rest;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as FOSRest;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

use Cimmi\AuthBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpFoundation\Request;

use JMS\Serializer\SerializationContext;

/**
 * RegisterController
 * @author G. Simard
 * @FOSRest\NamePrefix("api_")
 */
class RegisterController extends FOSRestController
{
   /**
     *
     * @ApiDoc(
     *  description="Register a new user",
     *  resource="/api/auth",
     *  input={
     *     "class"="Acme\Bundle\ApiBundle\Form\UserType",
     *     },
     *     statusCodes={
     *         201="Created successfully",
     *         403="Unauthorized",
     *         410="Validation errors"
     *     }
     * )
     * @FOSRest\View(statusCode=201)
     * @ParamConverter("user", class="Cimmi\AuthBundle\Entity\User", converter="fos_rest.request_body")
     * @Post("/register")
     * @throws AccessDeniedException
     */
    public function postRegisterAction(Request $request)
    {
        $content = $request->getContent();
        if (!empty($content))
        {
            $params = array();
            $params = json_decode($content, true);

            $user = new User();
            $user->setEmail($this->getProp($params, 'email'));
            $user->setPassword($this->getProp($params,'password'));
            $user->setFirstName($this->getProp($params,'firstname'));
            $user->setLastName($this->getProp($params,'lastname'));

            $validator = $this->get('validator');
            $errors = $validator->validate($user);

            if (count($errors) > 0) 
            {
                $data=array(
                    "code"=> 410,
                    "message"=> "Validation Failed",
                    'errors'=>$errors,
                    );

                $view = $this->view($data, 410);
                return $this->handleView($view);
            } 
            else 
            {
                $userManager = $this->container->get('fos_user.user_manager');
                $cuser = $userManager->createUser();
                $cuser->setEnabled(true);
                $cuser->setSuperAdmin(false);
                $cuser->setPlainPassword($user->getPassword());
                $cuser->setFirstName($user->getFirstName());
                $cuser->setLastName($user->getLastName());
                $cuser->setUsername($user->getEmail());
                $cuser->setEmail($user->getEmail());
                $cuser->addRole('ROLE_API');
                $userManager->updateUser($cuser);

                $user->setUsername($user->getEmail());
                $user->setRoles(array('ROLE_API'));

                $view = $this->view($cuser, 201);
                $view->setSerializationContext(SerializationContext::create()->setGroups(array('register')));
                return $this->handleView($view);
            }
        }
    }

    private function getProp($arr, $pName){
        if(isset($arr[$pName]))
        {
            return $arr[$pName];
        }
        else
        {
            return '';
        }
    }

}
