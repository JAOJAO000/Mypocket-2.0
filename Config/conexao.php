<?php

declare(strict_types=1);

class Conexao
{
    private static ?PDO $conexao = null;

    public static function getConexao(): PDO
    {
        if (self::$conexao === null) {

            $host = "localhost";
            $dbname = "mypocket";
            $usuario = "root";
            $senha = "";

            try {

                self::$conexao = new PDO(
                    "mysql:host={$host};dbname={$dbname};charset=utf8mb4",
                    $usuario,
                    $senha,
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    ]
                );

            } catch (PDOException $e) {
                die("Erro na conexão com o banco: " . $e->getMessage());
            }
        }

        return self::$conexao;
    }
}