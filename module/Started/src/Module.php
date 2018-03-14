<?php

namespace Started;

class Module
{

    public function getConfig()
    {	
    	//C:\xampp\htdocs\zend2112\module\Started\src
    	//echo __DIR__;
        return include __DIR__ . '/../config/module.config.php';
    }
}
