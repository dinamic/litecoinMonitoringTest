<?php

namespace Petkanski\Litecoin\MonitoringBundle\Repository\Criteria;

/**
 * Description of UserCriteria
 *
 * @author nikola.petkanski
 */
class UserCriteria
{
    protected $username;

    public function getUsername()
    {
        return $this->username;
    }

    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }


}
