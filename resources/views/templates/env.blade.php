APP_NAME=Machine {{ $machine->id }}
APP_ENV=production
APP_KEY={{ $key }}
APP_DEBUG=false
APP_URL="https://{{ $machine->id }}.machines.rustscp.net"
APP_TIMEZONE=UTC

MACHINE_TOKEN={{ $machine->token }}
SERVERS_BASE_PATH=/var/manager/servers

LOG_CHANNEL=stack
LOG_SLACK_WEBHOOK_URL=

DB_CONNECTION=sqlite
DB_DATABASE=/var/manager/web/database/db.sqlite

CACHE_DRIVER=redis
QUEUE_CONNECTION=redis
