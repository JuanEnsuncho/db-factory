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
     * Genera una clave única normalizada para la configuración dada.
     */
    private static function generateKey(array $config): string
    {
        $normalized = $config;
        ksort($normalized);
        return md5(serialize($normalized));
    }

    /**
     * Crea o devuelve una instancia de Medoo basada en la configuración de Oracle proporcionada.
     * 
     * @param array $config Configuración de la conexión (HOST, USER, PASS, DATABASE/BASE, PORT opcional, OPTIONS opcional).
     * @return Medoo Instancia de la conexión Oracle.
     * @throws Exception Si falta algún parámetro requerido en la configuración.
     */
    public static function create(array $config): Medoo
    {
        $key = self::generateKey($config);

        if (isset(self::$instances[$key])) {
            try {
                self::$instances[$key]->query("SELECT 1 FROM DUAL");
            } catch (\Throwable $e) {
                unset(self::$instances[$key]);
            }
        }

        if (!isset(self::$instances[$key])) {
            $database = $config['DATABASE'] ?? $config['BASE'] ?? null;
            $port = $config['PORT'] ?? $config['port'] ?? 1521;

            if (!isset($config['HOST'], $config['USER'], $config['PASS']) || !$database) {
                throw new Exception("Configuración incompleta para la conexión a la base de datos.");
            }

            $dsn = 'oci:dbname=(DESCRIPTION=(ADDRESS_LIST=(ADDRESS=(PROTOCOL=TCP)(HOST='.$config['HOST'].')(PORT='.$port.')))(CONNECT_DATA=(SERVICE_NAME='.$database.')))'; 
            $username = $config['USER']; 
            $password = $config['PASS'];

            $defaultOptions = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_NUM,
            ];

            $customOptions = $config['OPTIONS'] ?? $config['options'] ?? [];
            $opt = $customOptions + $defaultOptions;

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
        $key = self::generateKey($config);
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