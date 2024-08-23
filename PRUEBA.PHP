<?php
// Inicia la sesión si no está iniciada
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Procesa el formulario (por ejemplo, guarda los datos en la base de datos)
  ;

    // Desactiva la caché para asegurarse de que la redirección no se almacene
    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");

    // Redirige al usuario a una página de agradecimiento o a la misma página
     // O header("Location: form.html");
    
}