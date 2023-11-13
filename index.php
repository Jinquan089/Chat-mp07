<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/login.css">
    <link href="https://fonts.googleapis.com/css?family=Nunito&display=swap" rel="stylesheet"> 
    <link href="https://fonts.googleapis.com/css?family=Overpass&display=swap" rel="stylesheet">
    <title>Login</title>
</head>
<body>
<div id="contenedor">
    <div id="central">
        <div id="login">
            <div class="titulo"> Bienvenido </div>
            <form action="./php/login.php" method="post" id="loginform">
                <input type="text" name="user" id="user" placeholder="Usuario" required/>
                <input type="password" name="pwd" id="pwd" placeholder="Contraseña" required/>
                <?php if (isset($_GET['fallo'])) {
                echo "<p class='errorlogin'> Usuario o Contraseña incorrecta </p>";
                }?>
                <!-- Botón de enviar -->
                <button type="submit" title="Login"name="login" value="Login" id="loginBtn">Login</button>
            </form>
            <!-- Register button -->
            <div class="pie-form">
            <a href="./php/registrar.php"> ¿No eres miembro? Regístrate</a>
            </div>
        </div>
    </div>
</div>
</body>
</html>