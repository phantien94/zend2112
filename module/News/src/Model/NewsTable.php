<?php
namespace News\Model;

use RuntimeException;
use Zend\Db\TableGateway\TableGatewayInterface;
use Zend\Db\Sql\Sql;

class NewsTable {

	private $tableGateway;

	public function __construct(TableGatewayInterface $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    function testConnect(){//get table name
        return $this->tabelGateway->getTable();
    }

    public function fetchAll()
    {
        return $this->tableGateway->select();
    }
}