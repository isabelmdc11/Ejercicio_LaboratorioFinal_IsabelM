<?php
//Conexión con PDO

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "laboratoriophp";

//Create connection
$conection = new mysqli($servername, $username, $password, $dbname);


$comprobarCont = false;
$comprobaremail = false;

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">

    <title>Practica de Formulario Php</title>
</head>

<body>
    <div class="container2">

        <?php

        if (isset($_POST['submit'])) {

            // Obtener valores del formulario
            $nombre = $_POST['nombre'];
            $apellido1 = $_POST['apellido1'];
            $apellido2 = $_POST['apellido2'];
            $email = $_POST['email'];
            $login = $_POST['login'];
            $password = $_POST['password'];

            // Check connection
            if ($conection->connect_error) {
                die("Connection failed: " . $conection_error);
            }

            // Validar valores
            if (!empty($nombre) || !empty($apellido1) || !empty($apellido2) || !empty($email) || !empty($login) || !empty($password)) {

                //comprobar validacion de correo electronico
                if (filter_var($email, FILTER_VALIDATE_EMAIL) && preg_match("/^\w+([\.-]?\w+)*@(?:|hotmail|outlook|yahoo|live|gmail)\.(?:|com|es)+$/", $email)) {
                    $comprobaremail = true;
                } else {
                    echo "<p>El email  $email introducido no es correcto <a href='index.html'>Volver al formulario de registro</a></p>";
                    $comprobaremail = false;
                }

                echo "<br>";

                // comprobar validacion de contraseña

                $password_str = strlen($password);
                if ($password_str >= 4 && $password_str <= 8) {
                    $comprobarCont = true;
                } else {
                    echo "<p>La contraseña introducida no es correcta . <br> Tiene que tener una longitud entre 4 y 8 caracteres. <a href='index.html'>Volver al formulario de registro</a></p>";
                    $comprobarCont = false;
                }


                if ($comprobaremail == true &&  $comprobarCont == true) {

                    // Consulta para comprobar si el correo electrónico ya existe en la base de datos
                    $sql = "SELECT * FROM usuarios WHERE email='$email'";
                    $resultado = mysqli_query($conection, $sql);

                    // Comprobar si la consulta devolvió algún resultado
                    if (mysqli_num_rows($resultado) > 0) {
                        echo "<p>No se puede insertar .Porque ese correo electrónico ya está registrado.<a href='index.html'>Volver al formulario de registro</a></p>";
                    } else {
                        // Insertar valores en la base de datos
                        $sql = "INSERT INTO usuarios (nombre, apellido1, apellido2, email, login, password) VALUES ('$nombre', '$apellido1', '$apellido2', '$email', '$login', '$password')";
                        $resultado = mysqli_query($conection, $sql);
                        if ($resultado == true) {
                            echo "Registro completado con éxito";
                    ?>
                            <form method="post">
                                <button type="submit" name="mostrarTabla">Consulta</button>
                                <!-- <input value=""> -->
                            </form>

                    <?php
                        } else {
                            echo "Error al insertar los datos: " . mysqli_error($conection);
                        }
                    }
                }
            } else {
                echo "Algun campo está vacío o no es válido.";
            }
        }

        /// Para mostrar los datos de la tabla en una tabla 
        if (isset($_POST['mostrarTabla'])) {
            $mostraUsuario = "SELECT * FROM usuarios";

            if ($resultado = $conection->query($mostraUsuario)) {
                ?>
                <h2>Listado de usuarios registrados</h2>
                <a href="index.html" style="width: 14%;padding: 10px;">Volver a Inicio</a>
                <div class="tabla-scroll">
                    <table>
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Primer Apellido</th>
                                <th>Segundo Apellido </th>
                                <th>Email</th>
                                <th>Login</th>
                                <th>Password</th>
                            </tr>
                        </thead>
                        <tbody>
                    <?php

                    foreach ($conection->query($mostraUsuario) as $row) {
                        echo "<tr>";
                        echo "<td>" . $row['nombre'] . "</td>";
                        echo "<td>" . $row['apellido1'] . "</td>";
                        echo "<td>" . $row['apellido2'] . "</td>";
                        echo "<td>" . $row['email'] . "</td>";
                        echo "<td>" . $row['login'] . "</td>";
                        echo "<td>" . $row['password'] . "</td>";
                        echo "</tr>";
                    }
                }
            }
                    ?>
                        </tbody>
                    </table>
                </div>
    </div>
</body>

</html>