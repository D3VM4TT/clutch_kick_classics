<?php

namespace App\Twig;

use App\Entity\Order\OrderItemUnit;
use Doctrine\ORM\EntityManager;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class EntriesExtension extends AbstractExtension
{

    private EntityManager $entityManager;

    public function __construct(EntityManager $entityManager)
    {

        $this->entityManager = $entityManager;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('totalEntries', [$this, 'getTotalEntries']),
        ];
    }

    public function getTotalEntries()
    {
        $qb = $this->entityManager->getRepository(OrderItemUnit::class)->createQueryBuilder('s');
        $qb->select('count(s.id)');
        $qb->where($qb->expr()->isNotNull('s.entryNumber'));
        return $qb->getQuery()->getSingleScalarResult();
    }

}