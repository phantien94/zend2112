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
			// return $this->tableGateway->select(); //select All
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
	
		
	}