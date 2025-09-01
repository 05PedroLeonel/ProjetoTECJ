<?php
namespace App\Models;
class Course { public ?int $id; public string $titulo; public string $descricao; public function __construct($id=null,$titulo='',$descricao=''){ \$this->id=$id; \$this->titulo=$titulo; \$this->descricao=$descricao; } }
