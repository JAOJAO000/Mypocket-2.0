<?php

declare(strict_types=1);

require_once "Receita.php";
require_once "Despesa.php";
require_once __DIR__ . "/../config/Conexao.php";

class Carteira
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Conexao::getConexao();
    }

    public function getSaldo(): float
    {
        $sql = "SELECT
                    COALESCE(SUM(CASE WHEN tipo = 'receita' THEN valor ELSE -valor END), 0) AS saldo
                FROM transacoes";

        $stmt = $this->pdo->query($sql);
        $resultado = $stmt->fetch();

        return (float) $resultado["saldo"];
    }

    public function getTransacoes(): array
    {
        $sql = "SELECT * FROM transacoes ORDER BY data DESC, id DESC";

        $stmt = $this->pdo->query($sql);
        $linhas = $stmt->fetchAll();

        $transacoes = [];

        foreach ($linhas as $linha) {
            $transacoes[] = $this->criarTransacao($linha);
        }

        return $transacoes;
    }

    public function buscarPorId(int $id): ?Transacao
    {
        $sql = "SELECT * FROM transacoes WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(["id" => $id]);

        $linha = $stmt->fetch();

        if (!$linha) {
            return null;
        }

        return $this->criarTransacao($linha);
    }

    public function adicionarTransacao(Transacao $transacao): void
    {
        if ($transacao instanceof Despesa && $transacao->getValor() > $this->getSaldo()) {
            throw new Exception("Saldo insuficiente.");
        }

        $sql = "INSERT INTO transacoes (tipo, valor, descricao, data)
                VALUES (:tipo, :valor, :descricao, :data)";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            "tipo" => $transacao->getTipoBanco(),
            "valor" => $transacao->getValor(),
            "descricao" => $transacao->getDescricao(),
            "data" => $transacao->getData(),
        ]);
    }

    public function atualizarTransacao(int $id, Transacao $transacao): void
    {
        $sql = "UPDATE transacoes
                SET tipo = :tipo, valor = :valor, descricao = :descricao, data = :data
                WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            "tipo" => $transacao->getTipoBanco(),
            "valor" => $transacao->getValor(),
            "descricao" => $transacao->getDescricao(),
            "data" => $transacao->getData(),
            "id" => $id,
        ]);
    }

    public function removerTransacao(int $id): void
    {
        $sql = "DELETE FROM transacoes WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(["id" => $id]);
    }

    private function criarTransacao(array $linha): Transacao
    {
        if ($linha["tipo"] === "receita") {

            return new Receita(
                (float) $linha["valor"],
                $linha["descricao"],
                $linha["data"],
                (int) $linha["id"]
            );
        }

        return new Despesa(
            (float) $linha["valor"],
            $linha["descricao"],
            $linha["data"],
            (int) $linha["id"]
        );
    }
}