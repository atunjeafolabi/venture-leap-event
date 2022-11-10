<?php declare(strict_types=1);

namespace App\Controller\v1;

use App\Entity\Type;
use App\Repository\TypeRepository;
use App\Transformer\Transformer;
use App\Validator\EventTypeValidator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;


class EventTypeController extends AbstractController
{
    /**
     * @var TypeRepository
     */
    private $typeRepository;

    private $validator;

    public function __construct(TypeRepository $typeRepository, EventTypeValidator $validator)
    {
        $this->typeRepository = $typeRepository;
        $this->validator = $validator;
    }

    /**
     * Get all types
     *
     * @Route("/types", name="get_all_event_types", methods={"GET"})
     *
     */
    public function index()
    {
        $eventTypes = $this->typeRepository->findAll();

        return $this->json(
            (new Transformer())->transformCollection($eventTypes),
            Response::HTTP_CREATED
        );
    }

    /**
     * Create an event type
     *
     * @Route("/types", name="create_event_type", methods={"POST"})
     * @param   Request  $request
     *
     *
     * @return JsonResponse
     */
    public function create(Request $request): JsonResponse
    {
        $this->validator->validate($request->get('type'));

        if ($this->validator->hasErrors()) {
            return $this->json($this->validator->getErrors(), Response::HTTP_BAD_REQUEST);
        }

        $type = new Type();
        $type->setName($request->get('type'));

        $this->typeRepository->add($type);

        return $this->json([], Response::HTTP_CREATED, ["Location" => "/types/" . $type->getId()]);
    }
}
