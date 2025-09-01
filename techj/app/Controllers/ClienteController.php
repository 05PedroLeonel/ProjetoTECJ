<?php
namespace App\Controllers;
use App\Core\Controller;
use App\Repositories\MySQL\ClienteRepository;
use App\Repositories\MySQL\SkillRepository;
use App\Repositories\MySQL\PublicationRepository;
class ClienteController extends Controller {
    private function ensure(){ if(empty($_SESSION['user_id'])) $this->redirect('/login'); }
    public function profile(){ $this->ensure(); $cliRepo = new ClienteRepository(); $client = $cliRepo->findByLoginId((int)$_SESSION['user_id']); $skillRepo = new SkillRepository(); $skills = $skillRepo->findByCliente((int)$client['idcliente']); $pubRepo = new PublicationRepository(); $pubs = $pubRepo->all(); $this->view('cliente/profile',['client'=>$client,'skills'=>$skills,'publications'=>$pubs]); }
    public function edit(){ $this->ensure(); $cliRepo = new ClienteRepository(); $client = $cliRepo->findByLoginId((int)$_SESSION['user_id']); $this->view('cliente/edit',['client'=>$client]); }
    public function update(){ $this->ensure(); $nome = $_POST['nome'] ?? ''; $telefone = $_POST['telefone'] ?? ''; $cliRepo = new ClienteRepository(); $cliRepo->updateByLoginId((int)$_SESSION['user_id'], ['nome'=>$nome,'telefone'=>$telefone]); $this->redirect('/perfil'); }
    public function addSkill(){ $this->ensure(); $nome = $_POST['skill'] ?? ''; $cliRepo = new ClienteRepository(); $client = $cliRepo->findByLoginId((int)$_SESSION['user_id']); $skillRepo = new SkillRepository(); $skillRepo->add((int)$client['idcliente'],$nome); $this->redirect('/perfil'); }
    public function deleteSkill(){ $this->ensure(); $id = (int)($_POST['skill_id']??0); $skillRepo = new SkillRepository(); $skillRepo->delete($id); $this->redirect('/perfil'); }
    public function addCertificate(){ $this->ensure(); $titulo = $_POST['titulo'] ?? ''; $pdo = \App\Core\DB::get(); $clientId = $pdo->query('SELECT idcliente FROM cliente WHERE login_idlogin=' . (int)$_SESSION['user_id'])->fetchColumn(); $pdo->prepare('INSERT INTO certificate (cliente_id,titulo) VALUES (?,?)')->execute([$clientId,$titulo]); $this->redirect('/perfil'); }
    public function addEducation(){ $this->ensure(); $instituicao = $_POST['instituicao'] ?? ''; $pdo = \App\Core\DB::get(); $clientId = $pdo->query('SELECT idcliente FROM cliente WHERE login_idlogin=' . (int)$_SESSION['user_id'])->fetchColumn(); $pdo->prepare('INSERT INTO education (cliente_id,instituicao) VALUES (?,?)')->execute([$clientId,$instituicao]); $this->redirect('/perfil'); }
    public function follow(){ $this->ensure(); $target = (int)($_POST['target_id']??0); $pdo = \App\Core\DB::get(); $clientId = $pdo->query('SELECT idcliente FROM cliente WHERE login_idlogin=' . (int)$_SESSION['user_id'])->fetchColumn(); $pdo->prepare('INSERT IGNORE INTO follow (follower_id,following_id) VALUES (?,?)')->execute([$clientId,$target]); $this->redirect('/perfil'); }
}
