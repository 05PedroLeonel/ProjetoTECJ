<?php
namespace App\Models;
class Vaga { public ?int $id; public string $area; public string $local; public ?int $empresa_id; public function __construct($id=null,$area='',$local='', $empresa_id=null){ \$this->id=$id; \$this->area=$area; \$this->local=$local; \$this->empresa_id=$empresa_id; } }
