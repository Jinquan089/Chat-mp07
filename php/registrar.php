<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/login.css">
    <link href="https://fonts.googleapis.com/css?family=Nunito&display=swap" rel="stylesheet"> 
    <link href="https://fonts.googleapis.com/css?family=Overpass&display=swap" rel="stylesheet">
    <title>Registrar</title>
</head>
<body>
<div id="contenedor">
    <div id="central">
        <div id="login">
            <div class="titulo"> Registrar </div>
            <form action="./proc_registrar.php" method="post" id="loginform">
                <!-- Nombre real del usuario -->
                <input type="text" name="nomreal" id="nomreal" placeholder="Nombre real"><br>
                <!-- Nombre de usuario -->
                <input type="text" name="user" id="user" placeholder="Usuario"><br>
                <!-- Contraseña -->
                <input type="password" name="pwd_reg" id="pwd_reg" placeholder="Contraseña"><br>
                <!-- Confirmar Contraseña -->
                <input type="password" name="confirm_pwd" id="confirm_pwd" placeholder="Confirmar Contraseña"><br>
                    <?php
                    if (isset($_GET["error"])) {
                        if ($_GET['error'] == "pass") {
                            echo "<p class='errorlogin'> Las contraseñas no coinciden </p>";
                            } elseif ($_GET["error"] == "vacio") {
                                echo "<p class='errorlogin'> Campos vacios </p>";
                            } elseif ($_GET["error"] == "existe") {
                                echo "<p class='errorlogin'> El nombre de usuario ya esta usado </p>";
                            } else {
                                echo "<p class='errorlogin'> Intente de nuevo </p>";
                            }
                    }
                    ?>
                <!-- Botón de registro -->
                <button type="submit" title="Registrar" name="registrar" value="Registrarse">Registrar</button>
            </form>
            <div class="pie-form">
            <a href="../index.php">¿Ya eres miembro? Login</a>
            </div>
        </div>
    </div>
</div>
</body>
</html>
