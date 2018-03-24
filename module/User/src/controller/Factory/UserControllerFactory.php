<?php
namespace User\Controller\Factory;

use User\Controller\UserController;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\InvokableFactory;
use Zend\ServiceManager\ServiceManager;
use Zend\ServiceManager\Factory\FactoryInterface;


class UserControllerFactory implements FactoryInterface{
	public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $entityManager = $container->get(\Doctrine\ORM\EntityManager::class);
        $userManager = $container->get(\User\Service\UserManager::class);
        return new UserManager($entityManager,$userManager);
    }
}

?>