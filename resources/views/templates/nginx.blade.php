server {
    listen 127.0.0.1:8228;

    root /var/manager/web/public;

    index index.html index.htm index.php;

    charset utf-8;

    client_max_body_size 1G;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php7.4-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}

server {
    listen 443;
    ssl on;
    ssl_certificate /var/manager/ssl/cert.pem;
    ssl_certificate_key /var/manager/ssl/privkey.pem;
    ssl_client_certificate /var/manager/ssl/originca.pem;
    ssl_verify_client on;
    server_name {{ $domain }};

    root /var/manager/web/public;

    index index.html index.htm index.php;

    charset utf-8;

    client_max_body_size 1G;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php7.4-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
