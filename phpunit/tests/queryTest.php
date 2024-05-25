<?php declare(strict_types=1);
namespace App\Tests;

use App\Database\Connection;
use App\Database\Response;
use PHPUnit\Framework\TestCase;

final class QueryTest extends TestCase
{
    public function testSelectQuery(): void
    {
        try {
            echo "Select test...\n";

            $conn = new Connection();
            $response = $conn->select("SELECT * FROM users");

            $this->assertInstanceOf(Response::class, $response);
            $this->assertTrue($response->status);
            $this->assertSame("Consulta executada com sucesso.", $response->message);
            $this->assertNotNull($response->data);
        } catch (\Exception $e) {
            $this->fail("Exception during testSelectQuery: " . $e->getMessage());
        }
    }

    public function testInsertQuery(): void
    {
        try {
            echo "Insert test...\n";

            $conn = new Connection();
            $response = $conn->insert("INSERT INTO users (username, email, password) VALUES ('John Doe', 'john@example.com', 'password')");

            $this->assertInstanceOf(Response::class, $response);
            $this->assertTrue($response->status);
            $this->assertSame("Consulta executada com sucesso. Linhas afetadas: 1", $response->message);
            $this->assertNull($response->data);
        } catch (\Exception $e) {
            $this->fail("Exception during testInsertQuery: " . $e->getMessage());
        }
    }

    public function testUpdateQuery(): void
    {
        try {
            echo "Update test...\n";

            $conn = new Connection();
            $response = $conn->update("UPDATE users SET email = 'updated@example.com' WHERE id = 1");

            $this->assertInstanceOf(Response::class, $response);
            $this->assertTrue($response->status);
            $this->assertSame("Consulta executada com sucesso. Linhas afetadas: 1", $response->message);
            $this->assertNull($response->data);
        } catch (\Exception $e) {
            $this->fail("Exception during testUpdateQuery: " . $e->getMessage());
        }
    }

    public function testDeleteQuery(): void
    {
        try {
            echo "Delete test...\n";

            $conn = new Connection();
            $response = $conn->delete("DELETE FROM users WHERE id = 1");

            $this->assertInstanceOf(Response::class, $response);
            $this->assertTrue($response->status);
            $this->assertSame("Consulta executada com sucesso. Linhas afetadas: 1", $response->message);
            $this->assertNull($response->data);
        } catch (\Exception $e) {
            $this->fail("Exception during testDeleteQuery: " . $e->getMessage());
        }
    }
}
