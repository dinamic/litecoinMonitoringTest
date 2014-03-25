<?php

namespace Petkanski\Litecoin\MonitoringBundle\Repository;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Statement;

/**
 * Description of UserRepository
 *
 * @author nikola.petkanski
 */
class UserRepository
{
    /**
     *
     * @var Connection
     */
    protected $connection;
    
    public function setConnection(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     *
     * @param \Petkanski\Litecoin\MonitoringBundle\Repository\Criteria\UserCriteria $criteria
     * @return \Doctrine\DBAL\Portability\Statement
     */
    public function matchCriteria(Criteria\UserCriteria $criteria)
    {
        $params = array();
        $where = array();
        $sql =<<<EOF
SELECT * FROM users u ORDER BY u.id ASC
EOF;

        if (null !== $criteria->getUsername()) {
            $where[] = 'u.username = :username';
            $params['username'] = $criteria->getUsername();
        }

        if (!empty($where)) {
            $sql .= ' WHERE '. implode(' AND ', $where);
        }
        
        $statement = $this->connection->prepare($sql);
        $statement->execute($params);
        
        return $statement;
    }
}
