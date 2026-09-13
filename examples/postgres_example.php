<?php

require_once __DIR__ . '/../vendor/autoload.php';

use DBfactory\DbPostgresFactory;

// Configuración de la base de datos PostgreSQL
$config = [
    'BASE' => 'mi_base_datos',
    'HOST' => '127.0.0.1',
    'USER' => 'postgres',
    'PASS' => 'secret',
    'PORT' => 5432 // Opcional (por defecto 5432)
];

try {
    // 1. Obtener la instancia de Medoo para PostgreSQL
    $db = DbPostgresFactory::create($config);

    // 2. Consultar registros
    $productos = $db->select('productos', '*', [
        'precio[>]' => 100
    ]);

    echo "Productos con precio mayor a 100:\n";
    print_r($productos);

    // 3. Liberar la conexión específica
    DbPostgresFactory::flush($config);

} catch (Exception $e) {
    echo "Error de conexión o consulta: " . $e->getMessage() . "\n";
}
