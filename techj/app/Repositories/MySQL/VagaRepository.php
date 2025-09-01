<?php
namespace App\Repositories\MySQL;
use App\Core\DB;
use App\Repositories\Contracts\VagaRepositoryInterface;
class VagaRepository implements VagaRepositoryInterface {
    public function all(): array{ $pdo=DB::get(); return $pdo->query('SELECT v.*, e.nome as empresa FROM vagas v LEFT JOIN empresa e ON v.empresa_idempresa=e.idempresa')->fetchAll(); }
    public function find(int $id): ?array{ $pdo=DB::get(); $stmt=$pdo->prepare('SELECT * FROM vagas WHERE idVagas=?'); $stmt->execute([$id]); $r=$stmt->fetch(); return $r?:null; }
    public function create(array $data): int{ $pdo=DB::get(); $stmt=$pdo->prepare('INSERT INTO vagas (area_desejada, localidade, empresa_idempresa) VALUES (?,?,?)'); $stmt->execute([$data['area_desejada'],$data['localidade'],$data['empresa_id']??null]); return (int)$pdo->lastInsertId(); }
}
