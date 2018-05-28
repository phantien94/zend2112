<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace News\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use News\Model\NewsTable;
use News\Model\News;

class NewsController extends AbstractActionController
{
	public $table;
    function __construct(NewsTable $table){
         echo "abc";
        $this->table = $table;
    }
	
    function testDBAction(){
        echo $this->table->testConnect();   
    }

    function indexAction()
    {	
        echo "abc";
        $result = $this->table->fetchAll();

        // foreach ($result as $news) {
        //     echo "<pre>";
        //     print_r($news);
        //     echo "</pre>";
        // }
        // return false;

        return new ViewModel([
            'result'=>$result
        ]);

    }

    
}
