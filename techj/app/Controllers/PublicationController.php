<?php
namespace App\Controllers;
use App\Core\Controller;
use App\Repositories\MySQL\PublicationRepository;
class PublicationController extends Controller {
    public function create(){ if(empty($_SESSION['user_id'])) $this->redirect('/login'); $conteudo = $_POST['conteudo'] ?? ''; $pdo = \App\Core\DB::get(); $cid = $pdo->query('SELECT idcliente FROM cliente WHERE login_idlogin=' . (int)$_SESSION['user_id'])->fetchColumn(); $repo = new PublicationRepository(); $repo->create((int)$cid,$conteudo); $this->redirect('/'); }
}
