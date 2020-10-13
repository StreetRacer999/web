[program:manager-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/manager/web/artisan queue:work redis --sleep=3 --tries=3
autostart=true
autorestart=true
user=manager
numprocs=4
redirect_stderr=true
stdout_logfile=/var/manager/web/storage/logs/worker.log
stopwaitsecs=3600
