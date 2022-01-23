#!/bin/bash

# @author: Mihai Iamandei, Teodor-Adrian Mirea
# @original: RootDev4 https://github.com/RootDev4/poodle-PoC

# Installing nginx and php for deploying the server.
sudo ls > /dev/null
echo "[>] Installing nginx and php ..."
sudo apt-get install nginx php5-fpm -y > /dev/null

if [ $? -ne 0 ]; then
    echo "[!] Error occurred while installing nginx and php"
    exit 1
else
    echo "[v] OK"
fi

# Generating SSL certificate
echo "[>] Generating SSL certificate ..."

sudo mkdir -p /etc/nginx/ssl
cd /etc/nginx/ssl

if [[ -f "server.key" && -f "server.crt" ]]; then
    echo "[v] OK"
else
    sudo openssl req -new -newkey rsa:4096 -days 365 -nodes -x509 -subj "/C=RO/ST=B/L=Bucharest/O=BCR/CN=bcr.ro" -keyout server.key -out server.crt > /dev/null 2>&1

    if [ $? -ne 0 ]; then
        echo "[!] Error while generating SSL certificate"
        exit 1
    else
        echo "[v] OK"
    fi
fi

# Adding HTTPS server in nginx
echo "[>] Adding HTTPS support to nginx configuration ..."
cd - > /dev/null

cat <<EOF > temp_nginx_config
# HTTPS server
server {
    listen 443;
    server_name bcr.ro;
    root /usr/share/nginx/www;
    index index.php;

    ssl on;
    ssl_certificate /etc/nginx/ssl/server.crt;
    ssl_certificate_key /etc/nginx/ssl/server.key;
    ssl_session_timeout 5m;
    ssl_protocols SSLv3 TLSv1 TLSv1.1 TLSv1.2;
    ssl_ciphers DES-CBC3-SHA;
    ssl_prefer_server_ciphers on;

    location ~ \.php$ {
        fastcgi_split_path_info ^(.+\.php)(/.+)$;
        fastcgi_pass 127.0.0.1:9000;
        #fastcgi_pass unix:/var/run/php5-fpm.sock;
        fastcgi_index index.php;
        include fastcgi_params;
    }

    location / {
        try_files \$uri \$uri/ /index.php;
        add_header 'Access-Control-Allow-Origin' '*';
        add_header 'Access-Control-Allow-Methods' 'GET, POST, OPTIONS';
        add_header 'Access-Control-Allow-Credentials' 'true';
    }
}
EOF

sudo cp temp_nginx_config /etc/nginx/sites-enabled/bcr.ro

echo "[v] OK"

# Uploading website in the nginx server
echo "[>] Uploading website in the nginx server ..."
sudo cp index.php /usr/share/nginx/www/index.php
sudo mkdir -p /usr/share/nginx/www/images
sudo cp -R images/* /usr/share/nginx/www/images

echo "[v] OK"

# Restarting nginx service
echo "[>] Restarting nginx server ..."
sudo service nginx restart > /dev/null

if [ $? -ne 0 ]; then
    echo "[!] Error while restarting nginx service"
    exit 1
else
    echo "[v] OK"
fi

echo "[v] Initialization finished successfully!"
