<?php

namespace App\services;

class db
{

    private $config = [];

    protected $connect;

    public function __construct($config)
    {
        $this->config = $config;
    }

    protected function getConnection()
    {
        if (empty($this->connect)) {
            $this->connect = new \PDO(
                $this->getPrepareDsnString(),
                $this->config['username'],
                $this->config['password']
            );
            $this->connect->setAttribute(
                \PDO::ATTR_DEFAULT_FETCH_MODE,
                \PDO::FETCH_ASSOC);
        }

        return $this->connect;
    }

    protected function getPrepareDsnString()
    {
        return sprintf(
            '%s:host=%s;dbname=%s;charset=%s',
            $this->config['driver'],
            $this->config['host'],
            $this->config['db'],
            $this->config['charset']
        );
    }

    protected function query($sql, $params = [])
    {
        $PDOStatement = $this->getConnection()->prepare($sql);
        $PDOStatement->execute($params);
        return $PDOStatement;
    }

    public function queryObject(string $sql, $class, $params = [])
    {
        $PDOStatement = $this->query($sql, $params);
        $PDOStatement->setFetchMode(\PDO::FETCH_CLASS, $class);
        return $PDOStatement->fetch();
    }

    public function queryObjects(string $sql, $class, $params = [])
    {
        $PDOStatement = $this->query($sql, $params);
        $PDOStatement->setFetchMode(\PDO::FETCH_CLASS, $class);
        return $PDOStatement->fetchAll();
    }

    public function find(string $sql, $params = [])
    {
        return $this->query($sql, $params)->fetch();
    }

    public function findAll(string $sql, $params = [])
    {
        return $this->query($sql, $params)->fetchAll(\PDO::FETCH_OBJ);
    }

    public function exec(string $sql, $params = [])
    {
        return $this->query($sql, $params);
    }
    public function lastInsertId()
    {
        return $this->getConnection()->lastInsertId();
    }
}