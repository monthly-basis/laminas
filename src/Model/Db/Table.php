<?php

declare(strict_types=1);

namespace MonthlyBasis\Laminas\Model\Db;

class Table
{
    private string $table;

    public function __construct(
        protected \Laminas\Db\Sql\Sql $sql
    ) {}

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

    public function setTable(string $table): self
    {
        $this->table = $table;
        return $this;
    }
}
