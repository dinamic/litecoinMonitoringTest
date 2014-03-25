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

    public function matchCriteria(Criteria\WorkerDataCriteria $criteria)
    {
        $params = array();
        $where = array();

        $sql =<<< EOF
SELECT w.*
FROM worker_data w
INNER JOIN users u ON w.user_id = u.id
EOF;

        if (null !== $criteria->getUsername()) {
            $where[] = 'u.username = :username';
            $params['username'] = $criteria->getUsername();
        }

        if (null !== $criteria->getHourRange()) {
            $where[] = 'w.created_at > NOW() - INTERVAL :hourRange hour';
            $params['hourRange'] = $criteria->getHourRange();
        }

        if (!empty($where)) {
            $sql .= ' WHERE '. implode(' AND ', $where);
        }

        $sql .= ' ORDER BY w.id ASC';

        $statement = $this->connection->prepare($sql);
        $statement->execute($params);

        return $statement;
    }
        
    public function setConnection(Connection $connection)
    {
        $this->connection = $connection;
    }
}
