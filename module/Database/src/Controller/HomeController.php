<?php
namespace Database\Controller;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Where;
use Zend\Db\Sql\Expression;


class HomeController extends AbstractActionController{

	function connectDB(){
		return $adapter = new Adapter([
					'driver'=>'Pdo_Mysql',
					'database'=>'furniture_2',
					'username'=>'root',
					'password'=>'',
					'charset'=>'utf8'
				]);

	}

	//Liệt kê danh sách sản phẩm gồm có tên Tên sp,Đơn giá, Hình. Sắp xếp giảm theo cột đơn giá
	function indexAction(){
		
		$adapter = $this->connectDB();
		
		$sql = new Sql($adapter);
		$select = $sql->select();
		$select->from('products');
		$select->columns([
			'ten'=>'name',
			'dongia'=>'price',
			'mota'=>'detail'
		]);
		$select->order('price DESC');

		$selectString = $sql->buildSqlString($select);
		$results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE);

		foreach($results as $product){
			echo "<pre>";
			print_r($product);
			echo "</pre>";
		}

		return false;
	}


	// Liệt kê danh sách sản phẩm gồm có: Tên sp, Mô tả, Đơn giá. Chỉ liệt kê các Sản phẩm "Sofa" và giá lớn hơn > 1.000.0000

	function index03Action(){
		
		$adapter = $this->connectDB();
		
		$sql = new Sql($adapter);
		$select = $sql->select();
		$select->from('products');
		$select->columns([
			'ten'=>'name',
			'dongia'=>'price',
			'mota'=>'detail'
		]);
		
		$select->where(function (Where $where){
			$where->like('name','%Sofa%');
		});

		 $select->where([
            'price > 1000000'
        ]);

		$select->order('price DESC');

		$selectString = $sql->buildSqlString($select);
		$results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE);

		foreach($results as $cus){
			echo "<pre>";
			print_r($cus);
			echo "</pre>";
		}

		return false;

	}
	// 4. Cho biết tên sản phẩm, Mô tả, đơn giá của 10 sản phẩm có đơn giá cao nhất.

	function index04Action(){
		$adapter = $this->connectDB();
		
		$sql = new Sql($adapter);
		$select = $sql->select();
		$select->from('products');
		$select->columns([
			'ten'=>'name',
			'dongia'=>'price',
			'mota'=>'detail'
		]);
		$select->where(function (Where $where){
			$where->like('name','%Sofa%');
		});

		$select->where([
			'price > 1000000'
		]);


		$select->order('price DESC')->limit(10)->offset(0);

		$selectString = $sql->buildSqlString($select);
		$results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE);

		foreach($results as $cus){
			echo "<pre>";
			print_r($cus);
			echo "</pre>";
		}

		return false;

	}

	// 5. Cho biết đơn giá trung bình của các sp hiện có trong cửa hàng

	function index05Action(){
		$adapter = $this->connectDB();
		
		$sql = new Sql($adapter);
		$select = $sql->select();
		$select->from('products');
		$select->columns([
			'DGTB'=>new Expression('avg(price)')
		]);
	

		$selectString = $sql->buildSqlString($select);
		$results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE);

		echo $selectString;

		foreach($results as $cus){
			echo "<pre>";
			print_r($cus);
			echo "</pre>";
		}

		return false;
	}

	//6. Liệt kê danh sách sản phẩm gồm có tên Tên loại, Tên sp, Mô tả, Đơn giá và sắp xếp Tên loại theo chiều tăng dần.
	
	function index06Action(){
		$adapter = $this->connectDB();
		
		$sql = new Sql($adapter);
		$select = $sql->select();
		$select->from(['p'=>'products']);
		$select->columns(['name','detail','price']);

		$select->join(
			['m'=>'menu'],
			'p.id_type = m.id',
			['name_type'=>'name']
		);

		$select->order('m.name ASC'); 

		$selectString = $sql->buildSqlString($select);
		$results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE);

		echo $selectString;

		foreach($results as $cus){
			echo "<pre>";
			print_r($cus);
			echo "</pre>";
		}

		return false;
	}

	// 7. Thống kê tổng số sản phẩm theo Loại, gồm các thông tin: Tên Loại sản phẩm, tổng số sản phẩm, có sắp tăng theo tổng số sản phẩm

	function index07Action(){
		$adapter = $this->connectDB();
		
		$sql = new Sql($adapter);
		$select = $sql->select();
		$select->from(['m'=>'menu']);
		$select->columns(['name']);

		$select->join(
			['p'=>'products'],
			'p.id_type = m.id',
			['tongSP'=>new Expression('count(p.id)')]
		);

		$select->group('m.name');

		$select->order('m.name ASC'); 

		$selectString = $sql->buildSqlString($select);
		$results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE);

		echo $selectString;

		foreach($results as $cus){
			echo "<pre>";
			print_r($cus);
			echo "</pre>";
		}

		return false;
	}

	// 8. Cho biết đơn giá trung bình/min/max của sản phẩm theo từng Loại sản phẩm.

	function index08Action(){
		$adapter = $this->connectDB();
		
		$sql = new Sql($adapter);
		$select = $sql->select();
		$select->from(['m'=>'menu']);
		$select->columns(['name']);

		$select->join(
			['p'=>'products'],
			'p.id_type = m.id',
			['trungbinh'=>new Expression('avg(p.price)'),
			'lonnhat'=>new Expression('max(p.price)'),
			'nhonhat'=>new Expression('min(p.price)'),
			]
		);

		$select->group('m.name');

		$select->order('m.name ASC'); 

		$selectString = $sql->buildSqlString($select);
		$results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE);

		echo $selectString;

		foreach($results as $cus){
			echo "<pre>";
			print_r($cus);
			echo "</pre>";
		}

		return false;
	}

	// 9. Cho biết tổng giá tiền và số sản phẩm của sản phẩm có đơn giá trong khoảng 1.000.000đ đến 3.000.000đ theo từng loại sản phẩm.

	function index09Action(){
		$adapter = $this->connectDB();
		
		$sql = new Sql($adapter);
		$select = $sql->select();
		$select->from(['m'=>'menu']);
		$select->columns(['name']);

		$select->join(
			['p'=>'products'],
			'p.id_type = m.id',
			['tontien'=>new Expression('sum(p.price)'),
			'tongSP'=>new Expression('count(p.id)')
			]
		);

		$select->where(function(Where $where){

			$where->between(
				'price',
				1000000,
				3000000
			);
		});

		$select->group('m.name');

		$select->order('m.name ASC'); 

		$selectString = $sql->buildSqlString($select);
		$results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE);

		echo $selectString;

		foreach($results as $cus){
			echo "<pre>";
			print_r($cus);
			echo "</pre>";
		}

		return false;
	}

