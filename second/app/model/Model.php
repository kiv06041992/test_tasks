<?php
/**
 * TODO: it will be written
 */
namespace app\model;

abstract class Model {
    private static \PDO $pdo;
    //** cache for table fields */
    private static array $field = [];

    public function __construct(array $data = []) {
        self::$pdo = new \PDO('mysql:host=localhost;dbname=exam', 'base', 'base');
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

    public function create(array $data): bool {
        $queryString = "INSERT INTO `{$this->getTableName()}` 
                    (`" . implode('`,`', array_keys($this->getField())) . "`) 
                    VALUES (" . implode(',', array_fill(0, count($this->getField()), '?')) . ")";
        $statement = self::$pdo->prepare($queryString);
        if ($statement->execute(array_values($data))) {
            return true;
        } else {
            return false;
        }
    }

    public function read(array $data = []): array {
        if (count($data) > 0) {
            $queryString = "SELECT * FROM {$this->getTableName()}";

            $pdoPrepareArray = [];
            $pdoExecuteArray = [];
            foreach ($this->getField() as $field=>$value) {
                if (!empty($data[$field])) {
                    $pdoPrepareArray[] = "`{$field}` = ?";
                    $pdoExecuteArray[] = $data[$field];
                }
            }

            if (count($pdoPrepareArray)) {
                $queryString .= ' WHERE ';
                $queryString .= implode(' AND ', $pdoPrepareArray);
            }
            $statement = self::$pdo->prepare($queryString);


            if ($statement->execute($pdoExecuteArray)) {
                $r = [];
                foreach ($statement->fetchALL(\PDO::FETCH_ASSOC) as $field=>$value) {
                    $r[$field] = $value;
                }

                return $r;
            } else {
                return [];
            }
        }
    }

    public function update(array $dataForSearching, array $dataForUpdate): bool {
        $pdoPrepareArrayUpdate = [];
        $pdoPrepareArrayWhere = [];

        $pdoExecuteArray = [];

        foreach ($this->getField() as $field=>$value) {
            if (!empty($dataForSearching[$field])) {
                $pdoPrepareArrayUpdate[] = "`{$field}` = ?";
                $pdoExecuteArray[] = $dataForSearching[$field];
            }
        }

        if (count($pdoPrepareArrayUpdate) > 0) {
            foreach ($this->getField() as $field => $value) {
                if (!empty($dataForUpdate[$field])) {
                    $pdoPrepareArrayWhere[] = "`{$field}` = ?";
                    $pdoExecuteArray[] = $dataForUpdate[$field];
                }
            }
        }
        $queryString = "UPDATE `{$this->getTableName()}` 
                SET " . implode(',', $pdoPrepareArrayUpdate) .
                (count($pdoPrepareArrayWhere) > 0 ? ' WHERE ' . implode(' AND ', $pdoPrepareArrayWhere) : '');
        $statement = self::$pdo->prepare($queryString);

        return $statement->execute($pdoExecuteArray) ?? false;

    }
    public function delete() {}

    public function commit() {}

    public function getField(): array {
        $r = [];
        $tn = $this->getTableName();
        if (!isset(self::$field[get_class($this)])) {
            foreach ($this as $field => $value) {
                //@TODO: it will be changed
                if ($tn != $value && $field != 'pdo') {
                    $r[$field] = $value;
                }
            }
            self::$field[get_class($this)] = $r;
        } else {
            $r = self::$field[get_class($this)];
        }
        return $r;
    }

    public function getTableName(): string {
        return $this->tableName;
    }}