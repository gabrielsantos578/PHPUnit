<?php declare(strict_types=1);
namespace App\Database;

// Classe para encapsular a resposta da execução da consulta
class Response
{
    public bool $status;
    public string $message;
    public $data;

    public function __construct(bool $status, string $message, $data = null)
    {
        $this->status = $status;
        $this->message = $message;
        $this->data = $data;
    }
}
