#!/bin/bash

sudo apt-get update
sudo apt-get upgrade -y

# Add PHP repo
sudo apt-get install software-properties-common -y
sudo add-apt-repository ppa:ondrej/php -y
sudo apt-get update

# Install PHP7.4 && NGINX && Redis
sudo apt-get install systemd -y
sudo apt-get install nginx redis-server -y

sudo DEBIAN_FRONTEND=noninteractive apt-get install unzip php7.4-fpm php7.4-common php7.4-bcmath openssl php7.4-json php7.4-xml php7.4-mbstring php7.4-zip php7.4-redis -y

systemctl enable nginx php7.4-fpm
systemctl start nginx php-7.4-fpm

sudo DEBIAN_FRONTEND=noninteractive apt-get install ufw -y

sudo ufw allow 22
sudo ufw allow 443

# Install git & composer
sudo apt-get install curl git -y
sudo curl -s https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
sudo chmod +x /usr/local/bin/composer

# Create manager dirs
mkdir -p /var/manager
mkdir -p /var/manager/{ssl,servers}

# Create user
useradd -G sudo,www-data -d /var/manager manager

# Add certs
touch /var/manager/ssl/{cert.pem,privkey.pem,originca.pem}
@foreach ( $ssl as $key => $pem )
    echo "{{ $pem }}" >> /var/manager/ssl/{{ $key }}.pem
@endforeach

# Download manager
cd /var/manager
git clone https://gitlab.domoratskiy.net/RustSCP/ServerManager.git web
cd /var/manager/web

touch .env
echo "{{ $env_data }}" >> .env
touch database/db.sqlite

composer install
php artisan migrate

chmod -R 755 /var/manager/web
chmod -R 777 /var/manager/web/{bootstrap,storage}
chmod -R 777 /var/manager/web/database/db.sqlite

# Create Nginx conf
touch /etc/nginx/sites-available/manager
echo '{{ $nginx_conf }}' >> /etc/nginx/sites-available/manager

ln -s /etc/nginx/sites-available/manager /etc/nginx/sites-enabled/manager
service nginx restart && service php7.4-fpm restart

# Give rights to manager
chown -R manager:www-data /var/manager

# Install supervisor for queues
sudo apt-get install supervisor -y
touch /etc/supervisor/conf.d/manager-worker.conf
echo '{{ $supervisor_conf }}' >> /etc/supervisor/conf.d/manager-worker.conf

sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start laravel-worker:*


# Install Rust Dependencies
echo -e "\n\n2\n1" | sudo dpkg --add-architecture i386; sudo apt update; sudo apt install mailutils curl wget file tar bzip2 gzip unzip bsdmainutils python util-linux ca-certificates binutils bc jq tmux lib32gcc1 libstdc++6 lib32stdc++6 steamcmd lib32z1 -y