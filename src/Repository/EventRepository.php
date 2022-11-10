<?php declare(strict_types=1);

namespace App\Repository;

use App\Entity\Event;
use App\Entity\Type;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @extends ServiceEntityRepository<Event>
 *
 * @method Event|null find($id, $lockMode = null, $lockVersion = null)
 * @method Event|null findOneBy(array $criteria, array $orderBy = null)
 * @method Event[]    findAll()
 * @method Event[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventRepository extends ServiceEntityRepository
{
    /**
     * @var TypeRepository
     */
    private $typeRepository;
    /**
     * @var PaginatorInterface
     */
    private $paginator;
    /**
     * @var Event
     */
    private $event;
    /**
     * @var Type
     */
    private $type;

    public function __construct(
        TypeRepository $typeRepository,
        ManagerRegistry $registry,
        PaginatorInterface $paginator
    ) {
        parent::__construct($registry, Event::class);
        $this->event = new Event();
        $this->type = new Type();
        $this->typeRepository = $typeRepository;
        $this->paginator = $paginator;
    }

    public function findEvents($typeName, $page=1, $pageLimit = 4)
    {
        if (!empty($typeName)) {
            $type = $this->typeRepository->findOneBy(['name' => $typeName]);
            $query = $this->findByType($type);
        } else {
            $query = $this->createQueryBuilder('e')->getQuery();
        }

        return $this->paginator->paginate($query, $page, $pageLimit);
    }

    public function createEvent($eventData): int
    {
        if ($type = $this->doesTypeAlreadyExist($eventData['type'])) {
            $this->event->setType($type);
        } else {
            $this->type->setName($eventData['type']);
            $this->event->setType($this->type);
        }

        $this->event->setTimestamp();
        $this->event->setDetails($eventData['details']);

        $this->getEntityManager()->persist($this->event);
        $this->getEntityManager()->flush();

        return $this->event->getId();
    }

    public function remove(Event $event, bool $flush = true): void
    {
        $this->getEntityManager()->remove($event);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @param $type
     *
     * @return \Doctrine\ORM\Query
     */
    public function findByType($type)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.type = :val')
            ->setParameter('val', $type)
            ->getQuery();
    }

    /**
     * @param $eventData
     *
     * @return Type|null
     */
    protected function doesTypeAlreadyExist($type)
    {
        return $this->typeRepository->findOneBy(['name' => $type]);
    }
}
