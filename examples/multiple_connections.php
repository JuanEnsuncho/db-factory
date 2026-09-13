<?php

require_once __DIR__ . '/../vendor/autoload.php';

use DBfactory\DbMysqlFactory;
use DBfactory\DbPostgresFactory;

// Configuración MySQL
$mysqlConfig = [
    'BASE' => 'app_mysql',
    'HOST' => '127.0.0.1',
    'USER' => 'root',
    'PASS' => 'secret',
    'PORT' => 3306
];

// Configuración PostgreSQL
$pgConfig = [
    'BASE' => 'app_pg',
    'HOST' => '127.0.0.1',
    'USER' => 'postgres',
    'PASS' => 'secret',
    'PORT' => 5432
];

try {
    // 1. Obtener conexión a MySQL
    $dbMysql = DbMysqlFactory::create($mysqlConfig);

    // 2. Obtener conexión a PostgreSQL
    $dbPg = DbPostgresFactory::create($pgConfig);

    // 3. Demostración de Reutilización (Multiton Pattern):
    // Una segunda llamada con el mismo contenido (incluso con orden de llaves distinto)
    // devolverá exactamente la misma instancia de conexión sin abrir otro socket.
    $mysqlConfigInvertido = [
        'PORT' => 3306,
        'PASS' => 'secret',
        'USER' => 'root',
        'HOST' => '127.0.0.1',
        'BASE' => 'app_mysql'
    ];

    $dbMysql2 = DbMysqlFactory::create($mysqlConfigInvertido);

    if (spl_object_hash($dbMysql) === spl_object_hash($dbMysql2)) {
        echo "✅ Multiton exitoso: La misma instancia fue reutilizada eficientemente.\n";
    }

    // 4. Liberar todas las conexiones al finalizar el trabajo masivo
    DbMysqlFactory::flushAll();
    DbPostgresFactory::flushAll();
    echo "✅ Todas las conexiones han sido liberadas.\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
