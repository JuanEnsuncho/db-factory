<?php
namespace DBfactory;

use Medoo\Medoo;
use PDO;
use Exception;

class DbPostgresFactory
{
    // Manejador de múltiples instancias para diferentes bases de datos.
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
     * Método estático para obtener o crear una instancia de Medoo basada en una configuración específica.
     * 
     * @param array $config Configuración de conexión (host, usuario, contraseña, base de datos, puerto opcional, opciones PDO).
     * @return Medoo Instancia de la base de datos.
     * @throws Exception Si no se proporcionan los parámetros adecuados.
     */
    public static function create(array $config): Medoo
    {
        $key = self::generateKey($config);

        if (isset(self::$instances[$key])) {
            try {
                self::$instances[$key]->query("SELECT 1;");
            } catch (\Throwable $e) {
                unset(self::$instances[$key]);
            }
        }

        if (!isset(self::$instances[$key])) {
            // Validar que la configuración contenga los parámetros requeridos.
            if (!isset($config['BASE'], $config['HOST'], $config['USER'], $config['PASS'])) {
                throw new Exception("Configuración incompleta para la conexión a la base de datos.");
            }

            $port = isset($config['PORT']) ? ';port=' . $config['PORT'] : (isset($config['port']) ? ';port=' . $config['port'] : '');

            $defaultOptions = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];

            $customOptions = $config['OPTIONS'] ?? $config['options'] ?? [];
            $options = $customOptions + $defaultOptions;

            // Crear una nueva instancia de Medoo con la configuración dada.
            $pdo = new PDO(
                'pgsql:dbname=' . $config['BASE'] . ';host=' . $config['HOST'] . $port,
                $config['USER'],
                $config['PASS'],
                $options
            );

            self::$instances[$key] = new Medoo([
                'type' => 'pgsql',
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
        $key = self::generateKey($config);
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