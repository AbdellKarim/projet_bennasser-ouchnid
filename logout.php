<?php
session_start();
session_destroy();
header('Location: ./login.html'); // Rediriger vers la page de connexion
exit();
?>