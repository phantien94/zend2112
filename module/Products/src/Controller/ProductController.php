<?php
namespace Products\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Products\Model\ProductsTable;
use Zend\Paginator\Paginator;
use Zend\Paginator\Adapter\ArrayAdapter;
use Products\Form\ProductForm;
use Zend\Filter\File\Rename;
use Products\Model\Products;

class ProductController extends AbstractActionController{

	public $table;
	function __construct(ProductsTable $table){
		$this->table = $table;
	}

	function testDBAction(){
		// echo $this->table->testConnect();
		$result = $this->table->getAllProducts();

		foreach ($result as $product) {
			echo "<pre>";
			print_r($product);
			echo "</pre>";
		}
		return false;	
	}

	function indexAction(){
		 //    $products = $this->table->getAllProducts();
        $page = $this->params()->fromRoute('page');
        
        $products = $this->table->fetchAll();
        $arrProducts = [];
        foreach($products as $p){
            $arrProducts[] = $p; 
        }
        $paginator = new Paginator(new ArrayAdapter($arrProducts));
        $paginator->setCurrentPageNumber($page);
        $paginator->setItemCountPerPage(5);
        $paginator->setPageRange(3);
        return new ViewModel(['products'=>$paginator]);

	}

	function addAction(){
		$form = new ProductForm();

		//Lấy loại cho product
		$types = $this->table->getAllType();
		$arrType = [];
		foreach($types as $type){
			$arrType[$type['id']] = $type['name'];
		}
		// print_r($arrType);
		// return false;
		$form->get('id_type')->setValueOptions($arrType); //Dùng setValueOption để gán loại sp cho add.phtml

		$request = $this->getRequest();

		if($request->isGet()){
			return new ViewModel(['form'=>$form]);
		}

		//Lưu vào database
		$data = $request->getPost()->toArray();
		$file = $request->getFiles()->toArray();

		$data = array_merge($data,$file);
			
		$form->setData($data);

		if(!$form->isValid()){
			return new ViewModel(['form'=>$form]);
		}

		//Upload file hình
		$arrImage = [];
		foreach($data['image'] as $image){
			$newName = time().'-'.$image['name'];
			$arrImage[] = $newName;
			//Doi ten file
			$rename = new Rename([
                    'target'=>FILE_PATH.'images/'.$newName,
                    'overwrite'=>true
                ]);
			//Upload file hình
			$result = $rename->filter($image);
		}

		$jsonImage = json_encode($arrImage);
		$data['image'] = $jsonImage;

		//Lưu update date
		$data['update_at'] = date('Y-m-d',time());

		// idUrl
		//$url = $this->table->saveUrl(['tien-tien']);
		///////////////////////////////////////
		$data['id_url'] = 1;

		$product = new Products;
		$product->exchangeArray($data);
		$this->table->saveProduct($product);

		return $this->redirect()->toRoute('products',[
			'controller'=>'product',
			'action'=>'index'
		]);
		//save
		// print_r(current($url));
		// return false;
		
	}

	function editAction(){

	}

	function deleteAction(){

	}
}