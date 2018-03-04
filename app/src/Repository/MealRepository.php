<?php

namespace App\Repository;

use App\Entity\Meal;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Exception\OutOfRangeCurrentPageException;
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

    public function findAllMealsByUser(int $page = 1, Request $request, User $user): Pagerfanta
    {
        $qb = $this->getMealsWithUsersBaseQB($user)->select('m, u');
        $this->addFilterConditionsToQB($qb, $request);
        return $this->createPaginator($qb->getQuery(), $page);
    }

    public function getUserTotalDailyCalories(User $user): int
    {
        $count = $this->getMealsWithUsersBaseQB($user)
            ->select('sum(m.caloriesNumber)')
            ->andWhere('m.createdAt > :byDateFrom')
            ->setParameter('byDateFrom', (new \DateTime())->format('Y-m-d 00:00:00'))
            ->andWhere('m.createdAt < :byDateTo')
            ->setParameter('byDateTo', (new \DateTime())->format('Y-m-d 23:59:59'))
            ->getQuery()
            ->getSingleScalarResult();

        return (int) $count;
    }

    private function addFilterConditionsToQB(QueryBuilder $qb, Request $request): QueryBuilder
    {
        $createdAtFrom = ($request->query->get('createdAtFrom') ?? '');
        if (!empty($createdAtFrom)) {
            $qb
                ->andWhere('m.createdAt > :byDateFrom')
                ->setParameter('byDateFrom', (new \DateTime($createdAtFrom))->format('Y-m-d 00:00:00'))
            ;
        }

        $createdAtTo = ($request->query->get('createdAtTo') ?? '');
        if (!empty($createdAtTo)) {
            $qb
                ->andWhere('m.createdAt < :byDateTo')
                ->setParameter('byDateTo', (new \DateTime($createdAtTo))->format('Y-m-d 23:59:59'))
            ;
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
        try {
            $paginator->setCurrentPage($page);
        } catch (OutOfRangeCurrentPageException $e) {
            $paginator->setCurrentPage(1);
        }

        return $paginator;
    }

    private function getMealsWithUsersBaseQB(User $user): QueryBuilder
    {
        $qb = $this->createQueryBuilder('m')
            ->join('m.user', 'u')
            ->where('u = :user')
            ->setParameter('user', $user);
        return $qb;
    }
}
