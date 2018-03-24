<?php
namespace User;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\ModuleManager\Feature\ConfigProviderInterface;


class Module
{

    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    function getAutoloaderConfig(){
    	return[
    		'Zend\Loader\StandardAutoloader'=>[
    			'namespace'=>[
    				__NAMESPACE__ => __DIR__.'/src/'.__NAMESPACE__
    			]
    		]
    	];
    }
    
}