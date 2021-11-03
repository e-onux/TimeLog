<?php

namespace App\Repository;

use App\Entity\TimeLog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use DoctrineExtensions\Query\Mysql\Month;
use DoctrineExtensions\Query\Mysql\Year;
use DoctrineExtensions\Query\Mysql\Floor;

/**
 * @method TimeLog|null find($id, $lockMode = null, $lockVersion = null)
 * @method TimeLog|null findOneBy(array $criteria, array $orderBy = null)
 * @method TimeLog[]    findAll()
 * @method TimeLog[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TimeLogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TimeLog::class);
    }

    public function monthlyReport(){
        return    $this
        ->createQueryBuilder('tl')
        ->select(' SUM(tl.duration) as duration,
        FLOOR(SUM(tl.duration)/3600) as d_hours,
        MONTH(tl.work_day) as month, YEAR(tl.work_day) as year')
        ->groupBy('month')
        ->addGroupBy('year')
        ->getQuery()->getResult();
    }

    public function getDailyAverage(){
        $result =    $this
        ->createQueryBuilder('tl')
        ->select(' SUM(tl.duration) as duration')
        ->groupBy('tl.work_day')
        ->getQuery()->getResult();
        $result = array_column($result,"duration");
        return !empty($result) ? ceil(array_sum($result) / count($result)) : 0;
    }
}
