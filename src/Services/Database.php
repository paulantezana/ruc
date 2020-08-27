<?php

class Database
{
    private $connection;
    public function __construct()
    {
        $options = array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        );

        // $this->connection = new PDO('mysql:host=localhost;dbname=buscaruc_buscaruc', 'buscaruc', '*yU150rd', $options);
        $this->connection = new PDO('mysql:host=localhost;dbname=paulpvad_ruc', 'paulpvad_paul', 'bvTLG.^Sp?VW', $options);
        // $this->connection = new PDO('mysql:host=localhost;dbname=ruc', 'root', '', $options);
        $this->connection->exec("SET CHARACTER SET UTF8");
    }
    public function getConnection()
    {
        return $this->connection;
    }
    public function closeConnection()
    {
        $this->connection = null;
    }
}
