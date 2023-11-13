<?php
// Inicia la sesión (asegúrate de hacerlo en cada archivo donde necesites usar sesiones).
session_start();
// Destruir todas las variables de sesion
session_unset();
// Cierra la sesión.
session_destroy();
// Redirige al usuario a la página de inicio de sesión u otra página deseada.
header("Location: ../../index.php");
exit;