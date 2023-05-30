<?php

session_start();

include('config/db.php');

$errores = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (!empty($_POST['csrf_token']) && hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {

        $nombre = $_POST['nombre'];
        $email = $_POST['email'];
        $password = $_POST['pass'];
        $repitepass = $_POST['repitepass'];

        $ip = $_SERVER['REMOTE_ADDR'];
        $captcha = $_POST['g-recaptcha-response'];
        $secretkey = "6Le2AdclAAAAAMMbYQSNtyslIXHJwUp0QOTFM7c6";

        $url = "https://www.google.com/recaptcha/api/siteverify?";
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
        }

        if (!empty($nombre) && !empty($email) && !empty($password) && !empty($repitepass)) {
            $nombre = filter_var(trim($nombre), FILTER_SANITIZE_STRING);
            $email = filter_var(trim($email), FILTER_SANITIZE_EMAIL);
            $password = trim($password);
            $repitepass = trim($repitepass);

            $query = "SELECT * from usuarios where email = ? limit 1";
            $stmt = mysqli_prepare($coon, $query);
            mysqli_stmt_bind_param($stmt, "s", $email);
            mysqli_stmt_execute($stmt);
            $resultado = mysqli_stmt_get_result($stmt);

            if (mysqli_num_rows($resultado) > 0) {
                $errores .= 'El Email ya existe </br>';
            }

            if ($password != $repitepass) {
                $errores .= 'Las contraseñas no coinciden';
            }

            if (!$errores) {
                $password = md5($password);
                $query = "INSERT INTO usuarios(nombre, email, passwordd, rol_id) VALUES (?, ?, ?, '3')";
                $stmt = mysqli_prepare($coon, $query);
                mysqli_stmt_bind_param($stmt, "sss", $nombre, $email, $password);
                if (mysqli_stmt_execute($stmt)) {
                    $_SESSION['nombre'] = $nombre;
                    $_SESSION['email'] = $email;
                }
                mysqli_stmt_close($stmt);
                mysqli_close($coon);
                header('location: login.php');
                exit;
            }
        } else {
            $errores .= 'Todos los datos son obligatorios';
        }
    } else {
        $errores .= 'Token CSRF inválido';
    }
}
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
include('views/registro.view.php');
?>

