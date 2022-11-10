<?php declare(strict_types=1);

namespace App\Controller\v1;

use App\Entity\Type;
use App\Repository\TypeRepository;
use App\Transformer\Transformer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class EventTypeController extends AbstractController
{
    /**
     * @var TypeRepository
     */
    private $typeRepository;

    public function __construct(TypeRepository $typeRepository)
    {
        $this->typeRepository = $typeRepository;
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
            200
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
        // TODO: validate incoming request parameters

        $type = new Type();
        $type->setName($request->get('type'));

        $this->typeRepository->add($type);

        return $this->json([], 201, ["Location" => "/types/" . $type->getId()]);
    }
}
