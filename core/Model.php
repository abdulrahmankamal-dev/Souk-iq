<?php
/**
 * SOUK.IQ Core Base Model (PDO Database Wrapper)
 */

namespace Core;

use PDO;
use PDOException;

class Model {
    protected static $dbConnection = null;
    protected $db;
    protected $statement;
    protected $table = '';
    protected $useSoftDeletes = false;

    public function __construct() {
        $this->connect();
    }

    private function connect() {
        if (self::$dbConnection === null) {
            try {
                $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHAR;
                $options = [
                    PDO::ATTR_PERSISTENT => true,
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ
                ];
                self::$dbConnection = new PDO($dsn, DB_USER, DB_PASS, $options);
            } catch (PDOException $e) {
                // If installer is not run, fallback or throw error
                if (defined('APP_ENV') && APP_ENV === 'development') {
                    throw new PDOException($e->getMessage(), (int)$e->getCode());
                } else {
                    die("Database connection failed. Please contact the administrator.");
                }
            }
        }
        $this->db = self::$dbConnection;
    }

    // Prepare SQL statements
    public function query($sql) {
        $this->statement = $this->db->prepare($sql);
    }

    // Bind parameters
    public function bind($param, $value, $type = null) {
        if (is_null($type)) {
            switch (true) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
            }
        }
        $this->statement->bindValue($param, $value, $type);
    }

    // Execute prepared statement
    public function execute() {
        return $this->statement->execute();
    }

    // Get multiple records (result set)
    public function resultSet() {
        $this->execute();
        return $this->statement->fetchAll(PDO::FETCH_OBJ);
    }

    // Get single record
    public function single() {
        $this->execute();
        return $this->statement->fetch(PDO::FETCH_OBJ);
    }

    // Get row count
    public function rowCount() {
        return $this->statement->rowCount();
    }

    // Get last inserted ID
    public function lastInsertId() {
        return $this->db->lastInsertId();
    }

    // Transactions
    public function beginTransaction() {
        return $this->db->beginTransaction();
    }

    public function commit() {
        return $this->db->commit();
    }

    public function rollBack() {
        return $this->db->rollBack();
    }

    // Active Record CRUD Helpers
    public function find($id) {
        if (empty($this->table)) return null;
        $sql = "SELECT * FROM {$this->table} WHERE id = :id";
        if ($this->useSoftDeletes) {
            $sql .= " AND deleted_at IS NULL";
        }
        $sql .= " LIMIT 1";
        $this->query($sql);
        $this->bind(':id', $id);
        return $this->single();
    }

    public function findByUuid($uuid) {
        if (empty($this->table)) return null;
        $sql = "SELECT * FROM {$this->table} WHERE uuid = :uuid";
        if ($this->useSoftDeletes) {
            $sql .= " AND deleted_at IS NULL";
        }
        $sql .= " LIMIT 1";
        $this->query($sql);
        $this->bind(':uuid', $uuid);
        return $this->single();
    }

    public function findAll($orderBy = 'id DESC') {
        if (empty($this->table)) return [];
        $sql = "SELECT * FROM {$this->table}";
        if ($this->useSoftDeletes) {
            $sql .= " WHERE deleted_at IS NULL";
        }
        $sql .= " ORDER BY {$orderBy}";
        $this->query($sql);
        return $this->resultSet();
    }

    public function create($data) {
        if (empty($this->table)) return false;
        $fields = array_keys($data);
        $placeholders = ':' . implode(', :', $fields);
        
        $sql = "INSERT INTO {$this->table} (" . implode(', ', $fields) . ") VALUES ({$placeholders})";
        
        $this->query($sql);
        foreach ($data as $key => $value) {
            $this->bind(':' . $key, $value);
        }
        
        if ($this->execute()) {
            return $this->lastInsertId();
        }
        return false;
    }

    public function update($id, $data) {
        if (empty($this->table)) return false;
        $updateParts = [];
        foreach ($data as $key => $value) {
            $updateParts[] = "{$key} = :{$key}";
        }
        
        $sql = "UPDATE {$this->table} SET " . implode(', ', $updateParts) . " WHERE id = :id";
        
        $this->query($sql);
        $this->bind(':id', $id);
        foreach ($data as $key => $value) {
            $this->bind(':' . $key, $value);
        }
        
        return $this->execute();
    }

    public function delete($id) {
        if (empty($this->table)) return false;
        if ($this->useSoftDeletes) {
            $sql = "UPDATE {$this->table} SET deleted_at = CURRENT_TIMESTAMP WHERE id = :id";
        } else {
            $sql = "DELETE FROM {$this->table} WHERE id = :id";
        }
        $this->query($sql);
        $this->bind(':id', $id);
        return $this->execute();
    }
}
