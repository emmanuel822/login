<?php
session_start();
include('config/db.php');

if (isset($_SESSION['rol'])) {
    if ($_SESSION['rol'] == 1) {
        header('location: dashboard-admin.php');
        exit();
    } elseif ($_SESSION['rol'] == 2) {
        header('location: dashboard-editor.php');
        exit();
    } elseif ($_SESSION['rol'] == 3) { 
        header('location: home.php');
        exit();
    }
}

$errores = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!empty($_POST['email']) && !empty($_POST['password'])) {
        $email = $_POST['email'];
        $pass = md5($_POST['password']);

        $query = "SELECT * FROM usuarios WHERE (email='$email' AND passwordd='$pass')";

        // Verificar el Captcha
        $ip = $_SERVER['REMOTE_ADDR'];
        $captcha = $_POST['g-recaptcha-response'];
        $secretkey = "6Le2AdclAAAAAMMbYQSNtyslIXHJwUp0QOTFM7c6";

        $url = "https://www.google.com/recaptcha/api/siteverify";
        $data = array(
            'secret' => $secretkey,
            'response' => $captcha,
            'remoteip' => $ip
        );

        $options = array(
            'http' => array(
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => http_build_query($data)
            )
        );

        $context = stream_context_create($options);
        $respuesta = file_get_contents($url, false, $context);

        $atributos = json_decode($respuesta, true);

        if (!$atributos['success']) {
            $errores .= 'Por favor verifica el Captcha';
        } else {
            $intentos_permitidos = 3;
            $tiempo_bloqueo = 60; 
            $consulta_bloqueo = "SELECT * FROM bloqueos WHERE ip='$ip'";
            $resultado_bloqueo = mysqli_query($coon, $consulta_bloqueo);

            if (mysqli_num_rows($resultado_bloqueo) > 0) {
                $row_bloqueo = mysqli_fetch_assoc($resultado_bloqueo);
                $tiempo_actual = time();
                $tiempo_bloqueo_fin = strtotime($row_bloqueo['tiempo_fin']);

                if ($tiempo_actual < $tiempo_bloqueo_fin) {
                    $errores .= 'Has superado el número máximo de intentos. Por favor, inténtalo más tarde.';
                    
                    echo "<script>document.getElementById('btn-login').disabled = true;</script>";
                } else {
                    $eliminar_bloqueo = "DELETE FROM bloqueos WHERE ip='$ip'";
                    mysqli_query($coon, $eliminar_bloqueo);
                }
            }

            if (empty($errores)) {
                $resultado = mysqli_query($coon, $query);

                if (mysqli_num_rows($resultado) > 0) {
                    $row = mysqli_fetch_assoc($resultado);
                    $_SESSION['nombre'] = $row['nombre'];
                    $_SESSION['email'] = $row['email'];
                    $_SESSION['rol'] = $row['rol_id'];
                
                    // Redireccionar según el rol del usuario
                    if ($_SESSION['rol'] == 1) {
                        header('location: dashboard-admin.php'); // Redireccionar al panel de administración
                    } elseif ($_SESSION['rol'] == 2) {                            
                        header('location: dashboard-editor.php'); // Redireccionar al panel de editor
                    } elseif ($_SESSION['rol'] == 3) {
                        
                        header('location: home.php');
                    }           
                    // Registro de inicio de sesión exitoso
                    $fechaInicio = date('Y-m-d H:i:s');
                    $ipIPv4 = $_SERVER['REMOTE_ADDR'];
                    $ipIPv6 = $_SERVER['REMOTE_ADDR'];
                    $navegador = $_SERVER['HTTP_USER_AGENT'];
                    $sistemaOperativo = php_uname('s') . ' ' . php_uname('r');
                    $usuario = $_SESSION['email'];
                    $exito = true;
                    $insertQuery = "INSERT INTO logs (fecha_inicio, ip_ipv4, ip_ipv6, navegador, sistema_operativo, usuario, exito) VALUES ('$fechaInicio', '$ipIPv4', '$ipIPv6', '$navegador', '$sistemaOperativo', '$usuario', $exito)";
                    mysqli_query($coon, $insertQuery);
                } else {
                    $errores .= 'Correo Electrónico o contraseña incorrecto';

                    $intentos_fallidos = 1;
                    $consulta_intentos = "SELECT * FROM intentos WHERE ip='$ip'";
                    $resultado_intentos = mysqli_query($coon, $consulta_intentos);

                    if (mysqli_num_rows($resultado_intentos) > 0) {
                        $row_intentos = mysqli_fetch_assoc($resultado_intentos);
                        $intentos_fallidos = $row_intentos['intentos'] + 1;

                        $actualizar_intentos = "UPDATE intentos SET intentos='$intentos_fallidos' WHERE ip='$ip'";
                        mysqli_query($coon, $actualizar_intentos);
                    } else {
                        $insertar_intentos = "INSERT INTO intentos (ip, intentos) VALUES ('$ip', $intentos_fallidos)";
                        mysqli_query($coon, $insertar_intentos);
                    }

                    if ($intentos_fallidos >= $intentos_permitidos) {
                        $tiempo_fin = date('Y-m-d H:i:s', strtotime("+$tiempo_bloqueo seconds"));
                        
                        $insertar_bloqueo = "INSERT INTO bloqueos (ip, tiempo_fin) VALUES ('$ip', '$tiempo_fin')";
                        mysqli_query($coon, $insertar_bloqueo);

                        $eliminar_intentos = "DELETE FROM intentos WHERE ip='$ip'";
                        mysqli_query($coon, $eliminar_intentos);

                        $errores .= ' Has superado el número máximo de intentos. Por favor, inténtalo más tarde, dentro de 1 Minuto';

                        // Log de inicio de sesión fallido
                        $fechaInicio = date('Y-m-d H:i:s');
                        $ipIPv4 = $_SERVER['REMOTE_ADDR'];
                        $ipIPv6 = $_SERVER['REMOTE_ADDR'];
                        $navegador = $_SERVER['HTTP_USER_AGENT'];
                        $sistemaOperativo = php_uname('s') . ' ' . php_uname('r');
                        $usuario = $email;  // Usar el correo ingresado
                        $exito = false;  // Indicar que el inicio de sesión falló
                        $insertQuery = "INSERT INTO logs (fecha_inicio, ip_ipv4, ip_ipv6, navegador, sistema_operativo, usuario, exito) VALUES ('$fechaInicio', '$ipIPv4', '$ipIPv6', '$navegador', '$sistemaOperativo', '$usuario', '$exito')";
                        mysqli_query($coon, $insertQuery);

                        echo "<script>document.getElementById('btn-login').disabled = true;</script>";
                    }
                }
            }
        }
    } else {
        $errores .= 'Todos los datos son necesarios';
    }
}

mysqli_close($coon);

include('views/login.view.php');
?>
