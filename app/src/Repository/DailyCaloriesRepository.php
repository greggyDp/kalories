<?php

namespace App\Repository;

use App\Entity\DailyCalories;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class DailyCaloriesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DailyCalories::class);
    }

    public function getOrCreateTodaysRecord(): DailyCalories
    {
        $record = $this->createQueryBuilder('dc')
            ->where('dc.createdAt BETWEEN :from AND :to')
            ->setParameters([
                'from' => (new \DateTime())->format('Y-m-d 00:00:00'),
                'to' => (new \DateTime())->format('Y-m-d 23:59:59'),
            ])
            ->getQuery()
            ->getOneOrNullResult();

        if ( ! $record) {
            $record = (new DailyCalories());
            $this->_em->persist($record);
            $this->_em->flush();
        }

        return $record;
    }
}
