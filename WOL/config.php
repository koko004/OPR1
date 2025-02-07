<?php
// Configuración de la contraseña
$PASSPHRASE = getenv('PASSPHRASE') ?: 'admpassw'; // Usa la variable de entorno o un valor por defecto
$APPROVED_HASH = password_hash($PASSPHRASE, PASSWORD_DEFAULT); // Genera el hash de la contraseña

// Otras configuraciones
$USE_HTTPS = false; // Cambia a true si usas HTTPS
$BOOTSTRAP_LOCATION_PREFIX = ''; // Ruta para los archivos de Bootstrap
$DEBUG = false; // Habilita o deshabilita el modo de depuración

// Configuración de las computadoras
$COMPUTER_NAME = explode(',', getenv('RWSOLS_COMPUTER_NAME') ?: 'Pc1,Pc2');
$COMPUTER_MAC = explode(',', getenv('RWSOLS_COMPUTER_MAC') ?: 'XX:XX:XX:XX:XX:XX,XX:XX:XX:XX:XX:XX');
$COMPUTER_LOCAL_IP = explode(',', getenv('RWSOLS_COMPUTER_IP') ?: '192.168.1.45,192.168.1.50');
$COMPUTER_SLEEP_CMD_PORT = getenv('RWSOLS_SLEEP_PORT') ?: 7760;
$COMPUTER_SLEEP_CMD = getenv('RWSOLS_SLEEP_CMD') ?: 'suspend';

// Configuración de ping
$MAX_PINGS = getenv('RWSOLS_MAX_PINGS') ?: 15;
$SLEEP_TIME = getenv('RWSOLS_SLEEP_TIME') ?: 5;
?>
