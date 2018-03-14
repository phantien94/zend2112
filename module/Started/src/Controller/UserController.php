<?php
namespace Started\Controller;
use Zend\Mvc\Controller\AbstractActionController;
//use Zend\View\Model\ViewModel;
class UserController extends AbstractActionController{
    
    function indexAction(){
        echo "indexAction";
        return false;
    }

     function editAction(){
        echo "editAction";
        echo "11111";
        return false;
    }

     function deleteAction(){
     	echo "Delete action cua User";
        echo "Delete action";
        return false;
    }

    function delete02Action(){
        echo "Delete action 2";
        return false;
    }
}
?>