# Cargo Panel - Sistema de Gestión de Contactos

Un sistema completo de gestión de contactos desarrollado con **Laravel 12**, **Filament PHP v3**, **NativePHP Mobile** y **Record Finder Pro**.

## 🚀 Características

- **📱 Aplicación Móvil**: Construida con NativePHP para Android e iOS
- **🎨 Panel de Administración**: Interfaz moderna con Filament PHP v3
- **🔍 Record Finder Pro**: Búsqueda avanzada de registros con filtros
- **🔄 Sincronización API**: Integración con API externa para sincronización de datos
- **💾 Base de Datos SQLite**: Base de datos local para aplicaciones móviles
- **🎯 Gestión de Contactos**: CRUD completo con relaciones y referencias

## 🛠️ Tecnologías Utilizadas

- **Laravel 12** - Framework PHP
- **Filament PHP v3** - Panel de administración
- **NativePHP Mobile** - Aplicaciones móviles nativas
- **Record Finder Pro** - Plugin de búsqueda avanzada
- **SQLite** - Base de datos
- **Tailwind CSS** - Estilos
- **Vite** - Build tool

## 📦 Instalación

### Requisitos Previos

- PHP 8.2+
- Composer
- Node.js y npm
- Android Studio (para builds de Android)
- Xcode (para builds de iOS)

### Pasos de Instalación

1. **Clonar el repositorio**
   ```bash
   git clone https://github.com/elsuper88/cargo-panel-filament.git
   cd cargo-panel-filament
   ```

2. **Instalar dependencias PHP**
   ```bash
   composer install
   ```

3. **Instalar dependencias Node.js**
   ```bash
   npm install
   ```

4. **Configurar variables de entorno**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Configurar base de datos**
   ```bash
   touch database/database.sqlite
   php artisan migrate
   php artisan db:seed
   ```

6. **Compilar assets**
   ```bash
   npm run build
   ```

## 🚀 Uso

### Panel Web

1. **Iniciar servidor de desarrollo**
   ```bash
   php artisan serve
   ```

2. **Acceder al panel**
   - URL: `http://localhost:8000`
   - Usuario por defecto: `admin@example.com`
   - Contraseña: `password`

### Aplicación Móvil

1. **Configurar NativePHP**
   ```bash
   php artisan native:install
   ```

2. **Ejecutar en Android**
   ```bash
   php artisan native:android
   ```

3. **Ejecutar en iOS**
   ```bash
   php artisan native:ios
   ```

## 📱 Características de la Aplicación

### Gestión de Contactos
- ✅ Crear, editar y eliminar contactos
- ✅ Búsqueda y filtrado avanzado
- ✅ Referencias entre contactos
- ✅ Sincronización con API externa
- ✅ Campos personalizados (empresa, dirección, notas)

### Record Finder Pro
- 🔍 Búsqueda en tiempo real
- 🏷️ Filtros por empresa
- 📊 Vista de tabla personalizable
- 🎨 Diseño como badges
- 📱 Modal responsive

### Sincronización
- 🔄 Sincronización bidireccional con API
- 📡 Operaciones CRUD remotas
- 🔐 Autenticación con tokens
- 📝 Logs detallados de sincronización

## 🗂️ Estructura del Proyecto

```
├── app/
│   ├── Filament/Resources/     # Recursos de Filament
│   ├── Models/                 # Modelos Eloquent
│   ├── Jobs/                   # Jobs de sincronización
│   └── Console/Commands/       # Comandos Artisan
├── database/
│   ├── migrations/             # Migraciones de base de datos
│   └── seeders/               # Seeders de datos
├── android/                   # Configuración Android
├── nativephp/                 # Configuración NativePHP
└── resources/
    ├── views/                 # Vistas Blade
    └── js/                    # Assets JavaScript
```

## 🔧 Comandos Útiles

### Desarrollo
```bash
# Servidor de desarrollo
php artisan serve

# Compilar assets
npm run dev

# Ejecutar tests
php artisan test
```

### Base de Datos
```bash
# Ejecutar migraciones
php artisan migrate

# Poblar base de datos
php artisan db:seed

# Reset completo
php artisan migrate:fresh --seed
```

### Sincronización
```bash
# Sincronizar contactos
php artisan sync:contactos

# Probar conexión API
php artisan test:api
```

### Móvil
```bash
# Instalar NativePHP
php artisan native:install

# Build Android
php artisan native:android

# Build iOS
php artisan native:ios
```

## 📄 Licencia

Este proyecto está bajo la Licencia MIT. Ver el archivo [LICENSE](LICENSE) para más detalles.

## 🤝 Contribuciones

Las contribuciones son bienvenidas. Por favor:

1. Fork el proyecto
2. Crea una rama para tu feature (`git checkout -b feature/AmazingFeature`)
3. Commit tus cambios (`git commit -m 'Add some AmazingFeature'`)
4. Push a la rama (`git push origin feature/AmazingFeature`)
5. Abre un Pull Request

## 📞 Soporte

Si tienes preguntas o necesitas ayuda:

- 📧 Email: raulynrpr@gmail.com
- 🐛 Issues: [GitHub Issues](https://github.com/elsuper88/cargo-panel-filament/issues)
- 📖 Documentación: [Filament PHP](https://filamentphp.com/)

## 🙏 Agradecimientos

- [Filament PHP](https://filamentphp.com/) - Panel de administración
- [NativePHP](https://nativephp.com/) - Aplicaciones móviles
- [Record Finder Pro](https://filamentphp.com/plugins/ralphjsmit-record-finder-pro) - Plugin de búsqueda
- [Laravel](https://laravel.com/) - Framework PHP

---

⭐ **¡No olvides darle una estrella al proyecto si te resulta útil!**
