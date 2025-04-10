# OPR1

![image](https://github.com/user-attachments/assets/f27afc34-ce8c-443e-b2bb-7b7acc99b376)


![image](https://github.com/user-attachments/assets/a3a325ef-d56d-4c25-b3c3-81290c1041d0)

### WatchTower
Keep updated docker containers
```
docker run -d --restart always --name watchtower -e WATCHTOWER_CLEANUP:true -e WATCHTOWER_REMOVE_VOLUMES=true -v /var/run/docker.
sock:/var/run/docker.sock containrrr/watchtower
```

docker-compose.yml - This will search every 6 hours updates
```
version: '3'
services:
  watchtower:
    image: containrrr/watchtower
    container_name: watchtower
    restart: always
    volumes:
      - /var/run/docker.sock:/var/run/docker.sock
    command: --interval 21600
    environment:
      - WATCHTOWER_CLEANUP=true
      - WATCHTOWER_REMOVE_VOLUMES=true
```


### WOL
Wake on Lan web server. Is important make a `config.php` file before launch docker-compose.

automated script install
```
git clone https://github.com/koko004/OPR1 && \
cd OPR1 && mv WOL/ ~ && cd ~ && rm -rf OPR1 && cd WOL && \
rm -f Dockerfile && docker-compose up -d && \
```
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

```

docker-compose.yml
```
version: "3"

services:
  remote-wake-sleep-on-lan-armv7:
    image: koko004/remote-wake-sleep-on-lan-armv7:latest
    container_name: wol
    restart: always
    network_mode: host
    volumes:
      - ./config.php:/var/www/html/config.php
    environment:
      - PASSPHRASE=adminpassword
      - APACHE2_PORT=80
      - RWSOLS_COMPUTER_NAME=PC01,PC02
      - RWSOLS_COMPUTER_MAC="xx:xx:xx:xx:xx:xx","x:xx:xx:xx:xx:xx"
      - RWSOLS_COMPUTER_IP="192.168.1.150","192.168.1.151"
```
### WGDashboard
Wireguard dashboard for armv7

docker-compose.yml
```
services:
  wgdashboard:
    image: donaldzou/wgdashboard:latest
    restart: always
    container_name: wgdashboard
    environment:
      - tz=Europe/Madrid
      - global_dns=1.1.1.1
      - enable=OPR1
      #- isolate=
      #- public_ip=
    ports:
      - 10086:10086/tcp
      - 51820:51820/udp
    volumes:
      - conf:/etc/wireguard
      - data:/data
    cap_add:
      - NET_ADMIN

volumes:
  conf:
  data:
```

### cron task

This will restart every 3 days and search for updates and make upgrade every 24 hours at 12:00 PM
```
0 0 */3 * * /usr/bin/apt update && /usr/bin/apt upgrade -y
0 0 * * * /sbin/reboot
```
