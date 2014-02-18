<?php

namespace Petkanski\Litecoin\MonitoringBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        /* @var $em \Doctrine\ORM\EntityManager */
        $em = $this->get('doctrine.orm.default_entity_manager');

        /* @var $connection \Doctrine\DBAL\Connection */
        $connection = $em->getConnection();

        $query = $connection->prepare('select * from worker_data order by id asc');
        $query->execute();

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
}
