#!/bin/bash

echo "🔨 Generando APK optimizado para Cargo Panel..."

# Limpiar builds anteriores
php artisan native:reset

# Configurar variables de entorno para optimización
export NATIVEPHP_OPTIMIZE=true
export NATIVEPHP_MINIFY=true

# Generar APK optimizado
echo "📱 Generando APK con configuración optimizada..."
php artisan native:run <<EOF
android
release
EOF

echo "✅ APK generado exitosamente!"
echo "📁 Ubicación: nativephp/android/app/build/outputs/apk/release/"
echo "📊 Tamaño esperado: ~80MB"
