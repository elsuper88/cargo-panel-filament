<?php

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\File;

echo "🔨 Generando APK ultra-optimizado para Cargo Panel...\n";

// Configuración de optimización
$config = require 'nativephp-optimized-config.php';

// Limpiar build anterior
if (File::exists('nativephp')) {
    File::deleteDirectory('nativephp');
    echo "🧹 Build anterior eliminado\n";
}

// Crear directorio temporal para el bundle optimizado
$tempDir = 'temp-laravel-bundle';
if (File::exists($tempDir)) {
    File::deleteDirectory($tempDir);
}
File::makeDirectory($tempDir);

echo "📦 Creando bundle Laravel optimizado...\n";

// Solo copiar archivos esenciales
$essentialFiles = $config['mobile_optimization']['include_only'];
foreach ($essentialFiles as $file) {
    $source = $file;
    $destination = $tempDir . '/' . $file;
    
    if (File::isDirectory($source)) {
        File::copyDirectory($source, $destination);
    } elseif (File::exists($source)) {
        File::makeDirectory(dirname($destination), 0755, true, true);
        File::copy($source, $destination);
    }
}

// Excluir paquetes de vendor innecesarios
$excludePackages = $config['mobile_optimization']['exclude_vendor_packages'];
foreach ($excludePackages as $package) {
    $packagePath = $tempDir . '/vendor/' . $package;
    if (File::exists($packagePath)) {
        File::deleteDirectory($packagePath);
        echo "🗑️ Excluido: vendor/$package\n";
    }
}

// Crear ZIP del bundle optimizado
$zipPath = 'laravel-optimized-bundle.zip';
if (File::exists($zipPath)) {
    File::delete($zipPath);
}

$zip = new ZipArchive();
if ($zip->open($zipPath, ZipArchive::CREATE) === TRUE) {
    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($tempDir),
        RecursiveIteratorIterator::LEAVES_ONLY
    );
    
    foreach ($iterator as $file) {
        if (!$file->isDir()) {
            $filePath = $file->getRealPath();
            $relativePath = substr($filePath, strlen($tempDir) + 1);
            $zip->addFile($filePath, $relativePath);
        }
    }
    $zip->close();
    
    $size = File::size($zipPath);
    $sizeMB = round($size / 1024 / 1024, 2);
    echo "✅ Bundle optimizado creado: $sizeMB MB\n";
} else {
    echo "❌ Error creando ZIP\n";
    exit(1);
}

// Limpiar directorio temporal
File::deleteDirectory($tempDir);

echo "🎯 Bundle optimizado listo para usar en NativePHP\n";
echo "📁 Archivo: $zipPath\n";
echo "📊 Tamaño: $sizeMB MB (vs 1021MB original)\n";
echo "📱 Ahora puedes usar: php artisan native:package android --keystore=cargo-panel.keystore --keystore-password=cargopanel123 --key-alias=cargo-panel --key-password=cargopanel123 --build-type=release --output=./ --rebuild\n";
