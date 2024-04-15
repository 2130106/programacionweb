<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Usuarios</title>
    <style>
        /* Colores */
        :root {
            --color1: #6F5F90; /* Fondo oscuro */
            --color2: #597e7b; /* Fondo oscuro */
            --color3: #FF7889; /* Color claro */
            --color4: #002b56; /* Color claro */
            --color5: #A5CAD2; /* Color claro */
            --color6:  #FFFFFFFF;
        }

        /* Degradado de colores */
        body {
            background: linear-gradient(to bottom right, var(--color1), var(--color2));
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        /* Contenedor principal */
        .container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: 100vh;
        }

        /* Columna izquierda (Logo) */
        .left-column {
            flex: 1;
            padding: 20px;
            display: flex;
            justify-content: flex-end;
            align-items: center;
        }

        .logo-container img {
            max-width: 800px;
        }

        /* Columna derecha (Registro) */
        .right-column {
            flex: 1;
            padding: 30px;
            color: var(--color6); /* Color claro */
            background: linear-gradient(to bottom right, var(--color3), var(--color4)); /* Degradado para el contenedor del registro */
            border-radius: 30px; /* Añade bordes redondeados en las esquinas superior izquierda y inferior izquierda */
            box-shadow: 0px 0px 20px rgb(183, 71, 59);
            margin-left: 140px;
            margin-right: 80px;
        }

        /* Contenedor de registro */
        .registro-container {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
        }

        .card {
            width: 400px;
        }

        /* Formulario */
        form {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        input[type="text"],
        input[type="password"],
        input[type="email"],
        input[type="date"] {
            padding: 10px;
            border-radius: 5px;
            border: none;
            outline: none;
            background-color: var(--color4); /* Color claro */
        }

        input[type="button"],
        input[type="submit"] {
            padding: 10px;
            border-radius: 5px;
            border: none;
            outline: none;
            cursor: pointer;
            background-color: var(--color3); /* Color claro */
            color: white;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        input[type="button"]:hover,
        input[type="submit"]:hover {
            background-color: var(--color3); /* Color claro */
        }

        .registro-container {
            display: flex;
            flex-direction: column;
            align-items: center; /* Centra horizontalmente */
            text-align: center; /* Centra verticalmente */
        }

        .registro-container h2 {
            font-size: 40px; /* Cambia el tamaño del texto para los títulos (h2) */
        }

    </style>
</head>
<body>

<div class="container">
    <div class="left-column">
        <div class="logo-container">
            <img src="css/img/logo2.jpg">
        </div>
    </div>

    <div class="right-column">
        <div class="registro-container">
            <h2>Registro de Usuarios</h2>
            <div class="card">
                <h5>Regístrese para acceder al File Manager</h5>
                <p>Debe registrarse para poder crear una cuenta</p>
                <div class="form-container">
                    <form action="php/registro.php" method="post">
                        <label for="name">Nombre:</label>
                        <input type="text" name="name" id="name" required>
                        <label for="apellidos">Apellidos:</label>
                        <input type="text" name="apellidos" id="apellidos" required>
                        <label for="username">Nombre de Usuario:</label>
                        <input type="text" name="username" id="username" required placeholder="example81">
                        <label for="correo">Correo Electrónico:</label>
                        <input type="email" name="correo" id="correo" required placeholder="usuario@example.com">
                        <label for="password">Contraseña:</label>
                        <input type="password" name="password" id="password" required>
                        <label for="confirm_password">Confirmar Contraseña:</label>
                        <input type="password" name="confirm_password" id="confirm_password" required>
                        <label for="genero">Género:</label>
                        <select name="genero" id="genero" required>
                            <option value="M">Masculino</option>
                            <option value="F">Femenino</option>
                            <option value="X">Prefiero no especificar</option>
                        </select>
                        <label for="fecha_nacimiento">Fecha de Nacimiento:</label>
                        <input type="date" name="fecha_nacimiento" id="fecha_nacimiento" required>
                        <div class="button-container">
                            <input type="submit" value="Crear cuenta">
                            <input type="button" value="¡Inicia sesión!" onclick="location.href='index.html';">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
