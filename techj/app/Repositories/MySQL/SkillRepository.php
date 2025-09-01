<?php
namespace App\Repositories\MySQL;
use App\Core\DB;
use App\Repositories\Contracts\SkillRepositoryInterface;
class SkillRepository implements SkillRepositoryInterface {
    public function findByCliente(int $clienteId): array{ $pdo=DB::get(); $stmt=$pdo->prepare('SELECT * FROM skill WHERE cliente_id=?'); $stmt->execute([$clienteId]); return $stmt->fetchAll(); }
    public function add(int $clienteId, string $nome): int{ $pdo=DB::get(); $stmt=$pdo->prepare('INSERT INTO skill (cliente_id,nome) VALUES (?,?)'); $stmt->execute([$clienteId,$nome]); return (int)$pdo->lastInsertId(); }
    public function delete(int $id): bool{ $pdo=DB::get(); $stmt=$pdo->prepare('DELETE FROM skill WHERE id=?'); return $stmt->execute([$id]); }
}
