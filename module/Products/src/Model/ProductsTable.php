<?php
	namespace Products\Model;
	use RuntimeException;
	use Zend\Db\TableGateway\TableGatewayInterface;
	use Zend\Db\Sql\Sql;

	class ProductsTable{

		private $tableGateway;

		function __construct(TableGatewayInterface $tableGateway){

			$this->tableGateway = $tableGateway;
		}

		function testConnect(){ 
			return $this->tableGateway->getTable();
		}

		function getAllProducts(){
			// return $this->tableGateway->select(), //select All
			return $this->tableGateway->select('deleted = 0');
		}

		function fetchAll(){
	 		$adapter = $this->tableGateway->getAdapter();
	        $sql = new Sql($adapter);
	        $select = $sql->select(['p'=>'products']);
	        $select->join(
	            ['m'=>'menu'],
	            'm.id = p.id_type',
	            [
	                'name_type'=>'name'
	            ]
	        );
	        $select->where('deleted = 0');
	        $select->order('name_type ASC');
	        $statement = $sql->prepareStatementForSqlObject($select);
	        return $results = $statement->execute();
		}
		
		//Lấy loại cho product
		function getAllType(){
			$adapter = $this->tableGateway->getAdapter();

			$sql = new Sql($adapter);
			$select = $sql->select('menu')->where('hide<>2');

			$statement = $sql->prepareStatementForSqlObject($select);
	        return $results = $statement->execute();

		}
		
		//Save Url
		function saveUrl($name){
			$adapter = $this->tableGateway->getAdapter();

			$sql = new Sql($adapter);
			$insert = $sql->insert('page_url')
							->values(['url'=>$alias]);

			$statement = $sql->prepareStatementForSqlObject($select);
	        return $results = $statement->execute();				
		}

		// Lưu vào DB
		function saveProduct(Products $data){
			
			$product = [
				'id_type'=>$data->id_type, 
				'id_url'=>$data->id_url, 
				'name'=>$data->name, 
				'summary'=>$data->summary, 
				'detail'=>$data->detail, 
				'price'=>$data->price, 
				'promotion_price'=>$data->promotion_price, 
				'image'=>$data->image, 
				'size'=>$data->size, 
				'material'=>$data->material, 
				'color'=>$data->color, 
				'update_at'=>$data->update_at, 
				'unit'=>$data->unit, 
				'noibat'=>$data->noibat
			];
			$adapter = $this->tableGateway->insert($product);
		}

	}