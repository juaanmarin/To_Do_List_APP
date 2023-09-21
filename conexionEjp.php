<?php
    $server = 'localhost';
    $username = 'root';
    $password = 'Juan50395bg*';
    $database = 'todolistDB';

    $conexion = new mysqli($server, $username, $password);

    if($conexion -> connect_error){
        die("Conexion Fallida". $conexion->connect_error);

    }
    //echo "Conexion Exitosa";

    $sql = "CREATE DATABASE todolistDB";
    if ($conexion->query($sql) === true) {
        echo 'Base de datos creada';
    }else{
        die("Error al crear la base de datos ".$conexion->error);
    }

    $conexion2 = new mysqli($server, $username, $password, $database);

    $sql = "CREATE TABLE todoTable (
        id  INT(11) AUTO_INCREMENT PRIMARY KEY,
        texto VARCHAR(100) NOT NULL,
        completado BOOLEAN NOT NULL,
        feccrea TIMESTAMP)";
    
    if ($conexion2->query($sql) === true) {
        echo 'Tabla creada correctamente';
    }else{
        die("Error al crear la tabla".$conexion2->error);
    }

?>