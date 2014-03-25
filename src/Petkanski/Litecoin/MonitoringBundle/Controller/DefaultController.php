<?php

namespace Petkanski\Litecoin\MonitoringBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Petkanski\Litecoin\MonitoringBundle\Repository\Criteria\WorkerDataCriteria;

class DefaultController extends Controller
{
    /**
     *
     * @var \Petkanski\Litecoin\MonitoringBundle\Repository\WorkerDataRepository
     */
    protected $workerDataRepository;
    
    public function indexAction($username = null)
    {
        $criteria = new WorkerDataCriteria();
        
        if (is_string($username)) {
            $criteria->setUsername($username);
        }

        $hourRange = $this->getRequest()->query->get('hours');

        // enforcing default value and min/max value range
        if (null === $hourRange || !ctype_digit($hourRange) || $hourRange > 48 || $hourRange < 1) {
            $hourRange = 24;
        }
        
        $criteria->setHourRange($hourRange);

        $query = $this->workerDataRepository->matchCriteria($criteria);

        $workers = array();
        foreach ($query->fetchAll() as $row) {
            if (!isset($workers[$row['worker_id']])) {
                $workers[$row['worker_id']] = array(
                    'id'        => $row['worker_id'],
                    'username'  => $row['username'],
                    'rand'      => sha1($row['username']),
                    'stats'     => array(),
                );
            }

            $worker = &$workers[$row['worker_id']];
            $worker['stats'][] = array(
                'hashrate'  => $row['hashrate'],
                'time'      => $row['created_at'],
                'timestamp' => strtotime($row['created_at']),
            );
        }

        return $this->render('PetkanskiLitecoinMonitoringBundle:Default:index.html.twig', array(
            'workers' => $workers,
        ));
    }
    
    /**
     * 
     * @param \Petkanski\Litecoin\MonitoringBundle\Repository\WorkerDataRepository $repository
     */
    public function setWorkerDataRepository($repository)
    {
        $this->workerDataRepository = $repository;
    }
}
