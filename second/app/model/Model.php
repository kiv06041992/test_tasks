<?php
namespace app\model;

abstract class Model {
    private \PDO $pdo;

    public function __construct(array $data = []) {
        $this->pdo = new \PDO('mysql:host=localhost;dbname=exam', 'base', 'base');
        $this->init($data);


    }

    public function init(array $data = []): void {
        if (count($data) > 0) {
            foreach ($this->getField() as $field=>$value) {
                if (isset($data[$field])) {
                    $this->$field = $data[$field];
                }
            }
        }
    }

    public function create(): bool {
        $queryString = "INSERT INTO `{$this->getTableName()}` 
                    (`" . implode('`,`', array_keys($this->getField())) . "`) 
                    VALUES (" . implode(',', array_fill(0, count($this->getField()), '?')) . ")";
        $statement = $this->pdo->prepare($queryString);

        if ($statement->execute(array_values($this->getField()))) {
            return true;
        } else {
            return false;
        }
    }

    public function read(array $data = []): array {
        $queryString = "SELECT * FROM {$this->getTableName()}";
        if (count($data) > 0) {
            $pdoPrepareArray = [];
            $pdoExecuteArray = [];
            foreach ($this->getField() as $field=>$value) {
                if (isset($data[$field])) {
                    $pdoPrepareArray[] = "`{$field}` = ?";
                    $pdoExecuteArray[] = $data[$field];
                }
            }

            if (count($pdoPrepareArray)) {
                $queryString .= ' WHERE ';
                $queryString .= implode(' AND ', $pdoPrepareArray);
            }

            $statement = $this->pdo->prepare($queryString);


            if ($statement->execute($pdoExecuteArray)) {
                return ($statement->fetch()) ? : [];
            } else {
                return [];
            }
        }
    }

    public function update() {}
    public function delete() {}

    public function commit() {}

    public function getField(): array {
        $r = [];
        $tn = $this->getTableName();
        foreach ($this as $field=>$value) {
            //@TODO: it will be changed
            if ($tn != $value && $field != 'pdo') {
                $r[$field] = $value;
            }
        }
        return $r;
    }

    public function getTableName(): string {
        return $this->tableName;
    }}