<?php

namespace App\Repository;

use App\Entity\Meal;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Component\HttpFoundation\Request;

class MealRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Meal::class);
    }

    public function findAllMeals(int $page = 1, Request $request): Pagerfanta
    {
        $qb = $this->createQueryBuilder('m')
            ->select('m, u')
            ->join('m.user', 'u')
        ;
        $this->addFilterConditionsToQB($qb, $request);

        return $this->createPaginator($qb->getQuery(), $page);
    }

    //TODO duplicates $qb
    public function findAllMealsByUser(int $page = 1, Request $request, User $user): Pagerfanta
    {
        $qb = $this->createQueryBuilder('m')
            ->select('m, u')
            ->join('m.user', 'u')
            ->where('u = :user')
            ->setParameter('user', $user)
        ;
        $this->addFilterConditionsToQB($qb, $request);

        return $this->createPaginator($qb->getQuery(), $page);
    }

    public function getUserTotalDailyCalories(User $user): int
    {
        $result = $this->createQueryBuilder('m')
            ->select('sum(m.caloriesNumber)')
            ->join('m.user', 'u')
            ->where('u = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getSingleScalarResult()
        ;

        return $result;
    }

    //TODO need more extendable decision for filters (maybe from box)
    private function addFilterConditionsToQB(QueryBuilder $qb, Request $request): QueryBuilder
    {
        $byDate = ($request->query->get('byDate') ?? '');
        if (!empty($byDate)) {
            $qb->andWhere('m.createdAt > :byDateFrom')
                ->andWhere('m.createdAt < :byDateTo')
                ->setParameters([
                    'byDateFrom' => new \DateTime($byDate . '00:00:00'),
                    'byDateTo' => new \DateTime($byDate . '23:59:59'),
                ]);
        }

        $sortByDate = ($request->query->get('sortByDate') ?? 'desc');
        if ($sortByDate == 'desc') {
            $qb->orderBy('m.createdAt', 'desc');
        } else {
            $qb->orderBy('m.createdAt', 'asc');
        }

        return $qb;
    }

    private function createPaginator(Query $query, int $page): Pagerfanta
    {
        $paginator = new Pagerfanta(new DoctrineORMAdapter($query));
        $paginator->setMaxPerPage(Meal::NUM_ITEMS);
        $paginator->setCurrentPage($page);

        return $paginator;
    }
}
