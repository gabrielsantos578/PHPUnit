<?php declare(strict_types=1);
namespace App\Tests;

use PHPUnit\Framework\TestCase;
use Exception;

final class InitialTest extends TestCase
{
    public function testFactorialCalculation(): void
    {
        echo ". Configuration initial test...\n";

        // Chama a função para calcular o fatorial
        $factorial = $this->calculateFactorial(5);

        // Verifica se o resultado está correto
        $this->assertEquals(120, $factorial);
    }

    public function testArraySort(): void
    {
        echo " Sorting array test...\n";

        // Array desordenado
        $unsortedArray = [4, 2, 7, 1, 9];

        // Chama a função para ordenar o array
        $sortedArray = $this->sortArray($unsortedArray);

        // Verifica se o array foi ordenado corretamente
        $this->assertEquals([1, 2, 4, 7, 9], $sortedArray);
    }

    private function calculateFactorial(int $n): int
    {
        if ($n < 0) {
            throw new Exception("Não é possível calcular o fatorial de um número negativo.");
        }

        // Caso base: fatorial de 0 é 1
        if ($n === 0) {
            return 1;
        }

        // Inicializa o fatorial como 1
        $factorial = 1;

        // Calcula o fatorial multiplicando os números de 1 até $n
        for ($i = 1; $i <= $n; $i++) {
            $factorial *= $i;
        }

        return $factorial;
    }

    private function sortArray(array $array): array
    {
        // Usa a função sort() para ordenar o array
        sort($array);

        return $array;
    }
}
