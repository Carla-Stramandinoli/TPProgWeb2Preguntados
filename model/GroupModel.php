<?php

class GroupModel
{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function getIntegrantes()
    {
        return  $this->database->query("SELECT * FROM integrantes");
    }

    public function add($nombre, $intrumento)
    {
        $sql = "INSERT INTO integrantes (nombre,instrumento) values ('$nombre', '$intrumento')";
        $this->database->execute($sql);
    }
}