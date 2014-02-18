<?php

namespace Petkanski\Litecoin\MonitoringBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class FetchWorkerDataCommand extends ContainerAwareCommand
{
    /**
     *
     * @var \Doctrine\DBAL\Connection
     */
    protected $connection;

    protected $data = array();
    
    protected function configure()
    {
        $this
            ->setName('litecoin_monitoring:pool:fetch')
            ->setDescription('Fetch worker data from pool')
//            ->addArgument('name', InputArgument::OPTIONAL, 'Who do you want to greet?')
//            ->addOption('yell', null, InputOption::VALUE_NONE, 'If set, the task will yell in uppercase letters')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /* @var $em \Doctrine\ORM\EntityManager */
        $em = $this->getContainer()->get('doctrine.orm.default_entity_manager');

        /* @var $connection \Doctrine\DBAL\Connection */
        $connection = $em->getConnection();

        $this->connection = $connection;

        while (true) {
            $this->updateData();
            $this->updateWorkerData();

            $output->writeln(date('H:i:s') . ' - Done.');
            sleep(120);
        }
    }

    protected function updateData()
    {
        $query = $this->connection->prepare('select * from api_key');
        $query->execute();

        foreach ($query->fetchAll() as $row) {
            $this->updateAccountData($row['user_id'], $row['api_key']);
        }
    }

    protected function updateAccountData($userId, $apiKey)
    {
        $url = 'https://www.ltcrabbit.com/index.php';

        $params = array(
            'page' => 'api',
            'api_key' => $apiKey,
            'action' => 'getuserworkers',
        );

        $url = $url .'?'. http_build_query($params);

        $result = file_get_contents($url);
        $result = json_decode($result, true);

        foreach ($result['getuserworkers'] as $worker) {
            $worker['user_id'] = $userId;
            $this->data[] = $worker;
        }
    }

    protected function updateWorkerData()
    {
        $this->connection->beginTransaction();
        
        foreach ($this->data as $worker) {
            $this->connection->insert('worker_data', array(
                'user_id'   => $worker['user_id'],
                'worker_id' => $worker['id'],
                'username'  => $worker['username'],
                'hashrate'  => $worker['hashrate'],
            ));
        }

        $this->data = array();

        $this->connection->commit();
    }
}