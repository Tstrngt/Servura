#!/bin/bash

set -euo pipefail
umask 077

BACKUP_DIR="/var/backups/mysql"
DB_NAME="servura"
DB_USER="servura"
DB_PASSWORD=$(cut -d'=' -f2 /etc/servura/db_credentials)
DATE=$(date +%Y%m%d_%H%M%S)
BACKUP_FILE="$BACKUP_DIR/servura_backup_$DATE.sql.gz"
TEMP_BACKUP_FILE=$(mktemp "$BACKUP_DIR/.servura_backup_${DATE}_XXXXXX.sql.gz")

trap 'rm -f "$TEMP_BACKUP_FILE"' EXIT

MYSQL_PWD="$DB_PASSWORD" mysqldump --no-tablespaces --single-transaction --routines --events --triggers -u"$DB_USER" "$DB_NAME" | gzip > "$TEMP_BACKUP_FILE"
gzip -t "$TEMP_BACKUP_FILE"
mv "$TEMP_BACKUP_FILE" "$BACKUP_FILE"
trap - EXIT

find "$BACKUP_DIR" -name "servura_backup_*.sql.gz" -mtime +14 -delete
chmod 600 "$BACKUP_FILE"
chown root:root "$BACKUP_FILE"

echo "Backup completed: $BACKUP_FILE"
