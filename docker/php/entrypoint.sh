#!/bin/bash

# Este script garante que a extensão ddtrace seja carregada
# e que os processos do APM/Tracer recebam as variáveis de ambiente necessárias,
# especialmente em ambientes como Swoole ou PHP-FPM.

# 1. Configurações de Debug (Opcional)
echo "--- Datadog Tracer Entrypoint Init ---"
echo "DD_AGENT_HOST: $DD_AGENT_HOST"
echo "DD_TRACE_AGENT_PORT: $DD_TRACE_AGENT_PORT"
echo "DD_SERVICE: $DD_SERVICE"
echo "--------------------------------------"

# 2. Executa o comando principal do container
# O "exec" é crucial para que o comando principal (como 'php artisan octane:start')
# substitua o processo do script, garantindo que os sinais de encerramento
# do Docker sejam passados corretamente.
exec "$@"
