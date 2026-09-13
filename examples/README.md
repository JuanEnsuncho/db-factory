# 📂 Ejemplos de uso de `db-factory`

Esta carpeta contiene ejemplos prácticos de uso para conectar y ejecutar consultas en MySQL, PostgreSQL y Oracle utilizando las clases de **db-factory** y la interfaz de **Medoo PHP**.

## 📄 Archivos de Ejemplo

- **[`mysql_example.php`](file:///home/juan/Documentos/db-factory/examples/mysql_example.php)**: Conexión a MySQL con puerto y opciones personalizadas de PDO.
- **[`postgres_example.php`](file:///home/juan/Documentos/db-factory/examples/postgres_example.php)**: Conexión a PostgreSQL y ejecución de consultas.
- **[`oracle_example.php`](file:///home/juan/Documentos/db-factory/examples/oracle_example.php)**: Conexión a Oracle (OCI) especificando `SERVICE_NAME`.
- **[`multiple_connections.php`](file:///home/juan/Documentos/db-factory/examples/multiple_connections.php)**: Manejo de múltiples bases de datos concurrentes y demostración de reutilización eficiente de instancias (Patrón Multiton).

## 🚀 Cómo ejecutar los ejemplos

Asegúrate de haber instalado las dependencias vía Composer antes de ejecutar los scripts:

```bash
composer install
```

Luego puedes ejecutar cualquiera de los scripts directamente desde la línea de comandos:

```bash
php examples/mysql_example.php
php examples/postgres_example.php
php examples/oracle_example.php
php examples/multiple_connections.php
```
