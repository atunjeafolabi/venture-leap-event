<?php declare(strict_types=1);

namespace App\Controller\v1;

use App\Repository\EventRepository;
use App\Transformer\Transformer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
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

    public function __construct(EventRepository $eventRepository)
    {
        $this->eventRepository = $eventRepository;
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
        // TODO: validate incoming request parameters

        $eventData = [
            'details' => $request->get('details'),
            'type' => $request->get('type'),
        ];

        $eventId = $this->eventRepository->createEvent($eventData);

        return $this->json([], 201, ["Location" => "/events/" . $eventId]);
    }
}
