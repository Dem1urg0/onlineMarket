<?php

namespace App\services;

/**
 * Класс PDO для работы с базой данных
 */
class db
{
    /**
     * Массив с параметрами подключения к базе данных
     * @var array $config
     */
    private $config = [];

    /**
     * Объект PDO, текущее соединение с БД
     * @var \PDO $connect
     */
    protected $connect;

    /**
     * Конструктор класса
     * @param $config
     */
    public function __construct($config)
    {
        $this->config = $config;
    }

    /**
     * Метод подключения к базе данных
     * @return \PDO
     */
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

    /**
     * Метод формирования строки подключения к базе данных
     * @return string
     */
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

    /**
     * Метод выполнения запроса к базе данных
     * @param string $sql - запрос
     * @param array $params - параметры запроса
     * @return \PDOStatement
     */
    protected function query($sql, $params = [])
    {
        $PDOStatement = $this->getConnection()->prepare($sql);
        $PDOStatement->execute($params);
        return $PDOStatement;
    }

    /**
     * Метод выполнения запроса к базе данных и возврат результата
     * в виде объекта определенной сущности
     * @param string $sql - запрос
     * @param $class - класс сущности
     * @param $params - параметры запроса
     * @return mixed
     */
    public function queryObject(string $sql, $class, $params = [])
    {
        $PDOStatement = $this->query($sql, $params);
        $PDOStatement->setFetchMode(\PDO::FETCH_CLASS, $class);
        return $PDOStatement->fetch();
    }

    /**
     * Метод выполнения запроса к базе данных и возврат результата
     * в виде массива объектов определенной сущности
     * @param string $sql - запрос
     * @param $class - класс сущности
     * @param $params - параметры запроса
     * @return array
     */
    public function queryObjects(string $sql, $class, $params = [])
    {
        $PDOStatement = $this->query($sql, $params);
        $PDOStatement->setFetchMode(\PDO::FETCH_CLASS, $class);
        return $PDOStatement->fetchAll();
    }

    /**
     * Поиск одной записи по параметрам
     * @param string $sql - запрос
     * @param $params - параметры запроса
     * @return mixed
     */
    public function find(string $sql, $params = [])
    {
        return $this->query($sql, $params)->fetch();
    }

    /**
     * Поиск всех записей по параметрам
     * @param string $sql - запрос
     * @param $params - параметры запроса
     * @return array
     */
    public function findAll(string $sql, $params = [])
    {
        return $this->query($sql, $params)->fetchAll(\PDO::FETCH_OBJ);
    }

    /**
     * Метод выполнения запроса к базе данных и возврат результата
     * @param string $sql - запрос
     * @param $params - параметры запроса
     * @return \PDOStatement
     */
    public function exec(string $sql, $params = [])
    {
        return $this->query($sql, $params);
    }

    /**
     * Метод возвращает id последней вставленной записи
     * @return false|string
     */
    public function lastInsertId()
    {
        return $this->getConnection()->lastInsertId();
    }
}