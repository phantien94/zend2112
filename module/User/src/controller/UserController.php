<?php
namespace User\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class UserController extends AbstractActionController{

	private $entityManager,$userManager;

	function __construct($entityManager,$userManager){
		$this->entityManager = $entityManager;
		$this->userManager = $userManager;
	}

	function indexAction(){
		echo "Hello";
		return false;
	}
}
?>