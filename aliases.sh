if [ -f .env ]; then
  set -a
  source .env 2>/dev/null || {
    export $(grep -v '^#' .env | grep -v '^$' | grep -v '^[[:space:]]*$' | xargs)
  }
  set +a
fi

# IMPORTANT: Les noms de services dans docker-compose.yml correspondent aux noms de conteneurs
# configurés lors de l'installation. Les aliases utilisent ces noms de services.

# Alias utilisant les noms de services (configurés lors de l'installation)
alias ccomposer='docker compose exec apache_v2 composer'
alias capache='docker compose exec -it apache_v2 bash'
alias cmariadb='docker compose exec -it mariadb_v2 bash'
alias db-export='docker compose exec mariadb_v2 /docker-entrypoint-initdb.d/backup.sh'
alias db-import='docker compose exec mariadb_v2 /docker-entrypoint-initdb.d/restore.sh'