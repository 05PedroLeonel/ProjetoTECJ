<?php
namespace App\Models;
class Skill { public ?int $id; public ?int $cliente_id; public string $nome; public function __construct($id=null,$cliente_id=null,$nome=''){ \$this->id=$id; \$this->cliente_id=$cliente_id; \$this->nome=$nome; } }
