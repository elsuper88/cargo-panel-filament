#!/bin/bash

# Script para configurar SSH en Termux y conectar con Cursor
# Autor: Generado automáticamente

set -e

echo "Iniciando configuracion de SSH en Termux para Cursor..."

# Colores para output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

# Funcion para imprimir mensajes con color
print_status() {
    echo -e "${BLUE}[INFO]${NC} $1"
}

print_success() {
    echo -e "${GREEN}[SUCCESS]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

# Verificar si estamos en Termux
if [[ ! -d "/data/data/com.termux" ]]; then
    print_error "Este script debe ejecutarse en Termux Android"
    exit 1
fi

print_status "Verificando paquetes necesarios..."

# Actualizar paquetes
print_status "Actualizando paquetes de Termux..."
pkg update -y

# Instalar paquetes necesarios
print_status "Instalando paquetes necesarios..."
pkg install -y openssh git curl wget

# Configurar SSH
print_status "Configurando SSH..."

# Crear directorio .ssh si no existe
mkdir -p ~/.ssh
chmod 700 ~/.ssh

# Generar clave SSH si no existe
if [[ ! -f ~/.ssh/id_rsa ]]; then
    print_status "Generando nueva clave SSH..."
    ssh-keygen -t rsa -b 4096 -f ~/.ssh/id_rsa -N ""
    print_success "Clave SSH generada exitosamente"
else
    print_warning "La clave SSH ya existe"
fi

# Configurar authorized_keys
if [[ ! -f ~/.ssh/authorized_keys ]]; then
    print_status "Configurando authorized_keys..."
    cp ~/.ssh/id_rsa.pub ~/.ssh/authorized_keys
    chmod 600 ~/.ssh/authorized_keys
    print_success "authorized_keys configurado"
fi

# Configurar sshd
print_status "Configurando servidor SSH..."
cat > ~/.ssh/sshd_config << EOF
Port 8022
PermitRootLogin no
PasswordAuthentication no
PubkeyAuthentication yes
AuthorizedKeysFile .ssh/authorized_keys
PrintMotd no
Subsystem sftp /data/data/com.termux/files/usr/libexec/sftp-server
EOF

# Iniciar servidor SSH
print_status "Iniciando servidor SSH en puerto 8022..."
sshd -D -p 8022 &
SSH_PID=$!

# Obtener IP del dispositivo
print_status "Obteniendo informacion de conexion..."
DEVICE_IP=$(ip route get 1.1.1.1 | awk '{print $7; exit}' 2>/dev/null || echo "No disponible")

# Mostrar informacion de conexion
echo ""
print_success "Servidor SSH iniciado exitosamente!"
echo ""
echo -e "${GREEN}INFORMACION DE CONEXION:${NC}"
echo -e "   Host: ${YELLOW}$DEVICE_IP${NC}"
echo -e "   Puerto: ${YELLOW}8022${NC}"
echo -e "   Usuario: ${YELLOW}$(whoami)${NC}"
echo ""
echo -e "${GREEN}CLAVE PUBLICA SSH:${NC}"
echo -e "${YELLOW}$(cat ~/.ssh/id_rsa.pub)${NC}"
echo ""
echo -e "${GREEN}COMANDO PARA CURSOR:${NC}"
echo -e "   ssh -p 8022 $(whoami)@$DEVICE_IP"
echo ""
echo -e "${GREEN}RUTA DEL PROYECTO:${NC}"
echo -e "   ${YELLOW}$(pwd)${NC}"
echo ""

# Funcion para manejar senales y limpiar procesos
cleanup() {
    print_status "Deteniendo servidor SSH..."
    kill $SSH_PID 2>/dev/null || true
    print_success "Servidor SSH detenido"
    exit 0
}

# Capturar senales para limpieza
trap cleanup SIGINT SIGTERM

print_warning "Presiona Ctrl+C para detener el servidor SSH"
print_status "El servidor SSH esta ejecutandose en segundo plano..."

# Mantener el script ejecutandose
while true; do
    sleep 30
    # Verificar si el proceso SSH sigue ejecutandose
    if ! kill -0 $SSH_PID 2>/dev/null; then
        print_error "El servidor SSH se detuvo inesperadamente"
        break
    fi
done
