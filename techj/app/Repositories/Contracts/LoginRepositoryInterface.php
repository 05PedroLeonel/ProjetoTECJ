<?php
namespace App\Repositories\Contracts;
use App\Models\Login;
interface LoginRepositoryInterface {
    public function findByEmail(string $email): ?array;
    public function create(string $email, string $hash): int;
    public function findById(int $id): ?array;
}
