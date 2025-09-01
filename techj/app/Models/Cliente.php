<?php
namespace App\Models;
class Cliente { public ?int $id; public ?int $login_id; public string $nome; public ?string $telefone; public function __construct($id=null,$login_id=null,$nome='',$telefone=null){ \$this->id=$id; \$this->login_id=$login_id; \$this->nome=$nome; \$this->telefone=$telefone; } }
