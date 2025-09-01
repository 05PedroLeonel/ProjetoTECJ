<?php
namespace App\Repositories\Contracts;
interface PublicationRepositoryInterface {
    public function create(int $clienteId, string $conteudo): int;
    public function all(): array;
}
