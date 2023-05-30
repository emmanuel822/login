<?php
session_start();
if ($_SESSION['rol'] != 2) {
    header('location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        /* Estilos para el cuerpo del dashboard */
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        /* Estilos para el encabezado */
        header {
            background-color: #447a71;
            color: #fff;
            padding: 10px;
            text-align: center;
        }

        /* Estilos para el menú */
        nav {
            background-color: #447a71d9;
            padding: 10px;
        }

        nav ul {
            list-style-type: none;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: space-between;
        }

        nav ul li {
            margin-right: 10px;
        }

        /* Estilos para el botón de salir */
        .btn-logout  {
            background-color: #ff0000;
            color: #fff ;
            font-weight: bold;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        /* Estilos para los botones */
        button  {
            background-color: #000000;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .btn-logout:hover {
            background-color: #ff3333;
        }

        /* Estilos para el contenedor del dashboard */
        .dashboard-container {
            display: grid;
            grid-template-areas:
                "header header header"
                "sidebar content1 content2"
                "footer footer footer";
            grid-template-rows: auto 1fr auto;
            grid-template-columns: 200px 1fr 1fr;
            height: calc(89vh - 80px); /* Ajusta la altura según tus necesidades */
        }

        /* Estilos para el sidebar */
        .sidebar {
            grid-area: sidebar;
            background-color: #f1f1f1;
            padding: 20px;
        }

        /* Estilos para el contenido 1 */
        .content1 {
            grid-area: content1;
            background-color: #f1f1f1;
            padding: 20px;
        }

        /* Estilos para el contenido 2 */
        .content2 {
            grid-area: content2;
            background-color: #f1f1f1;
            padding: 20px;
        }

        /* Estilos para el footer */
        .footer {
            grid-area: footer;
            background-color: #447a71;
            color: #fff;
            padding: 0px;
            text-align: center;
        }

        h1{
            text-align: left;
        }
    </style>
</head>
<body>
    <header>
        <h1>Bienvenido Editor <?php echo $_SESSION['nombre']; ?></h1>
    </header>
    <nav>
        <ul>
            <li><button>Agregar</button></li>
            <li><button>Ver</button></li>
            <li><button>Editar</button></li>
            <li>
                <a href="logout.php" style="text-decoration: none;">
                    <button class="btn-logout">Salir</button>
                </a>
            </li>
        </ul>
    </nav>
    <div class="dashboard-container">
        <div class="sidebar">
            <!-- Contenido del sidebar -->
            <h2>Sidebar</h2>
            <p>Aquí puedes mostrar diferentes opciones y enlaces.</p>
        </div>
        <div class="content1">
            <!-- Contenido del contenido 1 -->
            <h2>Contenido 1</h2>
            <p>Aquí puedes mostrar diferentes datos relevantes para el usuario.</p>
        </div>
        <div class="content2">
            <!-- Contenido del contenido 2 -->
            <h2>Contenido 2</h2>
            <p>Aquí puedes mostrar otros datos relevantes para el usuario.</p>
        </div>
        <footer class="footer">
            <!-- Contenido del footer -->
            <p>© 2023 Todos los derechos reservados.</p>
        </footer>
    </div>
</body>
</html>
