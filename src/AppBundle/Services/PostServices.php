<?php
/**
 * Created by PhpStorm.
 * User: salah
 * Date: 18/11/18
 * Time: 10:34
 */

namespace AppBundle\Services;


use AppBundle\Entity\Categories;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\DependencyInjection\Container;

class PostServices
{
    /**
     * @var Container
     */
    private $container;
    /**
     * @var EntityManager
     */
    private $em;

    public function __construct(Container $container, EntityManager $em)
    {
        $this->container = $container;
        $this->em = $em;
    }

    /**
     * @param Categories $category
     * @param int $limit
     * @return array
     */
    public function getPosts(Categories $category, $limit = 5)
    {
        /** @var QueryBuilder $qb */
        $qb = $this->em->createQueryBuilder(); // $em is your entity manager
        $posts = $qb->select("p")
            ->from("AppBundle:Posts", 'p')
            ->where($qb->expr()->isNotNull("p.image"))
            ->andWhere('p.category = :cat')
            ->setParameter('cat', $category)
            ->setMaxResults($limit)
            ->getQuery()->getResult();
        return $posts;
    }
}