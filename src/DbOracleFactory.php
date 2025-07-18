<?php
namespace DBfactory;

use Medoo\Medoo;
use PDO;
use Exception;

class DbOracleFactory
{
    // Almacena múltiples instancias para diferentes conexiones.
    private static array $instances = [];

    /**
     * Constructor privado para evitar instancias directas.
     */
    private function __construct() {}

    /**
     * Crea o devuelve una instancia de Medoo basada en la configuración de Oracle proporcionada.
     * 
     * @param array $config Configuración de la conexión (WALLET_NAME, USER, PASS).
     * @return Medoo Instancia de la conexión Oracle.
     * @throws Exception Si falta algún parámetro requerido en la configuración.
     */
    public static function create(array $config): Medoo
    {
        $key = md5(serialize($config)); // Genera una clave única para la configuración.

        if (!isset(self::$instances[$key])) {

            $dsn = 'oci:dbname=(DESCRIPTION=(ADDRESS_LIST=(ADDRESS=(PROTOCOL=TCP)(HOST='.$config['HOST'].')(PORT='.$config['PORT'].')))(CONNECT_DATA=(SERVICE_NAME='.$config['DATABASE'].')))'; 
            $username = $config['USER']; 
            $password = $config['PASS'];

            $opt = [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_NUM,
            ];

            $pdo = new PDO($dsn, $username, $password, $opt);

            self::$instances[$key] = new Medoo([
                'pdo' => $pdo,
                'type' => 'oracle',
                'error' => PDO::ERRMODE_SILENT
            ]);
        }

        return self::$instances[$key];
    }

    /**
     * Libera una instancia específica basada en su configuración.
     * 
     * @param array $config Configuración utilizada para identificar la instancia.
     */
    public static function flush(array $config): void
    {
        $key = md5(serialize($config));
        unset(self::$instances[$key]);
    }

    /**
     * Libera todas las instancias creadas.
     */
    public static function flushAll(): void
    {
        self::$instances = [];
    }
}
?>