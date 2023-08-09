<?php

declare(strict_types=1);

namespace MonthlyBasis\Laminas\Model\Db;

use Laminas\Db\Adapter\Driver\Pdo\Result;
use Laminas\Db\Sql\Where;

class Table
{
    protected \Laminas\Db\Adapter\Adapter $adapter;
    protected \Laminas\Db\Sql\Sql $sql;
    protected string $table;

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

    public function delete(
        array $where
    ): Result {
        $delete = $this->sql->delete()->from($this->table);
        $delete->where($where);
        return $this->sql->prepareStatementForSqlObject($delete)->execute();
    }

    public function insert(
        array $values,
        array $columns = null,
    ): Result {
        $insert = $this->sql->insert()->into($this->table);

        if (isset($columns)) {
            $insert->columns($columns);
        }

        $insert->values($values);

        return $this->sql->prepareStatementForSqlObject($insert)->execute();
    }

    public function insertIgnore(
        array $values,
        array $columns = null,
    ): Result {
        $insertIgnore = new \Laminas\Db\Sql\InsertIgnore($this->table);

        if (isset($columns)) {
            $insertIgnore->columns($columns);
        }

        $insertIgnore->values($values);

        return $this->sql->prepareStatementForSqlObject($insertIgnore)->execute();
    }

    public function select(
        array $columns = null,
        array $joinArguments = null,
        array|Where $where = null,
        array|string $order = null,
        int $limit = null,
        int $offset = null,
    ): Result {
        $select = $this->sql->select($this->table);

        if (isset($columns)) {
            $select->columns($columns);
        }

        if (isset($joinArguments)) {
            $select->join(...$joinArguments);
        }

        if (isset($where)) {
            $select->where($where);
        }

        if (isset($order)) {
            $select->order($order);
        }

        if (isset($limit)) {
            $select->limit($limit);
        }

        if (isset($offset)) {
            $select->offset($offset);
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

    public function update(
        array $set,
        array|Where $where = null,
    ): Result {
        $update = $this->sql->update($this->table);

        $update->set($set);

        if (isset($where)) {
            $update->where($where);
        }

        return $this->sql->prepareStatementForSqlObject($update)->execute();
    }
}
