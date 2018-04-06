<!-- <?php
namespace Database\Controller;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Expression;


class IndexController extends AbstractActionController{

	function setAdapter(){
		return $adapter = new Adapter([
					'driver'=>'Pdo_Mysql',
					'database'=>'zend2112',
					'username'=>'root',
					'password'=>'',
					'charset'=>'utf8'
				]);

	}
	function indexAction(){
		
		$adapter = $this->setAdapter();
		//$sql = "SELECT * FROM User where id=? and name=?";
		//$stmt = $adapter->query($sql);

		$sql = "INSERT INTO User(name,email,password) VALUES(?,?,?)";
		$stmt=$adapter->createStatement($sql,['KPT','kp@gmail.com','123']);

		$result = $stmt->execute();

		print_r($result);

		// foreach ($result as $user){
		// 	echo "<pre>";
		// 	print_r($user);
		// 	echo "</pre>";
		// }
		

		echo "Hello";
		return false;
	}

	function index02Action(){

		$adapter = $this->setAdapter();

		$sql = "SELECT * FROM User";
		$stmt = $adapter->createStatement($sql);
		$result = $stmt->execute();

		if ($result instanceof ResultInterface && $result->isQueryResult()) {
		    $resultSet = new ResultSet;
		    $resultSet->initialize($result);

		    echo $resultSet->getFieldCount();

		    $current = $resultSet->current();

		    echo "<pre>";
		    print_r($current);
		    echo "</pre>";
		    // foreach ($resultSet as $row) {
		    //     echo $row->my_column . PHP_EOL;
		    // }
		}

		return false;

	}

	function index03Action(){
		$adapter = $this->setAdapter();

		$select = new Select();
		$select->from('User');
		$select->where(['id'=>1]);

		$sql = new Sql($adapter);
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		foreach ($result as $user){
			echo "<pre>";
			print_r($user);
			echo "</pre>";
		}
		
		return false;
	}

	function index04Action(){
		$adapter = $this->setAdapter();

		$sql = new Sql($adapter); 


		//select email, name from User as u where u.id=1;

		$select = $sql->select();
		$select->from(['u'=>'User']);
		$select->columns(['email','name']);
		$select->where(['id'=>1]);

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		foreach ($result as $user){
			echo "<pre>";
			print_r($user);
			echo "</pre>";
		}
		
		return false;
	}

	function index05Action(){

		$adapter = $this->setAdapter();

		$sql = new Sql($adapter); 


		//select email, name, title 
		//from User u 
		//INNER JOIN Book b 
		//ON b.user_id = u.id 
		//where u.id=1;

		$select = $sql->select();

		$select->from(['u'=>'User']);
		
		$select->columns(['email','name']); //column of User 

		$select->join(
					['b'=>'Book'],
					'b.user_id = u.id',
					[title], 				//column of table Join (like Book)
					$select::JOIN_INNER
					);
		$select->order([
			'b.title'=>'ASC'
		]);

		$select->limit(1);

		$select->where(['u.id'=>1]);

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		foreach ($result as $user){
			echo "<pre>";
			print_r($user);
			echo "</pre>";
		}
		
		return false;
	}

	/**
		Cho biết tổng số sách viết được của mỗi User
	*/

	function index06Action(){

		$adapter = $this->setAdapter();

		$sql = new Sql($adapter); 

		// $predicate = new PredicateSet;
		$select = $sql->select();

		$select->from(['u'=>'User']);
		
		$select->columns([
					'ten'=>'name',
					'total'=>new Expression("count(b.id)")
				]); //column of User 

		$select->join(
					['b'=>'Book'],
					'b.user_id = u.id',
					[], 				//column of table Join (like Book)
					$select::JOIN_LEFT
					);
		$select->group('name');

		$select->having([ 				//having chi danh cho truong hop Groupby
			'total>0'
		]);

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		foreach ($result as $user){
			echo "<pre>";
			print_r($user);
			echo "</pre>";
		}
		
		return false;
	}

	

} -->