<?php
namespace App\Repositories\MySQL;
use App\Core\DB;
use App\Repositories\Contracts\LoginRepositoryInterface;
class LoginRepository implements LoginRepositoryInterface {
    public function findByEmail(string $email): ?array{ $pdo=DB::get(); $stmt=$pdo->prepare('SELECT * FROM login WHERE email=?'); $stmt->execute([$email]); $r=$stmt->fetch(); return $r?:null; }
    public function create(string $email, string $hash): int{ $pdo=DB::get(); $stmt=$pdo->prepare('INSERT INTO login (email, senha) VALUES (?,?)'); $stmt->execute([$email,$hash]); return (int)$pdo->lastInsertId(); }
    public function findById(int $id): ?array{ $pdo=DB::get(); $stmt=$pdo->prepare('SELECT * FROM login WHERE idlogin=?'); $stmt->execute([$id]); $r=$stmt->fetch(); return $r?:null; }
}
