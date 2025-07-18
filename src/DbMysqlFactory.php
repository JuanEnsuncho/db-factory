<?php
namespace DBfactory;

use Medoo\Medoo;
use PDO;
use Exception;

class DbMysqlFactory
{
    // Manejador de múltiples instancias para diferentes bases de datos.
    private static array $instances = [];

    /**
     * Constructor privado para evitar instancias directas.
     */
    private function __construct() {}

    /**
     * Método estático para obtener o crear una instancia de Medoo basada en una configuración específica.
     * 
     * @param array $config Configuración de conexión (host, usuario, contraseña, base de datos).
     * @return Medoo Instancia de la base de datos.
     * @throws Exception Si no se proporcionan los parámetros adecuados.
     */
    public static function create(array $config): Medoo
    {
        $key = md5(serialize($config)); // Clave única para cada configuración.

        if (!isset(self::$instances[$key])) {
            // Validar que la configuración contenga los parámetros requeridos.
            if (!isset($config['BASE'], $config['HOST'], $config['USER'], $config['PASS'])) {
                throw new Exception("Configuración incompleta para la conexión a la base de datos.");
            }

            // Crear una nueva instancia de Medoo con la configuración dada.
            $pdo = new PDO(
                'mysql:dbname=' . $config['BASE'] . ';host=' . $config['HOST'],
                $config['USER'],
                $config['PASS']
            );

            self::$instances[$key] = new Medoo([
                'type' => 'mysql',
                'pdo'  => $pdo,
            ]);
        }

        return self::$instances[$key];
    }

    /**
     * Método para liberar una instancia específica de la conexión.
     * 
     * @param array $config Configuración utilizada para identificar la instancia.
     */
    public static function flush(array $config): void
    {
        $key = md5(serialize($config));
        unset(self::$instances[$key]);
    }

    /**
     * Método para liberar todas las instancias creadas.
     */
    public static function flushAll(): void
    {
        self::$instances = [];
    }
}
?>