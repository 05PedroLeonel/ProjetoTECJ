<?php
namespace App\Models;
class Login { public ?int $id; public string $email; public string $senha; public function __construct($id=null,$email='',$senha=''){ \$this->id=$id; \$this->email=$email; \$this->senha=$senha; } }
