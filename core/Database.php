<?php

class Database
{

    private $conn;

    function __construct($servername, $username, $dbname, $password)
    {
        $this->conn = new Mysqli($servername, $username, $password, $dbname) or die("Error de conexion " . mysqli_connect_error());
        $this->conn->set_charset("utf8mb4");
    }

    public function query($sql)
    {
        $result = $this->conn->query($sql);

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function queryWithParams($sql, $params) {
        $stmt = $this->conn->prepare($sql);

        if ($stmt === false) {
            throw new Exception("Error en la preparación de la consulta: " . $this->conn->error);
        }

        // Armar dinámicamente los tipos (s para string, i para integer, etc.)
        if (count($params) > 0) {
            // asumimos todos string; podés armar dinámicamente los tipos
            $types = str_repeat('s', count($params));
            $stmt->bind_param($types, ...$params);
        }

        $stmt->execute();

        $result = $stmt->get_result();

        if ($result === false) {
            return true; // Para consultas tipo INSERT, UPDATE...
        }

        return $result->fetch_all(MYSQLI_ASSOC);
    }
    public function execute($sql)
    {
        return $this->conn->query($sql);
    }

    function __destruct()
    {
        $this->conn->close();
    }
}