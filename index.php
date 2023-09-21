<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TO DO LIST</title>
</head>
<body>
    <form action='index.php' method='POST'>
        <input type="text" name="texto" id="texto">
        <input type="submit" value="añadir pendiente">
    </form>

    <div id="mostrar-todo-container">
        <form id="formMostrarTodo" action="index.php" method="POST">
            <input type="checkbox" name="mostrar-todo" onchange="mostrarTodo(this)" 
            <?php if (isset($_POST['mostrar-todo'])) {
                if ($_POST['mostrar-todo'] == 'on') {
                    echo "checked";
                }
            } ?>
            >mostrar todo            
        </form>
    </div>

    <div id="todolist">
        <?php
            $server = 'localhost';
            $username = 'root';
            $password = 'Juan50395bg*';
            $database = 'todolistDB';
            
            $Connection = new mysqli($server, $username, $password, $database);

            if($Connection->connect_error){
                die("The Connection Failed". $Connection->connect_error);
            }
            if (isset($_POST['texto'])) {
                $texto = $_POST['texto'];

                if ($texto != "") {
                    //insertar datos 
                    $sql = "INSERT INTO todotable (texto, completado) values ('$texto', false)";
                    if ($Connection->query($sql) === true) {
                        //echo 'se insero la tarea';
                    }else{
                        dir("Error al insertar datos". $Connection->error);
                    }
                }
            }else if(isset($_POST['completar'])){
                //actualizar los datos cuando se complete una tarea
                $id = $_POST['completar'];

                $sql = "UPDATE todotable SET completado=1 WHERE id=$id ";
                if ($Connection->query($sql) === true) {
                    // echo 'se actualizo correctamente';
                }else{
                    dir("Error al actualizar los datos". $Connection->error);
                }
            }else if(isset($_POST['eliminar'])){
                //Eliminar las tareas que no se desean
                $id = $_POST['eliminar'];

                $sql = "DELETE from todotable WHERE id=$id";
                if ($Connection->query($sql) === true) {
                    // echo 'se elimino la tarea';
                }else{
                    dir("Error al actualizar los datos". $Connection->error);
                }
            }
            $sql = '';
            if(isset($_POST['mostrar-todo'])){
                //Mostrar todo las tareas que existen
                $todo = $_POST['mostrar-todo'];
                if ($todo == 'on') {
                    $sql = 'SELECT * from todotable ORDER BY completado DESC';
                }
            }else{
                $sql = 'SELECT * from todotable where completado=0';
            }

            //Obtener datos
            $resultado = $Connection->query($sql);
            if ($resultado->num_rows > 0) {
                echo '<h2>Tareas por completar</h2>'; 
                while ($row = $resultado->fetch_assoc()) {
                    ?>
                    <div>
                        <form method='POST' id='form<?php echo $row['id']; ?>' action=""> 
                            <input name="completar" value="<?php echo $row['id']; ?>" id="<?php echo $row['id']; ?>" type="checkbox" onchange="completarPendiente(this)" <?php if($row['completado'] == 1) echo " checked disabled "; ?>>   <?php echo $row['texto']; ?>
                            <div class="texto <?php if($row['completado'] == 1) echo "desabilitado"; ?>"></div>
                        </form>
                        <form method='POST' id='form_eliminar<?php echo $row['id']; ?>' action="index.php">
                            <input type="hidden" name="eliminar" value="<?php echo $row['id']; ?>">
                            <input type="submit" value="eliminar">
                        </form>
                    </div>
                    <?php
                }
            }

            //Obtener datos de las tareas completadas
            $sql = 'SELECT * from todotable where completado=1';
            $resultado = $Connection->query($sql);
            if ($resultado->num_rows > 0) {
                echo '<h2>Tareas completadas</h2>';               
                while ($row = $resultado->fetch_assoc()) {
                    ?> <li><?php echo $row['texto']; ?></li> <?php
                }
            }

            $Connection->close();

        ?>
    </div>

</body>
    <script>
        function completarPendiente(pendiente) {
            var id = 'form'+pendiente.id;
            var formulario = document.getElementById(id);
            formulario.submit();
        }
        function mostrarTodo(e) {
            var formulario = document.getElementById("formMostrarTodo");
            formulario.submit();
        }
    </script>
</html>