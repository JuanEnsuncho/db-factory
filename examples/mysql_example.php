<?php

require_once __DIR__ . '/../vendor/autoload.php';

use DBfactory\DbMysqlFactory;

// Configuración de la base de datos MySQL
$config = [
    'BASE'    => 'mi_base_datos',
    'HOST'    => '127.0.0.1',
    'USER'    => 'root',
    'PASS'    => 'secret',
    'PORT'    => 3306, // Opcional (por defecto 3306)
    'OPTIONS' => [     // Opciones PDO opcionales
        PDO::ATTR_PERSISTENT => false,
    ]
];

try {
    // 1. Obtener la instancia de Medoo para MySQL
    $db = DbMysqlFactory::create($config);

    // 2. Insertar un registro
    $db->insert('usuarios', [
        'nombre' => 'Juan Pérez',
        'email'  => 'juan@example.com'
    ]);

    // 3. Consultar registros
    $usuarios = $db->select('usuarios', ['id', 'nombre', 'email'], [
        'LIMIT' => 10
    ]);

    echo "Usuarios encontrados:\n";
    print_r($usuarios);

    // 4. Liberar la conexión al finalizar si es necesario
    DbMysqlFactory::flush($config);

} catch (Exception $e) {
    echo "Error de conexión o consulta: " . $e->getMessage() . "\n";
}
