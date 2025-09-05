# NativePHP Mobile - Instrucciones

## Instalación

```bash
# Agregar repositorio privado
composer config repositories.nativephp composer https://nativephp.composer.sh

# Autenticar con licencia
composer config http-basic.nativephp.composer.sh raulynrpr@gmail.com 0869600b-8091-4ce3-ae8b-2c50f6077c54

# Instalar NativePHP Mobile
composer require nativephp/mobile

# Instalar dependencias
php artisan native:install both --with-icu --force
```

## Configuración del Keystore

```bash
# Generar keystore
keytool -genkey -v -keystore cargo-panel.keystore -alias cargo-panel -keyalg RSA -keysize 2048 -validity 10000

# Contraseña: cargopanel123
```

## Variables de Entorno

```env
# .env
NATIVEPHP_APP_ID=com.cargopanel.app
NATIVEPHP_APP_VERSION="1.0.0"
NATIVEPHP_APP_VERSION_CODE="2"
NATIVEPHP_APP_NAME="Cargo Panel"
```

## Limpiar Cache

```bash
# Limpiar cache de Laravel
php artisan config:clear
php artisan cache:clear
php artisan view:clear

# Limpiar cache de Gradle
rm -rf nativephp/android/.gradle nativephp/android/app/build

# Limpiar cache de Composer
composer dump-autoload
```

## Generar APK

```bash
# Variables de entorno necesarias
export ANDROID_HOME=~/Library/Android/sdk
export PATH=$PATH:$ANDROID_HOME/platform-tools
export JAVA_HOME=/opt/homebrew/opt/openjdk@17
export GRADLE_OPTS="-Xmx8192m"

# Generar APK de release
php artisan native:run android --build=release --no-tty
```

## Activar Shrink y Minified

### config/nativephp.php
```php
'android' => [
    'build' => [
        'minify_enabled' => true,
        'shrink_resources' => true,
        'obfuscate' => true,
        'debug_symbols' => 'NONE',
        'keep_line_numbers' => false,
    ],
],
```

### nativephp/android/app/build.gradle.kts
```kotlin
buildTypes {
    release {
        isMinifyEnabled = true
        isShrinkResources = true
        proguardFiles(
            getDefaultProguardFile("proguard-android-optimize.txt"),
            "proguard-rules.pro"
        )
        ndk {
            debugSymbolLevel = "NONE"
        }
    }
}
```

## Seeding de Datos

### Crear Seeder
```bash
php artisan make:seeder InitialDataSeeder
```

### Contenido del Seeder (database/seeders/InitialDataSeeder.php)
```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class InitialDataSeeder extends Seeder
{
    public function run(): void
    {
        // Crear usuario admin
        DB::table('users')->insert([
            'name' => 'Admin',
            'email' => 'admin@cargopanel.com',
            'password' => Hash::make('password'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Crear contactos de ejemplo
        DB::table('contactos')->insert([
            [
                'nombre' => 'Juan Pérez',
                'apellido' => 'García',
                'email' => 'juan@example.com',
                'telefono' => '+1234567890',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'María López',
                'apellido' => 'Rodríguez',
                'email' => 'maria@example.com',
                'telefono' => '+0987654321',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
```

### Ejecutar Seeding
```bash
php artisan db:seed --class=InitialDataSeeder
```

### Auto-Seeding en AppServiceProvider
```php
// app/Providers/AppServiceProvider.php
public function boot(): void
{
    if (DB::table('users')->count() === 0) {
        Artisan::call('db:seed', ['--class' => 'InitialDataSeeder']);
    }
}
```

## Verificar Firma

```bash
cd nativephp/android
./gradlew signingReport
```

## Ubicación del APK

```
nativephp/android/app/build/outputs/apk/release/app-release.apk
```
