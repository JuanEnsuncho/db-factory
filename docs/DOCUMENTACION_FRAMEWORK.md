# Documentación de Desarrollo del Framework (PHP / Medoo)

## 1. Arquitectura General y Flujo de Trabajo

El proyecto **db-factory** proporciona una capa de abstracción basada en el patrón de diseño **Factory** (Fábrica) y **Multiton** para gestionar conexiones de base de datos en aplicaciones PHP utilizando el micro ORM **Medoo** y **PDO**.

### Componentes Clave
- **`DbMysqlFactory`**: Fábrica para conexiones MySQL.
- **`DbPostgresFactory`**: Fábrica para conexiones PostgreSQL.
- **`DbOracleFactory`**: Fábrica para conexiones Oracle (OCI).

Cada fábrica gestiona internamente un catálogo estático (`$instances`) indexado por una clave hash (MD5 de la configuración serializada). Esto garantiza que la misma configuración reutilice la misma conexión PDO/Medoo sin crear duplicados innecesarios.

---

## 2. Creación y Registro de Adaptadores

Las clases de fábrica actúan como adaptadores para crear instancias de `Medoo\Medoo` preconfiguradas con PDO.

### Ejemplo de Adaptador / Fábrica

```php
use DBfactory\DbMysqlFactory;

$config = [
    'BASE' => 'mi_bd',
    'HOST' => '127.0.0.1',
    'USER' => 'usuario',
    'PASS' => 'contraseña',
    'PORT' => 3306
];

$medoo = DbMysqlFactory::create($config);
```

---

## 3. Creación de Controladores (Routing)

En arquitecturas donde se utiliza **db-factory**, los controladores inyectan o solicitan la instancia de `Medoo` a través de la fábrica correspondiente para interactuar con las tablas.

```php
namespace App\Controllers;

use DBfactory\DbPostgresFactory;

class UserController
{
    private $db;

    public function __construct(array $dbConfig)
    {
        $this->db = DbPostgresFactory::create($dbConfig);
    }

    public function index()
    {
        return $this->db->select('users', ['id', 'name', 'email']);
    }
}
```

---

## 4. Creación de Middlewares

Para la gestión de ciclo de vida de peticiones en aplicaciones PHP, se pueden crear middlewares que liberen instancias de conexión al finalizar la ejecución utilizando `flush()` o `flushAll()`.

```php
use DBfactory\DbPostgresFactory;

class DatabaseCleanupMiddleware
{
    public function handle($request, $next, array $config)
    {
        $response = $next($request);
        
        // Liberar la instancia de conexión al finalizar la petición
        DbPostgresFactory::flush($config);

        return $response;
    }
}
```

---

## 5. Creación de Servicios

Los servicios de dominio encapsulan las operaciones de persistencia utilizando la interfaz de Medoo devuelta por **db-factory**.

```php
namespace App\Services;

use DBfactory\DbOracleFactory;

class FinancialService
{
    public static function getTransactions(array $config)
    {
        $db = DbOracleFactory::create($config);
        return $db->select('TRANSACTIONS', '*', ['STATUS' => 'COMPLETED']);
    }
}
```

---

## 6. Configuración de Proveedores (Variables de Entorno)

Se recomienda inyectar la configuración desde variables de entorno a través de un proveedor de configuración global (por ejemplo, utilizando `vlucas/phpdotenv` o superglobales `$_ENV`).

---

## 7. Manejo de Plantillas (Handlebars .hbs)

*(N/A - Esta biblioteca es exclusivamente una capa de persistencia de datos y no incluye motor de plantillas visuales).*

---

## 8. Utilidades del Núcleo y Seguridad NFR

- **Normalización de Claves Hash**: Se ordenan automáticamente las claves del arreglo `$config` (`ksort`) antes de generar la clave hash MD5. Esto evita crear conexiones duplicadas si el orden de los elementos varía.
- **Resiliencia y Detección de Conexiones Muertas (Ping)**: Al solicitar una instancia existente desde `$instances`, la fábrica realiza una verificación (`SELECT 1`). Si la conexión fue cerrada por timeout o caída del servidor, se invalida y reconecta automáticamente.
- **Opciones PDO Personalizadas y Estándar**: Soporte para pasar el arreglo `OPTIONS` en la configuración para ajustar parámetros de PDO (modo de errores, emulación de prepara, etc.).
- **Preparación de Consultas via PDO**: Todas las conexiones utilizan PDO con sentencias preparadas nativas de Medoo, previniendo ataques de inyección SQL.
- **Prevención de Fugas de Conexiones**: El método `flushAll()` permite restablecer el estado de las instancias almacenadas en memoria estática.
- **Seguridad NFR**: Manejo seguro de credenciales sin persistencia en disco dentro del paquete.
