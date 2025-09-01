<?php
namespace App\Repositories\MySQL;
use App\Core\DB;
use App\Repositories\Contracts\PublicationRepositoryInterface;
class PublicationRepository implements PublicationRepositoryInterface {
    public function create(int $clienteId, string $conteudo): int{ $pdo=DB::get(); $stmt=$pdo->prepare('INSERT INTO publication (cliente_id,conteudo) VALUES (?,?)'); $stmt->execute([$clienteId,$conteudo]); return (int)$pdo->lastInsertId(); }
    public function all(): array{ $pdo=DB::get(); return $pdo->query('SELECT p.*, c.nome as cliente FROM publication p LEFT JOIN cliente c ON p.cliente_id=c.idcliente ORDER BY criado_em DESC')->fetchAll(); }
}
