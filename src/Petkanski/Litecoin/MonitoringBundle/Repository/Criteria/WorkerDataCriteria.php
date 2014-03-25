<?php

namespace Petkanski\Litecoin\MonitoringBundle\Repository\Criteria;

/**
 * Description of WorkerDataCriteria
 *
 * @author nikola.petkanski
 */
class WorkerDataCriteria
{
    protected $hourRange;
    protected $username;
    protected $workers;

    protected $limit;
    protected $offset;

    public function __construct()
    {
        $this->workers = array();
    }

    public function getHourRange()
    {
        return $this->hourRange;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getWorkers()
    {
        return $this->workers;
    }

    public function setHourRange($hourRange)
    {
        $this->hourRange = $hourRange;
        return $this;
    }

    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    public function setWorkers($workers)
    {
        $this->workers = $workers;
        return $this;
    }


}
