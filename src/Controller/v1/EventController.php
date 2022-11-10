<?php declare(strict_types=1);

namespace App\Controller\v1;

use App\Repository\EventRepository;
use App\Transformer\Transformer;
use App\Validator\EventValidator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EventController extends AbstractController
{
    /**
     * @var EventRepository
     */
    private $eventRepository;
    /**
     * @var Request
     */
    private $request;
    /**
     * @var EventValidator
     */
    private $validator;

    public function __construct(EventRepository $eventRepository, EventValidator $validator)
    {
        $this->eventRepository = $eventRepository;
        $this->validator = $validator;
    }

    /**
     * Get all events
     *
     * @Route("/events", name="get_all_events", methods={"GET"})
     * @param   Request  $request
     *
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $events = $this->eventRepository->findEvents(
            $request->get('type'),
            $request->query->getInt('page', 1)
        );

        return $this->json(
            (new Transformer())->transformPagination($events),
            200
        );
    }

    /**
     * Create an event
     *
     * @Route("/events", name="create_event", methods={"POST"})
     * @param   Request  $request
     *
     *
     * @return JsonResponse
     */
    public function create(Request $request): JsonResponse
    {
        $eventData = [
            'details' => $request->get('details'),
            'type' => $request->get('type'),
        ];

        $this->validator->validate($eventData);

        if ($this->validator->hasErrors()) {
            return $this->json($this->validator->getErrors(), Response::HTTP_BAD_REQUEST);
        }

        $eventId = $this->eventRepository->createEvent($eventData);

        return $this->json([], Response::HTTP_CREATED, ["Location" => "/events/" . $eventId]);
    }
}
