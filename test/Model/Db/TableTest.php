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
        $this->sql = new \Laminas\Db\Sql\Sql($adapter);

        $sqlPath = $_SERVER['PWD'] . '/sql/test/table/drop.sql';
        $sqlString = file_get_contents($sqlPath);
        $adapter->query($sqlString)->execute();

        $sqlPath = $_SERVER['PWD'] . '/sql/test/table/create.sql';
        $sqlString = file_get_contents($sqlPath);
        $adapter->query($sqlString)->execute();

        $this->laminasTable = new LaminasDb\Table();
        $this->laminasTable
            ->setSql($this->sql)
            ->setTable('table')
        ;
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

    public function test_insertIgnore_values_result()
    {
        $result = $this->laminasTable->insertIgnore(
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
        $this->laminasTable->insert(
            values: [
                'name'     => 'baz',
            ],
        );

        $result = $this->laminasTable->select(
            where: [
                'name'     => 'baz',
            ],
        );
        $this->assertSame(
            [
                'table_id' => 3,
                'name'     => 'baz',
            ],
            $result->current()
        );
    }

    public function test_select_order_result()
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
        $this->laminasTable->insert(
            values: [
                'name'     => 'baz',
            ],
        );

        $result = $this->laminasTable->select(
            order: [
                'name ASC',
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

    public function test_settersAndGetters()
    {
        $this->assertSame(
            $this->laminasTable,
            $this->laminasTable->setAdapter($this->sql->getAdapter())
        );
        $this->assertSame(
            $this->sql->getAdapter(),
            $this->laminasTable->getAdapter()
        );

        $this->assertSame(
            $this->laminasTable,
            $this->laminasTable->setSql($this->sql)
        );
        $this->assertSame(
            $this->sql,
            $this->laminasTable->getSql()
        );

        $table = 'table';
        $this->assertSame(
            $this->laminasTable,
            $this->laminasTable->setTable($table)
        );
        $this->assertSame(
            $table,
            $this->laminasTable->getTable()
        );
    }

    public function test_update()
    {
        $result = $this->laminasTable->insert(
            values: [
                'table_id' => 1,
                'name'     => 'foo',
            ],
        );
        $result = $this->laminasTable->insert(
            values: [
                'table_id' => 2,
                'name'     => 'bar',
            ],
        );
        $result = $this->laminasTable->update(
            set: [
                'name'     => 'baz',
            ],
            where: [
                'table_id' => 1,
            ],
        );
        $this->assertSame(
            1,
            $result->getAffectedRows()
        );

        $result = $this->laminasTable->select(
            columns: [
                'name'
            ],
            where: [
                'table_id' => 1,
            ],
        );
        $this->assertSame(
            'baz',
            $result->current()['name']
        );
    }
}
