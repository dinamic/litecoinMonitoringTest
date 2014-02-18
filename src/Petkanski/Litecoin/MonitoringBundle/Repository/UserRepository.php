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
     * @return Statement
     */
    public function findAll()
    {
        $sql =<<<EOF
SELECT * 
    FROM users u
ORDER BY u.id ASC
EOF;
            
            $query = $this->connection->prepare($sql);
            
            $query->execute();
            
            return $query;
    }
}
