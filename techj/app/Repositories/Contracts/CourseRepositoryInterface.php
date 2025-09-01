<?php
namespace App\Repositories\Contracts;
interface CourseRepositoryInterface {
    public function all(): array;
    public function enroll(int $clienteId, int $courseId): bool;
    public function updateProgress(int $clienteId, int $courseId, int $progress): bool;
}
