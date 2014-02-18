<?php

namespace Petkanski\Litecoin\MonitoringBundle\Menu;

use Symfony\Component\HttpFoundation\Request;
use Knp\Menu\FactoryInterface;
use Petkanski\Litecoin\MonitoringBundle\Repository\UserRepository;

class MenuBuilder
{
    /**
     *
     * @var FactoryInterface
     */
    protected $factory;
    
    /**
     * 
     * @param \Knp\Menu\FactoryInterface $factory
     */
    public function __construct(FactoryInterface $factory)
    {
        $this->factory = $factory;
    }
    
    public function createWorkerByUsernameMenu(Request $request, UserRepository $userRepository)
    {
        $menu = $this->factory->createItem('root');
        $menu->setChildrenAttribute('class', 'nav nav-sidebar');
        $menu->setCurrentUri($request->getUri());
        
        $item = $this->factory->createItem('Overview', array('route' => 'petkanski_litecoin_monitoring_homepage'));
        $item->setCurrent(true);
        
        $menu->addChild($item);
        
        $query = $userRepository->findAll();
        
        foreach ($query->fetchAll() as $user) {
            $menu->addChild($user['username'], array('route' => 'petkanski_litecoin_monitoring_homepage'));
        }
        
        return $menu;
    }
}
