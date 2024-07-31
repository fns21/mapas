#!/bin/sh
set -eu

# Função para verificar se um comando existe
command_exists() {
    command -v "$1" >/dev/null 2>&1
}

# Verifica se os argumentos são válidos
if [ "$#" -gt 0 ] && ([ "$1" = "frankenphp" ] || [ "$1" = "php" ] || [ "$1" = "app/bin/doctrine" ]); then
    
    # Verifica se o arquivo composer.json existe
    if [ ! -f "composer.json" ]; then
        if grep -q "^DATABASE_URL=" ".env"; then
            echo "Para finalizar a instalação, pressione Ctrl+C para parar o Docker Compose e execute: docker compose up --build -d --wait"
            sleep infinity
        fi
    fi

    # Verifica se o diretório vendor está vazio
    if [ ! -d "vendor" ] || [ -z "$(ls -A 'vendor/' 2>/dev/null)" ]; then
        if command_exists composer; then
            composer install --prefer-dist --no-progress --no-interaction
        else
            echo "O Composer não está instalado. Certifique-se de que ele esteja disponível no PATH."
            exit 1
        fi
    fi

    # Verifica a variável de ambiente DATABASE_URL
    if grep -q "^DATABASE_URL=" ".env"; then
        echo "Esperando pelo banco de dados estar pronto..."
        ATTEMPTS_LEFT_TO_REACH_DATABASE=60

        until [ $ATTEMPTS_LEFT_TO_REACH_DATABASE -eq 0 ] || DATABASE_ERROR=$(php app/bin/doctrine dbal:run-sql -q "SELECT 1" 2>&1); do
            if [ $? -eq 255 ]; then
                # Se o comando Doctrine sair com 255, ocorreu um erro irrecuperável
                ATTEMPTS_LEFT_TO_REACH_DATABASE=0
                break
            fi
            sleep 1
            ATTEMPTS_LEFT_TO_REACH_DATABASE=$((ATTEMPTS_LEFT_TO_REACH_DATABASE - 1))
            echo "Ainda esperando pelo banco de dados... Ou talvez o banco de dados não esteja acessível. Restam $ATTEMPTS_LEFT_TO_REACH_DATABASE tentativas."
        done

        if [ $ATTEMPTS_LEFT_TO_REACH_DATABASE -eq 0 ]; then
            echo "O banco de dados não está disponível ou inacessível:"
            echo "$DATABASE_ERROR"
            exit 1
        else
            echo "O banco de dados agora está pronto e acessível."
        fi

        # TABLE_EXISTS=$(php app/bin/doctrine dbal:run-sql "SELECT to_regclass('public.system_role') IS NOT NULL;" 2>&1)

        # if echo "$TABLE_EXISTS" | grep -q "system_role"; then
        #     echo 'A tabela "system_role" já existe. Nenhuma ação necessária.'
        # else
        #     # Executa o arquivo SQL se a tabela não existir
        #     SQL_FILE="/app/dev/db/dump.sql"
        #     if [ -f "$SQL_FILE" ]; then
        #         SQL_COMMAND=$(cat "$SQL_FILE")
		# 		echo "$SQL_COMMAND"
        #         EXECUTE_RESULT=$(php app/bin/doctrine dbal:run-sql "$SQL_COMMAND" 2>&1)
		# 		echo "depois do if doctrine restore dump"
        #         exit_code2=$?
        #         if [ $exit_code2 -eq 0 ]; then
        #             echo 'Arquivo SQL executado com sucesso.'
        #         else
        #             echo "Erro ao executar o arquivo SQL: $EXECUTE_RESULT"
        #             exit $exit_code2
        #         fi
        #     else
        #         echo "Arquivo SQL não encontrado em $SQL_FILE."
        #         exit 1
        #     fi
        # fi

        # Se necessário, pode ser descomentado para executar migrações
        if [ "$( find ./app/migrations -iname '*.php' -print -quit )" ]; then
        	php app/bin/doctrine migrations:migrate --no-interaction --all-or-nothing
        fi
    fi

    # Configura permissões para o diretório var
    setfacl -R -m u:www-data:rwX -m u:"$(whoami)":rwX var
    setfacl -dR -m u:www-data:rwX -m u:"$(whoami)":rwX var
fi

# Executa o entrypoint do Docker PHP
exec docker-php-entrypoint "$@"