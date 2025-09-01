<?php
namespace App\Models;
class Publication { public ?int $id; public ?int $cliente_id; public string $conteudo; public function __construct($id=null,$cliente_id=null,$conteudo=''){ \$this->id=$id; \$this->cliente_id=$cliente_id; \$this->conteudo=$conteudo; } }
