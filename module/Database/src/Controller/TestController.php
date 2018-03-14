<?php
namespace Database\Controller;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Where;
use Zend\Db\Sql\Expression;


class TestController extends AbstractActionController{

	public function connectDB(){
		return $adapter = new Adapter([
			'driver'   => 'Pdo_Mysql',
		    'database' => 'furniture_2',
		    'username' => 'root',
		    'password' => '',
		    'charset'  => 'utf8'
		]);
	}

	//1. Liệt kê danh sách sản phẩm gồm có tên Tên sp,Đơn giá, Hình. Sắp xếp giảm theo cột đơn giá

	public function indexAction(){
		$adapter = $this->connectDB();

		$sql    = new Sql($adapter);
		$select = $sql->select();
		$select->from('products');
		$select->columns([
			'name','name_en','price','image'
		]);
		$select->order('image');

		$statement = $sql->prepareStatementForSqlObject($select);
		$results = $statement->execute();

		foreach($results as $products){
			echo "<pre>";
			print_r($results);
			echo "</pre>";
		}
		
		return false;
	}

	// 2. Liệt kê danh sách khách hàng gồm có các thông tin sau: tên khách hàng, giới tính, địa chỉ, điện thoại, danh sách sẽ được sắp tăng dần theo tên khách hàng.

	public function index02Action(){
		$adapter = $this->connectDB();

		$sql = new Sql($adapter);
		$select = $sql->select();
		$select->from('users');
		$select->columns([
			'fullname','gender','phone','address'
		]);

		$select->order('fullname');
		$statement = $sql->prepareStatementForSqlObject($select);
		$results = $statement->execute();

		foreach($results as $products){
			echo "<pre>";
			print_r($results);
			echo "</pre>";
		}
		
		return false;
	}

	// 3. Liệt kê danh sách sản phẩm gồm có: Tên sp, Mô tả, Đơn giá. Chỉ liệt kê các Sản phẩm "Sofa" và giá lớn hơn > 1.000.0000

	public function index03Action(){
		$adapter = $this->connectDB();

		$sql = new Sql($adapter);
		$select = $sql->select();
		$select->from('products');
		$select->columns([
			'name','price','detail'
		]);

		$select->where(function (Where $where){
			$where->like('name','%Sofa%');
		});

		$select->where([
            'price > 1000000'
        ]);

		$statement = $sql->prepareStatementForSqlObject($select);
		$results = $statement->execute();

		foreach($results as $products){
			echo "<pre>";
			print_r($results);
			echo "</pre>";
		}
		
		return false;
	}

	// 4. Cho biết tên sản phẩm, Mô tả, đơn giá của 10 sản phẩm có đơn giá cao nhất.

	public function index04Action(){
		$adapter = $this->connectDB();

		$sql = new Sql($adapter);
		$select = $sql->select();
		$select->from('products');
		$select->columns([
			'name','detail','price'
		]);

		$select->order('price DESC')->limit(10)->offset(0);

		$statement = $sql->prepareStatementForSqlObject($select);
		$results = $statement->execute();

		foreach($results as $products){
			echo "<pre>";
			print_r($results);
			echo "</pre>";
		}
		
		return false;
	}

	// 5. Cho biết đơn giá trung bình của các sp hiện có trong cửa hàng

	
}