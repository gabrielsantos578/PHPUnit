<?php declare(strict_types=1);
namespace App\Tests;

use App\Database\Database;
use App\Database\Response;
use PHPUnit\Framework\TestCase;

final class QueryTest extends TestCase
{
    public function testSelectQuery(): void
    {
        echo "Select query test...\n";

        $db = new Database();
        $response = $db->select("SELECT * FROM users");

        $this->assertInstanceOf(Response::class, $response);
        $this->assertTrue($response->status);
        $this->assertSame("Consulta executada com sucesso.", $response->message);
        $this->assertNotNull($response->data);
    }

    public function testInsertQuery(): void
    {
        echo "Insert query test...\n";

        $db = new Database();
        $response = $db->insert("INSERT INTO users (name, email) VALUES ('John Doe', 'john@example.com')");

        $this->assertInstanceOf(Response::class, $response);
        $this->assertTrue($response->status);
        $this->assertSame("Consulta executada com sucesso. Linhas afetadas: 1", $response->message);
        $this->assertNull($response->data);
    }

    public function testUpdateQuery(): void
    {
        echo "Update query test...\n";

        $db = new Database();
        $response = $db->update("UPDATE users SET email = 'updated@example.com' WHERE id = 1");

        $this->assertInstanceOf(Response::class, $response);
        $this->assertTrue($response->status);
        $this->assertSame("Consulta executada com sucesso. Linhas afetadas: 1", $response->message);
        $this->assertNull($response->data);
    }

    public function testDeleteQuery(): void
    {
        echo "Delete query test...\n";

        $db = new Database();
        $response = $db->delete("DELETE FROM users WHERE id = 1");

        $this->assertInstanceOf(Response::class, $response);
        $this->assertTrue($response->status);
        $this->assertSame("Consulta executada com sucesso. Linhas afetadas: 1", $response->message);
        $this->assertNull($response->data);
    }
}
