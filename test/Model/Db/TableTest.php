<?php
namespace MonthlyBasis\LaminasTest;

use MonthlyBasis\Laminas\Model\Db as LaminasDb;
use PHPUnit\Framework\TestCase;

class TableTest extends TestCase
{
    protected function setUp(): void
    {
        $configArray = require($_SERVER['PWD'] . '/config/autoload/local.php');
        $configArray = $configArray['db']['adapters']['test'];
        $adapter = new \Laminas\Db\Adapter\Adapter($configArray);
        $sql = new \Laminas\Db\Sql\Sql($adapter);

        $sqlPath = $_SERVER['PWD'] . '/sql/test/table/drop.sql';
        $sqlString = file_get_contents($sqlPath);
        $adapter->query($sqlString)->execute();

        $sqlPath = $_SERVER['PWD'] . '/sql/test/table/create.sql';
        $sqlString = file_get_contents($sqlPath);
        $adapter->query($sqlString)->execute();

        $this->laminasTable = new LaminasDb\Table($sql);
        $this->laminasTable->setTable('table');
    }

    public function test_insert_values_result()
    {
        $result = $this->laminasTable->insert(
            values: [
                'table_id' => 1,
                'name'     => 'foo',
            ],
        );
        $this->assertSame(
            1,
            $result->getAffectedRows()
        );
    }

    public function test_select_where_result()
    {
        $this->laminasTable->insert(
            values: [
                'name'     => 'foo',
            ],
        );
        $this->laminasTable->insert(
            values: [
                'name'     => 'bar',
            ],
        );

        $result = $this->laminasTable->select(
            where: [
                'name'     => 'bar',
            ],
        );
        $this->assertSame(
            [
                'table_id' => 2,
                'name'     => 'bar',
            ],
            $result->current()
        );
    }
}
