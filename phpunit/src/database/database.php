<?php declare(strict_types=1);
namespace App\Database;

use PDOException;
use Exception;
use PDO;

class Database
{
    private $host = 'localhost';
    private $port = '5432';
    private $dbname = 'PHPUnit';
    private $user = 'postgres';
    private $password = '123456';
    private $conn;

    /**
     * Cria e retorna uma conexão PDO com o banco de dados PostgreSQL.
     *
     * @return PDO
     * @throws Exception Se ocorrer um erro na conexão.
     */
    private function openConnection(): PDO
    {
        // Data Source Name (DSN) para a conexão com o PostgreSQL
        $dsn = "pgsql:host={$this->host};port={$this->port};dbname={$this->dbname}";

        try {
            // Cria uma nova instância de PDO
            $pdo = new PDO($dsn, $this->user, $this->password);
            // Define o modo de erro do PDO para lançar exceções
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            return $pdo;
        } catch (PDOException $e) {
            // Lança uma exceção em caso de erro na conexão
            throw new Exception("Erro ao conectar ao banco de dados: " . $e->getMessage());
        }
    }

    /**
     * Fecha a conexão com o banco de dados, atribuindo null à variável de conexão.
     */
    private function closeConnection(): void
    {
        $this->conn = null;
    }

    /**
     * Executa uma consulta SQL no banco de dados.
     *
     * @param string $sql A consulta SQL a ser executada.
     * @param array $params Parâmetros para a consulta SQL.
     * @return Response Retorna um objeto Response com o status, mensagem e dados.
     */
    private function execute(string $sql): Response
    {
        try {
            if ($this->isMalicious($sql)) {
                // Logue ou trate a tentativa de execução de SQL malicioso de acordo com as necessidades do seu aplicativo
                // Aqui estou apenas retornando uma mensagem de erro como exemplo
                return "Operação não permitida: SQL malicioso detectado.";
            }

            // Abre a conexão com o banco de dados
            $this->conn = $this->openConnection();
            // Prepara a consulta SQL
            $stmt = $this->conn->prepare($sql);
            // Executa a consulta SQL
            $stmt->execute();

            // Verifica se a consulta é um SELECT
            if (stripos($sql, 'SELECT') === 0) {
                $data = $stmt->fetchAll(PDO::FETCH_ASSOC); // Obtém os resultados como um array associativo
                return new Response(true, "Consulta executada com sucesso.", $data);
            } else {
                // Retorna o número de linhas afetadas para INSERT, UPDATE ou DELETE
                $rowCount = $stmt->rowCount();
                return new Response(true, "Consulta executada com sucesso. Linhas afetadas: $rowCount");
            }
        } catch (PDOException $e) {
            // Lança uma exceção com a mensagem de erro
            return new Response(false, "Erro ao executar a consulta: " . $e->getMessage());
        } finally {
            // Garante que a conexão será fechada após a execução da consulta
            $this->closeConnection();
        }
    }

    public function isMalicious($sql)
    {
        // Lista de comandos considerados maliciosos
        $maliciousCommands = array('DROP TABLE', 'DELETE FROM', 'TRUNCATE TABLE', 'ALTER TABLE');

        // Convertendo o SQL para minúsculas para evitar a detecção de comandos em maiúsculas
        $sql = strtolower($sql);

        // Verificando se o SQL contém algum comando malicioso
        foreach ($maliciousCommands as $command) {
            if (strpos($sql, $command) !== false) {
                return true;
            }
        }

        // Se nenhum comando malicioso for encontrado, retornar falso
        return false;
    }

    public function select($sql)
    {
        return $this->execute($sql);
    }

    public function insert($sql)
    {
        return $this->execute($sql);
    }

    public function update($sql)
    {
        return $this->execute($sql);
    }

    public function delete($sql)
    {
        return $this->execute($sql);
    }
}
