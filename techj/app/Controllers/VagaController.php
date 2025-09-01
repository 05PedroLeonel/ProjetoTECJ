<?php
namespace App\Controllers;
use App\Core\Controller;
use App\Repositories\MySQL\VagaRepository;
class VagaController extends Controller {
    public function index(){ $vagaRepo = new VagaRepository(); $vagas = $vagaRepo->all(); $this->view('vagas/index',['vagas'=>$vagas]); }
    public function apply(){ if(empty($_SESSION['user_id'])){ $this->redirect('/login'); }
        $vagaId = (int)($_POST['vaga_id']??0); $pdo = \App\Core\DB::get(); $clienteId = $pdo->query('SELECT idcliente FROM cliente WHERE login_idlogin=' . (int)$_SESSION['user_id'])->fetchColumn(); $pdo->prepare('INSERT IGNORE INTO cliente_has_vagas (cliente_idcliente,Vagas_idVagas) VALUES (?,?)')->execute([$clienteId,$vagaId]); $this->redirect('/vagas'); }
    public function create(){ $area = $_POST['area'] ?? ''; $local = $_POST['local'] ?? ''; $vagaRepo = new VagaRepository(); $vagaRepo->create(['area_desejada'=>$area,'localidade'=>$local]); $this->redirect('/vagas'); }
}
