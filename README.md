# 📦 Conexión PostgreSQL, MySQL y Oracle con Medoo

Este paquete proporciona una serie de clases PHP que permiten gestionar múltiples conexiones a bases de datos PostgreSQL, MySQL y Oracle utilizando el micro ORM Medoo. Es ideal para proyectos que requieren una arquitectura limpia y reutilizable para el acceso a datos.

## 🚀 Instalación

Puedes instalar este paquete vía Composer:

```bash
composer require juanensuncho/db-factory
```

## 🧰 Ejemplos de uso

El siguiente es un ejemplo de uso de la clase DbPostgresFactory. Para mayor información sobre las consultas que pueden realizarse, consulte el sitio web de la documentación de [Medoo PHP](https://medoo.in/).

```php
use DBfactory\DbPostgresFactory;

$config = [
    'BASE' => 'nombre_base_datos',
    'HOST' => 'localhost',
    'USER' => 'usuario',
    'PASS' => 'contraseña'
];

$db = DbPostgresFactory::create($config);

// Ejemplo de consulta
$data = $db->select('usuarios', '*');

```

## 🗂️ Estructura del proyecto

```bash
db-factory/
├── src/
│   └── DbPostgresFactory.php
|   └── DbMysqlFactory.php 
|   └── DbPracleFactory.php
├── composer.json
└── README.md
```

## 📄 Licencia

Este proyecto está licenciado bajo la licencia MIT.
