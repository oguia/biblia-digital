<?php

require_once __DIR__ . '/db.php';

class BibliaModel {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance()->getConnection();
    }

    public function getLivros() {
        // Removido liv_abreviacao temporariamente pois causava erro 1054 em alguns ambientes
        $stmt = $this->pdo->query("SELECT liv_id, liv_nome, liv_tes_id FROM livros ORDER BY liv_id ASC");
        return $stmt->fetchAll();
    }

    public function getVersoes() {
        $stmt = $this->pdo->query("SELECT vrs_id, vrs_nome FROM versoes ORDER BY vrs_id ASC");
        return $stmt->fetchAll();
    }

    public function getVersiculos($liv_id, $capitulo, $vrs_id) {
        $sql = "SELECT ver_numero, ver_texto
                FROM versiculos
                WHERE ver_liv_id = :liv_id
                  AND ver_capitulo = :capitulo
                  AND ver_vrs_id = :vrs_id
                ORDER BY ver_numero ASC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':liv_id' => $liv_id,
            ':capitulo' => $capitulo,
            ':vrs_id' => $vrs_id
        ]);

        return $stmt->fetchAll();
    }

    public function getContextoGeografico($liv_id, $capitulo) {
        $sql = "SELECT nome, latitude, longitude, descricao, imagem
                FROM contexto_geografico
                WHERE liv_id = :liv_id
                  AND capitulo = :capitulo";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':liv_id' => $liv_id,
            ':capitulo' => $capitulo
        ]);

        return $stmt->fetchAll();
    }

    public function getCronologia($liv_id, $capitulo) {
        $sql = "SELECT ano_estimado, periodo, personagens, eventos_mundiais
                FROM cronologia
                WHERE liv_id = :liv_id
                  AND capitulo = :capitulo
                LIMIT 1"; // Assumindo uma entrada por capítulo

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':liv_id' => $liv_id,
            ':capitulo' => $capitulo
        ]);

        return $stmt->fetch();
    }

    public function getAplicacaoPratica($liv_id, $capitulo) {
        $sql = "SELECT verdade_central, alerta, acao_pratica
                FROM aplicacao_pratica
                WHERE liv_id = :liv_id
                  AND capitulo = :capitulo
                LIMIT 1";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':liv_id' => $liv_id,
            ':capitulo' => $capitulo
        ]);

        return $stmt->fetch();
    }

    public function getBookById($liv_id) {
        $stmt = $this->pdo->prepare("SELECT liv_nome FROM livros WHERE liv_id = :liv_id");
        $stmt->execute([':liv_id' => $liv_id]);
        return $stmt->fetch();
    }
}
