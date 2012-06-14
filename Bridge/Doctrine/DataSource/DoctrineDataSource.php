<?php

namespace Snappminds\Utils\Bridge\Doctrine\DataSource;

use Snappminds\Utils\Bundle\WidgetsBundle\DataSource\IDataSource;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;
use DoctrineExtensions\Paginate\Paginate;

class DoctrineDataSource implements IDataSource
{
    private $em;
    private $repositoryName;
    private $criteria = array();
           

    public function __construct(EntityManager $em, $repositoryName = '')
    {
        $this->setEm($em);
        $this->setRepositoryName($repositoryName);
    }

    protected function setEm(EntityManager $value)
    {
        $this->em = $value;
    }

    protected function getEm()
    {
        return $this->em;
    }

    protected function setRepositoryName($value)
    {
        $this->repositoryName = $value;
    }

    protected function getRepositoryName()
    {
        return $this->repositoryName;
    }

    protected function getQueryBuilder()
    {
        return $this->getEm()->getRepository($this->getRepositoryName())->createQueryBuilder('e');
    }

    protected function getDataQuery($page = 1, $rowsPerPage = null)
    {
        $qb = $this->getQueryBuilder();

        $q = $qb->getQuery();

        if ($rowsPerPage) {
            $count = Paginate::getTotalQueryResults($q);
            $q = Paginate::getPaginateQuery($q, ($page - 1) * $rowsPerPage, $rowsPerPage);
        }               
        
        return $q;
    }

    public function getData($page = 1, $rowsPerPage = null)
    {
        return $this->getDataQuery($page, $rowsPerPage)->getArrayResult();
    }

    public function getCount()
    {
        $qb = $this->getQueryBuilder();

        return Paginate::getTotalQueryResults($qb->getQuery());
    }

    public function setCriteria(array $criteria)
    {
        $this->criteria = $criteria;
    }

    public function getCriteria()
    {
        return $this->criteria;
    }
    
}
