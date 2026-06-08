<?php

session_start();
session_destroy();

header("Location: views/usuario/login.php");
exit;