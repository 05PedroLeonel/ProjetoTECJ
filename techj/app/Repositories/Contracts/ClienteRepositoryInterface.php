<?php
namespace App\Repositories\Contracts;
interface ClienteRepositoryInterface {
    public function findByLoginId(int $loginId): ?array;
    public function createForLogin(int $loginId, string $name=''): int;
    public function updateByLoginId(int $loginId, array $data): bool;
}
