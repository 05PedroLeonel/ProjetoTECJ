<?php
namespace App\Repositories\MySQL;
use App\Core\DB;
use App\Repositories\Contracts\CourseRepositoryInterface;
class CourseRepository implements CourseRepositoryInterface {
    public function all(): array{ $pdo=DB::get(); return $pdo->query('SELECT * FROM course')->fetchAll(); }
    public function enroll(int $clienteId, int $courseId): bool{ $pdo=DB::get(); $stmt=$pdo->prepare('INSERT IGNORE INTO enrollment (cliente_id, course_id) VALUES (?,?)'); return $stmt->execute([$clienteId,$courseId]); }
    public function updateProgress(int $clienteId, int $courseId, int $progress): bool{ $pdo=DB::get(); $stmt=$pdo->prepare('UPDATE enrollment SET progresso=? WHERE cliente_id=? AND course_id=?'); return $stmt->execute([$progress,$clienteId,$courseId]); }
}
