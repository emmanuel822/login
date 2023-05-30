<!DOCTYPE html>
<html>
<head>
    <title>Registro</title>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <style>
        form {
            border-radius: 5%;
        }
        body {
            background-image: url("https://lh3.googleusercontent.com/p/AF1QipNvKDJnZadxwAs49nUaq_p6KDxjoj7UZ6PaHcUr=s1660-w1660-h1180");
        }
        .container {
            animation-name: animacion;
            animation-duration: 1s;
            animation-delay: 0s;
            animation-fill-mode: forwards;
        }
        @keyframes animacion {
            from {
                opacity: 0;
                transform: scale(0.5);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }
    </style>
</head>
<body>
    <?php include('shared/header.php'); ?>
    <div class="container">
        <div class="row">
            <div class="col-lg-4"></div>
            <div class="col-lg-4">
                <br>
                <br>
                <br>
                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" class="bg-light p-5">
                    <div class="form-group text-center">
                        <img src="https://pinotepa.tecnm.mx/wp-content/uploads/2020/03/logotecPNG2017-1536x1536.png" width="100px" height="100px" alt="">
                    </div>
                    <div class="form-group">
                        <label for="my-input"><strong>Nombre</strong></label>
                        <input id="my-input" class="form-control" type="text" name="nombre">
                    </div>
                    <div class="form-group">
                        <label for="my-input"><strong>Correo Electrónico</strong></label>
                        <input id="my-input" class="form-control" type="email" name="email">
                    </div>
                    <div class="form-group">
                        <label for="my-input"><strong>Contraseña</strong></label>
                        <input id="my-input" class="form-control" type="password" name="pass">
                    </div>
                    <div class="form-group">
                        <label for="my-input"><strong>Confirmar Contraseña</strong></label>
                        <input id="my-input" class="form-control" type="password" name="repitepass">
                    </div>
                    <div class="form-group">
                        <div class="g-recaptcha" data-sitekey="6Le2AdclAAAAAGzqrgR-2IZT_XNuLHWGHly6wkrn"></div>
                    </div>
                    <?php if (!empty($errores)) { ?>
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <strong><?php echo $errores; ?></strong>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php } ?>
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                    <input type="submit" class="btn btn-info btn-lg btn-block text-uppercase btn-rounded" value="Registrarse" name="registro">
                    <br>
                    <strong>¿Ya tienes cuenta? <a href="login.php" class="text-primary"><b>Iniciar sesión</b></a></strong>
                </form>
            </div>
            <div class="col-lg-4"></div>
        </div>
    </div>
    <?php include('shared/footer.php'); ?>
</body>
</html>
