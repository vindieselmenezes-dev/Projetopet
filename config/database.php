<?php
/**
 * ==========================================================
 * PETFINDER BRASIL
 * Arquivo: config/database.php
 * ==========================================================
 */

declare(strict_types=1);

class Database
{
    /**
     * Configurações do banco
     */
    private const HOST = 'localhost';
    private const DATABASE = 'petfinder';
    private const USER = 'root';
    private const PASSWORD = '';
    private const CHARSET = 'utf8mb4';

    /**
     * Instância única da conexão
     */
    private static ?PDO $connection = null;

    /**
     * Retorna uma conexão PDO
     */
    public static function conectar(): PDO
    {
        if (self::$connection === null) {

            $dsn = sprintf(
                "mysql:host=%s;dbname=%s;charset=%s",
                self::HOST,
                self::DATABASE,
                self::CHARSET
            );

            try {

                self::$connection = new PDO(
                    $dsn,
                    self::USER,
                    self::PASSWORD,
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_EMULATE_PREPARES => false
                    ]
                );

            } catch (PDOException $e) {

                die(
                    "Erro ao conectar ao banco de dados: " .
                    $e->getMessage()
                );

            }

        }

        return self::$connection;
    }
}