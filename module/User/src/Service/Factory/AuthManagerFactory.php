<?php
namespace User\Service\Factory;
use User\Service\AuthManager;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\InvokableFactory;
use Zend\ServiceManager\ServiceManager;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\Authentication\AuthenticationService;
use Zend\Session\SessionManager;

class AuthManagerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $authenticationService = $container->get(AuthenticationService::class);
        $sessionManager = $container->get(SessionManager::class);
        $config = $container->get('Config');
        if(isset($config['access_filter'])){
            $config = $config['access_filter'];
        }
        else $config = [];
        
        return new AuthManager($authenticationService, $sessionManager,$config);
    }
}
?>