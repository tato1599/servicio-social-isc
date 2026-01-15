

# 🏫 Sistema de Gestión de Horarios Multi-Departamento

Este proyecto es una solución integral desarrollada en **Laravel 12** para la asignación automatizada de salones y horarios, optimizando la logística académica entre distintos departamentos.


---

## 🛠️ Tecnologías Utilizadas

El proyecto está construido bajo el **TALL Stack**:

* **Framework:** [Laravel 12](https://laravel.com/)
* **Frontend Interactivo:** [Livewire 3](https://livewire.laravel.com/)
* **Estilos:** [Tailwind CSS](https://tailwindcss.com/)
* **Autenticación y Perfiles:** [Laravel Jetstream](https://jetstream.laravel.com/) (Livewire Stack)
* **Base de Datos:** Postgresql
* **Pruebas:** Pest PHP

---

## 🚀 Guía de Instalación

Sigue estos pasos para replicar el entorno de desarrollo localmente.

### 1. Clonar el repositorio
```bash
git clone https://github.com/tato1599/servicio-social-isc.git
cd servicio-social-isc

```

### 2. Instalar dependencias de PHP

```bash
composer install

```

### 3. Instalar dependencias de Frontend

```bash
npm install
npm run build

```

### 4. Configuración del entorno

Copia el archivo de ejemplo y genera la llave de la aplicación:

```bash
cp .env.example .env
php artisan key:generate

```

### 5. Configurar la Base de Datos

Edita tu archivo `.env` con las credenciales de tu base de datos local:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=nombre_de_tu_db
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_contraseña

```

### 6. Ejecutar Migraciones y Seeders

Este comando creará las tablas (incluyendo las de Jetstream y Departamentos) y cargará los datos iniciales si los tienes configurados:

```bash
php artisan migrate --seed

```

### 7. Iniciar el servidor

```bash
php artisan serve

```

La aplicación estará disponible en: `http://127.0.0.1:8000`

---


---

## 🧪 Ejecución de Pruebas

Para asegurar que las reglas de no traslape de horarios funcionen correctamente, ejecuta los tests con Pest:

```bash
php artisan test

```

---

