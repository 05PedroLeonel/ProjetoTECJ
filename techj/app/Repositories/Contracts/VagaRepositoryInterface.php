<?php
namespace App\Repositories\Contracts;
interface VagaRepositoryInterface {
    public function all(): array;
    public function find(int $id): ?array;
    public function create(array $data): int;
}
