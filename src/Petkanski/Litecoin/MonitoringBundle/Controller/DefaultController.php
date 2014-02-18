<?php

namespace Petkanski\Litecoin\MonitoringBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    /**
     *
     * @var \Petkanski\Litecoin\MonitoringBundle\Repository\WorkerDataRepository
     */
    protected $workerDataRepository;
    
    public function indexAction($username = null)
    {
        if (is_string($username)) {
            $query = $this->workerDataRepository->findByUsername($username);
        } else {
            $query = $this->workerDataRepository->findAll();
        }

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
