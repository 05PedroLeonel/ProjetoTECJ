<?php
namespace App\Repositories\MySQL;
use App\Core\DB;
use App\Repositories\Contracts\ClienteRepositoryInterface;
class ClienteRepository implements ClienteRepositoryInterface {
    public function findByLoginId(int $loginId): ?array{ $pdo=DB::get(); $stmt=$pdo->prepare('SELECT * FROM cliente WHERE login_idlogin=?'); $stmt->execute([$loginId]); $r=$stmt->fetch(); return $r?:null; }
    public function createForLogin(int $loginId, string $name=''): int{ $pdo=DB::get(); $stmt=$pdo->prepare('INSERT INTO cliente (nome, login_idlogin) VALUES (?,?)'); $stmt->execute([$name?:'Usuário',$loginId]); return (int)$pdo->lastInsertId(); }
    public function updateByLoginId(int $loginId, array $data): bool{ $pdo=DB::get(); $stmt=$pdo->prepare('UPDATE cliente SET nome=?, telefone=? WHERE login_idlogin=?'); return $stmt->execute([$data['nome']??'',$data['telefone']??'', $loginId]); }
}