// 10. Cho biết đơn giá trung bình sản phẩm thuộc loại sản phẩm là "Tủ bếp"
	function index10Action(){
		$adapter = $this->connectDB();
		
		$sql = new Sql($adapter);
		$select = $sql->select();
		$select->from(['m'=>'menu']);
		$select->columns(['name']);

		$select->join(
			['p'=>'products'],
			'p.id_type = m.id',
			['trungbinh'=>new Expression('avg(p.price)'),
			
			]
		);

		$select->where('m.name = "Tủ bếp"');
		$select->group('m.name');

		$select->order('m.name ASC'); 

		$selectString = $sql->buildSqlString($select);
		$results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE);

		echo $selectString;

		foreach($results as $cus){
			echo "<pre>";
			print_r($cus);
			echo "</pre>";
		}

		return false;
	}

	//11. Thêm thông tin user mới là thông tin của bạn

	function index11Action(){
		$adapter = $this->connectDB();
		
		$sql = new Sql($adapter);
		
		$insert = $sql->insert('users');

		$insert->values([
			'email'=>'tien@gmail.com',
			'password'=>'123456',
			'fullname'=>'Tien'
		]);


		$stmt = $sql->prepareStatementForSqlObject($insert);
		$result = $stmt->execute();

		print_r($result);
		echo "Inserted";

		return false;
	}

	//12. Thêm hoá đơn mới cho khách hàng có mã số là 16, mua 8 chiếc "Ghế văn phòng JONES"

	function index12Action(){
		$adapter = $this->connectDB();
		
		$sql = new Sql($adapter);
		
		$insert = $sql->insert('users');

		$insert->values([
			'id_customer'=>16,
			'date_order'=>date('Y-m-d',time()),
			'total'=>8*2790000
		]);


		$stmt = $sql->prepareStatementForSqlObject($insert);
		$result = $stmt->execute();

		print_r($result);
		echo "Inserted";

		return false;
	}

	// 13. Xoá sp có đơn giá < 50.000 - liên quan khóa ngoại không xóa dc
	function index13Action(){
		$adapter = $this->connectDB();
		
		$sql = new Sql($adapter);
		
		$delete = $sql->delete('products')
						->where('price<S0000');

		


		$stmt = $sql->prepareStatementForSqlObject($delete);
		$result = $stmt->execute();

		print_r($result);
		echo "Deleted";

		return false;
	}

	function index13bAction(){
		$adapter = $this->connectDB();
		
		$sql = new Sql($adapter);
		
		$update = $sql->update('products')
						->set(['deleted'=>1])
						->where('price<S0000');


		$stmt = $sql->prepareStatementForSqlObject($update);
		$result = $stmt->execute();

		print_r($result);
		echo "Deleted";

		return false;
	}

	// 14. Xoá token và token_date của những đơn hàng đã xác nhận 

	function index14Action(){
		$adapter = $this->connectDB();
		
		$sql = new Sql($adapter);
		
		$update = $sql->update('bills')
						->set([
							'token'=>'',
							'token_date'=>''
						])
						->where('status = 1');


		$stmt = $sql->prepareStatementForSqlObject($update);
		$result = $stmt->execute();

		print_r($result);
		echo "Deleted";

		return false;
	}

	// /15. Cập nhật lại đơn giá khuyến mãi cho sp "Giường ALBANY" biết rằng sp được khuyến mãi 10% 

	function index15Action(){
		$adapter = $this->connectDB();
		
		$sql = new Sql($adapter);
		
		$update = $sql->update('products')
						->set([
							'promotion_price'=>0.9*6990000
						])
						->where('name = "Giuờng ALBANY"');


		$stmt = $sql->prepareStatementForSqlObject($update);
		$result = $stmt->execute();

		print_r($result);
		echo "Update";

		return false;
	}
}