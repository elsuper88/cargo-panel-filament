#!/bin/bash

# Script para transferir el proyecto FilamentPHP v3 al servidor remoto
# Configuración
REMOTE_HOST="66.228.59.61"
REMOTE_USER="cargopanel-root"
REMOTE_PASS="Cargopanel8426"
REMOTE_DIR="/var/www/filamentphpv4"
LOCAL_PROJECT_DIR="Filamentphpv3"

echo "🚀 Iniciando transferencia del proyecto al servidor remoto..."
echo "📍 Servidor: $REMOTE_USER@$REMOTE_HOST"
echo "📁 Directorio destino: $REMOTE_DIR"

# Función para ejecutar comandos remotos
execute_remote() {
    local command="$1"
    sshpass -p "$REMOTE_PASS" ssh -o StrictHostKeyChecking=no "$REMOTE_USER@$REMOTE_HOST" "$command"
}

# Función para transferir archivos
transfer_files() {
    echo "📤 Transfiriendo archivos del proyecto..."
    rsync -avz --progress \
        --exclude=node_modules \
        --exclude=vendor \
        --exclude=.git \
        --exclude=storage/logs \
        --exclude=storage/framework/cache \
        --exclude=storage/framework/sessions \
        --exclude=storage/framework/views \
        --exclude=storage/app/private \
        --exclude=storage/app/public \
        --exclude=.DS_Store \
        -e "sshpass -p $REMOTE_PASS ssh -o StrictHostKeyChecking=no" \
        "$LOCAL_PROJECT_DIR/" "$REMOTE_USER@$REMOTE_HOST:$REMOTE_DIR/"
}

# Verificar conexión
echo "🔍 Verificando conexión al servidor..."
if execute_remote "echo 'Conexión exitosa'"; then
    echo "✅ Conexión establecida correctamente"
else
    echo "❌ Error al conectar al servidor"
    echo "Verificar:"
    echo "  - Usuario: $REMOTE_USER"
    echo "  - Contraseña: $REMOTE_PASS"
    echo "  - Host: $REMOTE_HOST"
    exit 1
fi

# Limpiar directorio remoto
echo "🧹 Limpiando directorio remoto..."
if execute_remote "sudo rm -rf $REMOTE_DIR/* && sudo mkdir -p $REMOTE_DIR && sudo chown -R $REMOTE_USER:$REMOTE_USER $REMOTE_DIR"; then
    echo "✅ Directorio remoto limpiado y configurado"
else
    echo "⚠️  No se pudo limpiar con sudo, intentando sin privilegios..."
    if execute_remote "rm -rf $REMOTE_DIR/* && mkdir -p $REMOTE_DIR"; then
        echo "✅ Directorio remoto limpiado (sin sudo)"
    else
        echo "❌ Error al limpiar directorio remoto"
        exit 1
    fi
fi

# Transferir archivos
echo "📤 Iniciando transferencia de archivos..."
if transfer_files; then
    echo "✅ Transferencia completada exitosamente"
else
    echo "❌ Error durante la transferencia"
    exit 1
fi

# Verificar archivos transferidos
echo "🔍 Verificando archivos transferidos..."
execute_remote "ls -la $REMOTE_DIR/"

echo "🎉 ¡Transferencia completada!"
echo "📍 Proyecto disponible en: $REMOTE_DIR"
echo "🔧 Próximos pasos:"
echo "  1. Instalar dependencias: composer install"
echo "  2. Configurar permisos: chmod -R 755 storage bootstrap/cache"
echo "  3. Configurar archivo .env"
echo "  4. Ejecutar migraciones: php artisan migrate"
