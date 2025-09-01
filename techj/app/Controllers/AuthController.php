<?php
namespace App\Controllers;
use App\Core\Controller;
use App\Repositories\MySQL\LoginRepository;
use App\Repositories\MySQL\ClienteRepository;
class AuthController extends Controller {
    public function showLogin(){ $this->view('auth/login'); }
    public function showRegister(){ $this->view('auth/register'); }
    public function register(){ $email = $_POST['email'] ?? ''; $senha = $_POST['senha'] ?? ''; if(!$email||!$senha){ $_SESSION['err']='Preencha'; $this->redirect('/register'); }
        $loginRepo = new LoginRepository(); $exists = $loginRepo->findByEmail($email);
        if($exists){ $_SESSION['err']='E-mail já cadastrado'; $this->redirect('/register'); }
        $hash = password_hash($senha, PASSWORD_DEFAULT); $id = $loginRepo->create($email,$hash);
        $cliRepo = new ClienteRepository(); $cliRepo->createForLogin($id,'Usuário'); $_SESSION['user_id'] = $id; $this->redirect('/perfil'); }
    public function login(){ $email = $_POST['email'] ?? ''; $senha = $_POST['senha'] ?? ''; $loginRepo = new LoginRepository(); $u = $loginRepo->findByEmail($email); if($u && password_verify($senha,$u['senha'])){ $_SESSION['user_id']=$u['idlogin']; $this->redirect('/perfil'); } $_SESSION['err']='Credenciais inválidas'; $this->redirect('/login'); }
    public function logout(){ session_destroy(); $this->redirect('/'); }
}
