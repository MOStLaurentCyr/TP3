<?php

namespace Acme\Bundle\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

use FOS\RestBundle\Controller\Annotations as FOSRest;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations\Post;

/**
 * AuthenticationController
 * @FOSRest\NamePrefix("api_")
 * @author G Simard <gsimard@cegep-ste-foy.qc.ca>
 */
class AuthenticationController extends Controller
{
	  /**
	   * User login, return a JWT. 
	   * The username parameter must be a valid email.
	   * @ApiDoc(
	   *  description="Login",
     *  resource="/api/auth",
	   *  input={
	   *     "class"="Acme\Bundle\ApiBundle\Form\LoginType",
	   *      "parsers"={
     *              "Nelmio\ApiDocBundle\Parser\FormTypeParser"
     *          }
	   *     },
	   *  statusCodes={
	   *      200="Logged successfully",
	   *      401="Unauthorized",
	   *  }
	   * )
		 *
		 * @Post("/login_check")
		 */
		public function checkAction(){}

}
