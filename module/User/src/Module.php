<?php
namespace User;

use Zend\Mvc\MvcEvent;
use User\Service\AuthManager;
use Zend\Mvc\Controller\AbstractActionController;
use User\Controller\AuthController;


class Module
{
    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    public function getAutoloaderConfig(){
        return [
            'Zend\Loader\StandardAutoloader'=>[
                'namespaces'=>[
                    __NAMESPACE__ => __DIR__.'/src/'.__NAMESPACE__
                ]
            ]
        ];

    }

    public function onBootstrap(MvcEvent $event){
        $eventManager = $event->getApplication()->getEventManager();
        $shareEventManager = $eventManager->getSharedManager();
        $shareEventManager->attach(AbstractActionController::class, MvcEvent::EVENT_DISPATCH,[$this,'onDispath'],100);
    }
    
    public function onDispath(MvcEvent $event){
        $controllerName = $event->getRouteMatch()->getParam('controller',null);
        $actionName = $event->getRouteMatch()->getParam('action',null);
        $authManager = $event->getApplication()->getServiceManager()->get(AuthManager::class);
        
        if(!$authManager->filterAccess($controllerName,$actionName) ){
            //không có quyền
            $controller = $event->getTarget();
            return $controller->redirect()->toRoute('login');
        }
    }


}


?>