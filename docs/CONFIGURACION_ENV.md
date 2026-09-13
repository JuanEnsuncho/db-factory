# Manual de Configuración de Variables de Entorno (db-factory)

Este documento describe la estructura y configuración necesaria de las variables y parámetros requeridos para establecer conexiones a bases de datos en **db-factory**.

## 📋 Configuración General del Servidor

Las clases de fábrica (`DbMysqlFactory`, `DbPostgresFactory`, `DbOracleFactory`) reciben un arreglo de configuración (`$config`) con los parámetros de conexión requeridos y opcionales.

### Parámetros de Configuración por Motor de Base de Datos

#### 1. MySQL (`DbMysqlFactory`)
| Clave | Tipo | Requerido | Descripción | Ejemplo |
| :--- | :--- | :--- | :--- | :--- |
| `BASE` | string | **Sí** | Nombre de la base de datos | `mi_base_datos` |
| `HOST` | string | **Sí** | Host o dirección IP del servidor MySQL | `127.0.0.1` |
| `USER` | string | **Sí** | Usuario de la base de datos | `root` |
| `PASS` | string | **Sí** | Contraseña del usuario | `secret` |
| `PORT` / `port` | int\|string | No | Puerto de conexión (opcional, por defecto 3306) | `3306` |
| `OPTIONS` / `options` | array | No | Opciones personalizadas de PDO | `[PDO::ATTR_PERSISTENT => true]` |

#### 2. PostgreSQL (`DbPostgresFactory`)
| Clave | Tipo | Requerido | Descripción | Ejemplo |
| :--- | :--- | :--- | :--- | :--- |
| `BASE` | string | **Sí** | Nombre de la base de datos | `mi_base_datos` |
| `HOST` | string | **Sí** | Host o dirección IP del servidor PostgreSQL | `localhost` |
| `USER` | string | **Sí** | Usuario de la base de datos | `postgres` |
| `PASS` | string | **Sí** | Contraseña del usuario | `secret` |
| `PORT` / `port` | int\|string | No | Puerto de conexión (opcional, por defecto 5432) | `5432` |
| `OPTIONS` / `options` | array | No | Opciones personalizadas de PDO | `[PDO::ATTR_TIMEOUT => 5]` |

#### 3. Oracle (`DbOracleFactory`)
| Clave | Tipo | Requerido | Descripción | Ejemplo |
| :--- | :--- | :--- | :--- | :--- |
| `DATABASE` / `BASE` | string | **Sí** | Nombre del servicio (Service Name) de Oracle | `ORCLCDB` |
| `HOST` | string | **Sí** | Host o dirección IP del servidor Oracle | `oracle-host` |
| `USER` | string | **Sí** | Usuario de la base de datos | `system` |
| `PASS` | string | **Sí** | Contraseña del usuario | `oracle` |
| `PORT` / `port` | int\|string | No | Puerto de conexión (opcional, por defecto 1521) | `1521` |
| `OPTIONS` / `options` | array | No | Opciones personalizadas de PDO | `[PDO::ATTR_CASE => PDO::CASE_LOWER]` |

---

### Ejemplo de archivo `.env` recomendado en proyectos consumidores

```env
# Configuración MySQL
MYSQL_HOST=127.0.0.1
MYSQL_PORT=3306
MYSQL_DATABASE=app_db
MYSQL_USER=app_user
MYSQL_PASSWORD=app_password

# Configuración PostgreSQL
PGSQL_HOST=127.0.0.1
PGSQL_PORT=5432
PGSQL_DATABASE=app_db
PGSQL_USER=app_user
PGSQL_PASSWORD=app_password

# Configuración Oracle
ORACLE_HOST=127.0.0.1
ORACLE_PORT=1521
ORACLE_SERVICE_NAME=ORCLCDB
ORACLE_USER=app_user
ORACLE_PASSWORD=app_password
```
