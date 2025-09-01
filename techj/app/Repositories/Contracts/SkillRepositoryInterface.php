<?php
namespace App\Repositories\Contracts;
interface SkillRepositoryInterface {
    public function findByCliente(int $clienteId): array;
    public function add(int $clienteId, string $nome): int;
    public function delete(int $id): bool;
}
