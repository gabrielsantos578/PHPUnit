<?php declare(strict_types=1);
namespace App\Tests;

use App\Database\Connection;
use App\Database\Response;
use PHPUnit\Framework\TestCase;
use Exception;
use PDO;

final class DatabaseTest extends TestCase
{
    private const DB_NAME = 'PHPUnit';

    public function testConnection(): void
    {
        try {
            echo "Connection test...\n";

            $conn = new Connection();
            $response = $conn->select("SELECT 1");

            $this->assertInstanceOf(Response::class, $response);
            $this->assertTrue($response->status);
            $this->assertSame("Consulta executada com sucesso.", $response->message);
            $this->assertNotNull($response->data);
        } catch (\Exception $e) {
            $this->fail("Exception during testConnection: " . $e->getMessage());
        }
    }

    public function testCreateDatabase(): void
    {
        try {
            echo "Create database test...\n";

            $pdo = $this->createPDOConnection();

            if (!$this->databaseExists($pdo, self::DB_NAME)) {
                $this->createDatabase($pdo, self::DB_NAME);
                $this->assertTrue($this->databaseExists($pdo, self::DB_NAME), 'Falha ao criar o banco de dados.');

                $pdo = $this->createPDOConnectionToDB();
                $this->createTables($pdo);

                $this->assertTrue($this->tableExists($pdo, 'users'), 'Falha ao criar a tabela Users.');
                $this->assertTrue($this->tableExists($pdo, 'sessions'), 'Falha ao criar a tabela Sessions.');
            } else {
                throw new Exception('O banco de dados já existe. Nenhuma ação necessária.');
            }
        } catch (\Exception $e) {
            $this->fail("Exception during testCreateDatabase: " . $e->getMessage());
        }
    }

    private function createPDOConnection(): PDO
    {
        // Configurações de conexão
        $host = 'localhost';
        $port = '5432';
        $user = 'postgres';
        $password = '123456';

        // Cria uma nova conexão PDO
        $dsn = "pgsql:host=$host;port=$port";
        return new PDO($dsn, $user, $password);
    }

    private function createPDOConnectionToDB(): PDO
    {
        // Configurações de conexão
        $host = 'localhost';
        $port = '5432';
        $dbname = self::DB_NAME;
        $user = 'postgres';
        $password = '123456';

        // Cria uma nova conexão PDO
        $dsn = "pgsql:host=$host;port=$port;dbname=$dbname";
        return new PDO($dsn, $user, $password);
    }

    private function databaseExists(PDO $pdo, string $dbName): bool
    {
        // Consulta para verificar se o banco de dados existe
        $sql = "SELECT 1 FROM pg_database WHERE datname = :dbName";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':dbName', $dbName);
        $stmt->execute();

        return $stmt->fetchColumn() !== false;
    }

    private function createDatabase(PDO $pdo, string $dbName): void
    {
        // Consulta para criar o banco de dados
        $sql = "CREATE DATABASE $dbName";

        $pdo->exec($sql);
    }

    private function tableExists(PDO $pdo, string $tableName): bool
    {
        // Consulta para verificar se a tabela existe
        $sql = "SELECT EXISTS (SELECT FROM information_schema.tables WHERE table_name = :tableName)";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':tableName', $tableName);
        $stmt->execute();

        return $stmt->fetchColumn() === 't';
    }

    private function createTables(PDO $pdo): void
    {
        // Consulta para criar a tabela Users
        $sqlUsers = "CREATE TABLE Users (
            id SERIAL PRIMARY KEY,
            username VARCHAR(50) NOT NULL,
            email VARCHAR(100) NOT NULL,
            password VARCHAR(255) NOT NULL,
            created_at VARCHAR(19) DEFAULT TO_CHAR(CURRENT_TIMESTAMP, 'DD/MM/YYYY HH24:MI:SS')
        )";

        // Consulta para criar a tabela Sessions
        $sqlSessions = "CREATE TABLE Sessions (
            id SERIAL PRIMARY KEY,
            dateOpening VARCHAR(19) DEFAULT TO_CHAR(CURRENT_TIMESTAMP, 'DD/MM/YYYY HH24:MI:SS'),
            dateClosure VARCHAR(19),
            idUser INT NOT NULL,
            CONSTRAINT FK_idUser FOREIGN KEY (idUser) REFERENCES Users(id)
        )";

        $pdo->exec($sqlUsers);
        $pdo->exec($sqlSessions);
    }
}
