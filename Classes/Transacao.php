<?php
 
declare(strict_types=1);
 
abstract class Transacao
{
    protected ?int $id;
    protected float $valor;
    protected string $descricao;
    protected string $data;
 
    public function __construct(
        float $valor,
        string $descricao,
        string $data,
        ?int $id = null
    ) {
        $this->id = $id;
        $this->valor = $valor;
        $this->descricao = $descricao;
        $this->data = $data;
    }
 
    public function getId(): ?int
    {
        return $this->id;
    }
 
    public function getValor(): float
    {
        return $this->valor;
    }
 
    public function getDescricao(): string
    {
        return $this->descricao;
    }
 
    public function getData(): string
    {
        return $this->data;
    }
 
    // Usado para exibir na tela ("Entrada" / "Saída")
    abstract public function getTipo(): string;
 
    // Usado para gravar no ENUM do banco ("receita" / "despesa")
    abstract public function getTipoBanco(): string;
}
 