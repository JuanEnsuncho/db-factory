# API de db-factory

Biblioteca PHP para la gestión eficiente y reutilizable de conexiones a bases de datos PostgreSQL, MySQL y Oracle utilizando el micro ORM **Medoo** y **PDO**.

## 📚 Documentación y Recursos Adicionales

- 📂 [Ejemplos de Uso Prácticos](file:///home/juan/Documentos/db-factory/examples/README.md)
- 📄 [Manual de Configuración de Variables de Entorno](file:///home/juan/Documentos/db-factory/docs/CONFIGURACION_ENV.md)
- 📘 [Documentación de Arquitectura y Framework](file:///home/juan/Documentos/db-factory/docs/DOCUMENTACION_FRAMEWORK.md)
- 🌐 [Documentación Oficial de Medoo PHP](https://medoo.in/)

## 🚀 Tecnologías

- **PHP**: >= 7.4 / 8.0+
- **Composer**: Gestor de dependencias de PHP
- **Medoo**: ^2.1 (Micro ORM)
- **PDO Extensions**: `pdo_mysql`, `pdo_pgsql`, `pdo_oci`

## 🛠️ Despliegue y Preparación del Entorno

### Pasos para desarrollo local:

1. **Clonar el repositorio:**
   ```bash
   git clone https://github.com/JuanEnsuncho/db-factory.git
   cd db-factory
   ```

2. **Instalar dependencias con Composer:**
   ```bash
   composer install
   ```

3. **Ejemplo de uso en código PHP:**
   ```php
   use DBfactory\DbPostgresFactory;

   $config = [
       'BASE' => 'nombre_base_datos',
       'HOST' => 'localhost',
       'USER' => 'usuario',
       'PASS' => 'contraseña',
       'PORT' => 5432 // Opcional
   ];

   $db = DbPostgresFactory::create($config);

   // Realizar consultas utilizando Medoo
   $data = $db->select('usuarios', '*');
   ```

### Pasos para despliegue con Docker y Docker Compose:

Si deseas probar las conexiones contra contenedores locales de bases de datos:

1. **Crear archivo `docker-compose.yml` de prueba:**
   ```yaml
   version: '3.8'
   services:
     postgres:
       image: postgres:15
       environment:
         POSTGRES_DB: app_db
         POSTGRES_USER: app_user
         POSTGRES_PASSWORD: secret_password
       ports:
         - "5432:5432"

     mysql:
       image: mysql:8
       environment:
         MYSQL_DATABASE: app_db
         MYSQL_USER: app_user
         MYSQL_PASSWORD: secret_password
         MYSQL_ROOT_PASSWORD: root_password
       ports:
         - "3306:3306"
   ```

2. **Iniciar servicios:**
   ```bash
   docker-compose up -d
   ```

## 📁 Estructura Principal del Proyecto

```bash
db-factory/
├── docs/
│   ├── CONFIGURACION_ENV.md
│   └── DOCUMENTACION_FRAMEWORK.md
├── examples/
│   ├── README.md
│   ├── mysql_example.php
│   ├── postgres_example.php
│   ├── oracle_example.php
│   └── multiple_connections.php
├── src/
│   ├── DbMysqlFactory.php
│   ├── DbOracleFactory.php
│   └── DbPostgresFactory.php
├── composer.json
├── LICENSE
└── README.md
```

## 🌳 Metodología Git

Este proyecto utiliza una metodología basada en **Git Flow**:
- `main`: Rama de producción estable.
- `develop`: Rama principal de integración para desarrollo.
- `feature/*`: Ramas para el desarrollo de nuevas características o mejoras.
- `fix/*`: Ramas para corrección de errores.

## ✒️ Autores

- **Juan Jose Ensuncho Cantillo** - *Desarrollador Principal* - [jj.ensuncho@outlook.com](mailto:jj.ensuncho@outlook.com)

## 📄 Licencia

Este proyecto está licenciado bajo la Licencia **MIT**. Consulta el archivo `LICENSE` para más detalles.

## 🛡️ Estándar de Seguridad NFR Implementado

- **Sentencias Preparadas PDO**: Previene ataques de inyección SQL mediante el binding de parámetros nativo de PDO y Medoo.
- **Gestión Segura de Memoria**: Control de instancias estáticas con flushing explícito (`flush()` / `flushAll()`) para evitar fugas de memoria en procesos de larga duración (daemons, workers, ReactPHP, Swoole).
- **Protección de Credenciales**: No se almacenan credenciales en almacenamiento persistente ni en logs del paquete.
