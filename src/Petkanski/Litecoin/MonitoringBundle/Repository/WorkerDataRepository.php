<?php

namespace Petkanski\Litecoin\MonitoringBundle\Repository;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Statement;

class WorkerDataRepository
{
    /**
     *
     * @var Connection
     */
    protected $connection;
    
    /**
     * 
     * @param string $username
     * @return Statement
     */
    public function findByUsername($username)
    {
            $sql =<<<EOF
SELECT * 
    FROM worker_data w
    INNER JOIN users u ON w.user_id = u.id
WHERE
    u.username = :username
ORDER BY w.id ASC
EOF;
            
            $query = $this->connection->prepare($sql);
            $query->bindValue('username', $username);
            
            $query->execute();
            
            return $query;
    }
    
    /**
     * 
     * @return Statement
     */
    public function findAll()
    {
        $sql =<<<EOF
SELECT * 
    FROM worker_data w
ORDER BY w.id ASC
EOF;
            
            $query = $this->connection->prepare($sql);
            
            $query->execute();
            
            return $query;
    }
    
    public function setConnection(Connection $connection)
    {
        $this->connection = $connection;
    }
}
