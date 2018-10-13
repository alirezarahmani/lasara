<?php
declare(strict_types = 1);

namespace AppBundle\Infrastructure\Persistence\Doctrine;

use AppBundle\Model\Product;
use AppBundle\Model\ProductRepositoryInterface;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping;

class ProductRepository extends EntityRepository implements ProductRepositoryInterface
{
    public function __construct(EntityManager $em, string $class)
    {
        parent::__construct($em, new Mapping\ClassMetadata($class));
    }

    public function store(Product $product)
    {
        $this->_em->persist($product);
        $this->_em->flush($product);
    }

    public function searchByDateCurrency(array $params)
    {
        $qb = $this->createQueryBuilder('p');
        if (isset($params['from'])) {
            $qb->andWhere(':createdAt >= :from')
                ->setParameter('to', $params['from']);
        }
        if (isset($params['to'])) {
            $qb->andWhere(':createdAt <= :to')
                ->setParameter('to', $params['to']);
        }
        return $qb->getQuery()->getResult();
    }
}