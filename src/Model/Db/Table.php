<?php

declare(strict_types=1);

namespace MonthlyBasis\Laminas\Model\Db;


class Table
{
    private \Laminas\Db\Adapter\Adapter $adapter;
    private \Laminas\Db\Sql\Sql $sql;
    private string $table;

    public function getAdapter(): \Laminas\Db\Adapter\Adapter
    {
        return $this->adapter;
    }

    public function getSql(): \Laminas\Db\Sql\Sql
    {
        return $this->sql;
    }

    public function getTable(): string
    {
        return $this->table;
    }

    public function insert(
        array $values,
        array $columns = null,
    ): \Laminas\Db\Adapter\Driver\Pdo\Result {
        $insert = $this->sql->insert()->into($this->table);

        if (isset($columns)) {
            $insert->columns($columns);
        }

        $insert->values($values);

        return $this->sql->prepareStatementForSqlObject($insert)->execute();
    }

    public function select(
        array $columns = null,
        array $where = null,
    ): \Laminas\Db\Adapter\Driver\Pdo\Result {
        $select = $this->sql->select($this->table);

        if (isset($columns)) {
            $select->columns($columns);
        }

        if (isset($where)) {
            $select->where($where);
        }

        return $this->sql->prepareStatementForSqlObject($select)->execute();
    }

    public function setAdapter(\Laminas\Db\Adapter\Adapter $adapter)
    {
        $this->adapter = $adapter;
        return $this;
    }

    public function setSql(\Laminas\Db\Sql\Sql $sql): self
    {
        $this->sql = $sql;
        return $this;
    }

    public function setTable(string $table): self
    {
        $this->table = $table;
        return $this;
    }
}
