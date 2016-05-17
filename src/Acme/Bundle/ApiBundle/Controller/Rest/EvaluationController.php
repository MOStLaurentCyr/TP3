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
use Acme\Bundle\ApiBundle\Entity\Evaluation;

/**
 * RegisterController
 * @author M-O St-Laurent-Cyr
 * @FOSRest\NamePrefix("api_")
 */
class EvaluationController extends FOSRestController
{
    /**
     * @ApiDoc(
     *  resource="/api/Evaluation",
     *  description="To set an evaluation of a movie on a 1 to 5 rank",
     *  input={
     *     "class"="Acme\Bundle\ApiBundle\Form\EvaluationType",
     *     },
     *  statusCodes={
     *      200="Successful",
     *      403="Validation errors"
     *     },
     * )
     * @throws AccessDeniedException
     * @FOSRest\View(statusCode=201)
     * @ParamConverter("contact", class="Acme\Bundle\ApiBundle\Model\Evaluation", converter="fos_rest.request_body")
     * @Post("/Evaluation")
     **/
    private $repository;

    public function EvaluationController($repository)
    {
        $this->repository = $repository;
    }


    public function getAllEvaluationAction($movieId)
    {
        return $this->repository->findBy(array($movieId => $movieId));
    }

    public function showEvaluation($movieId, $userId)
    {
        $evaluations = $this->repository->findBy(
            array($userId => $userId),
            array($movieId => $movieId)
        );

        $printLine = printEvaluation($movieId);
        if ($evaluations == null || $userId != null)
        {
            $printLine = printEvaluate();
        }

        return $printLine;
    }

    private function printEvaluate()
    {
        $scope.asNotEvaluate;
    }

    private function printEvaluation($movieId)
    {
        $evaluation = $this->repository->findBy(array($movieId => $movieId));
        $total = Evaluation.getValue($evaluation);
        return $total;
    }
}

public function Evaluate($evaluation, $movieId, $userId)
{
    $newEvaluation = new Evaluation($evaluation, $movieId, $userId);

    $em = $this->getDoctrine->getManager;

    $em->persist($newEvaluation);
    $em->flush();
}
?>