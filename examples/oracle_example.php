<?php

require_once __DIR__ . '/../vendor/autoload.php';

use DBfactory\DbOracleFactory;

// Configuración de la base de datos Oracle
$config = [
    'DATABASE' => 'ORCLCDB', // O 'BASE' => 'ORCLCDB'
    'HOST'     => 'oracle-host',
    'USER'     => 'system',
    'PASS'     => 'oracle_password',
    'PORT'     => 1521 // Opcional (por defecto 1521)
];

try {
    // 1. Obtener la instancia de Medoo para Oracle
    $db = DbOracleFactory::create($config);

    // 2. Ejecutar consulta
    $data = $db->select('EMPLEADOS', ['ID_EMPLEADO', 'NOMBRE', 'SALARIO'], [
        'LIMIT' => 5
    ]);

    echo "Empleados:\n";
    print_r($data);

    // 3. Liberar la conexión
    DbOracleFactory::flush($config);

} catch (Exception $e) {
    echo "Error de conexión u Oracle: " . $e->getMessage() . "\n";
}
