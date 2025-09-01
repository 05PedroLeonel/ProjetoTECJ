# TECJ - Projeto MVC (Entrega 2)

Este README contém todo o código do projeto (cada arquivo na íntegra).

## composer.json
```text
{
  "name": "tecj/portal",
  "description": "TECJ - MVC PHP project skeleton with Repository Pattern",
  "type": "project",
  "require": { "php": ">=8.0" },
  "autoload": { "psr-4": { "App\\": "app/" } }
}

```


## database.sql
```sql

CREATE DATABASE IF NOT EXISTS tecj DEFAULT CHARACTER SET utf8mb4;
USE tecj;

CREATE TABLE login (
    idlogin INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100) UNIQUE NOT NULL,
    senha VARCHAR(255) NOT NULL
);

CREATE TABLE cliente (
    idcliente INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(150) NOT NULL,
    idade INT,
    sexo VARCHAR(10),
    telefone VARCHAR(20),
    endereco VARCHAR(200),
    login_idlogin INT,
    foto VARCHAR(255),
    FOREIGN KEY (login_idlogin) REFERENCES login(idlogin) ON DELETE CASCADE
);

CREATE TABLE empresa (
    idempresa INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(150),
    exigencias TEXT
);

CREATE TABLE vagas (
    idVagas INT AUTO_INCREMENT PRIMARY KEY,
    area_desejada VARCHAR(150),
    localidade VARCHAR(150),
    empresa_idempresa INT,
    FOREIGN KEY (empresa_idempresa) REFERENCES empresa(idempresa) ON DELETE SET NULL
);

CREATE TABLE empresa_has_cliente (
    empresa_idempresa INT,
    cliente_idcliente INT,
    PRIMARY KEY (empresa_idempresa, cliente_idcliente),
    FOREIGN KEY (empresa_idempresa) REFERENCES empresa(idempresa),
    FOREIGN KEY (cliente_idcliente) REFERENCES cliente(idcliente)
);

CREATE TABLE cliente_has_vagas (
    cliente_idcliente INT,
    Vagas_idVagas INT,
    PRIMARY KEY (cliente_idcliente, Vagas_idVagas),
    FOREIGN KEY (cliente_idcliente) REFERENCES cliente(idcliente) ON DELETE CASCADE,
    FOREIGN KEY (Vagas_idVagas) REFERENCES vagas(idVagas) ON DELETE CASCADE
);

CREATE TABLE skill (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT,
    nome VARCHAR(120),
    nivel VARCHAR(50),
    FOREIGN KEY (cliente_id) REFERENCES cliente(idcliente) ON DELETE CASCADE
);

CREATE TABLE certificate (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT,
    titulo VARCHAR(200),
    entidade VARCHAR(200),
    ano YEAR,
    FOREIGN KEY (cliente_id) REFERENCES cliente(idcliente) ON DELETE CASCADE
);

CREATE TABLE education (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT,
    instituicao VARCHAR(200),
    curso VARCHAR(200),
    ano_inicio YEAR,
    ano_fim YEAR,
    FOREIGN KEY (cliente_id) REFERENCES cliente(idcliente) ON DELETE CASCADE
);

CREATE TABLE course (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(200),
    descricao TEXT
);

CREATE TABLE enrollment (
    cliente_id INT,
    course_id INT,
    progresso INT DEFAULT 0,
    PRIMARY KEY (cliente_id, course_id),
    FOREIGN KEY (cliente_id) REFERENCES cliente(idcliente) ON DELETE CASCADE,
    FOREIGN KEY (course_id) REFERENCES course(id) ON DELETE CASCADE
);

CREATE TABLE publication (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT,
    conteudo TEXT,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (cliente_id) REFERENCES cliente(idcliente) ON DELETE SET NULL
);

CREATE TABLE follow (
    follower_id INT,
    following_id INT,
    PRIMARY KEY (follower_id, following_id),
    FOREIGN KEY (follower_id) REFERENCES cliente(idcliente) ON DELETE CASCADE,
    FOREIGN KEY (following_id) REFERENCES cliente(idcliente) ON DELETE CASCADE
);

INSERT INTO course (titulo, descricao) VALUES
('HTML Básico','Aprenda a estrutura do HTML'),
('CSS Básico','Estilize páginas com CSS'),
('JavaScript Básico','Introdução ao JS');

```


## vendor/autoload.php
```php
<?php
spl_autoload_register(function($class){
    $prefix = 'App\\';
    if(strpos($class, $prefix) === 0){
        $relative = substr($class, strlen($prefix));
        $file = __DIR__ . '/../app/' . str_replace('\\\\', '/', $relative) . '.php';
        $file = str_replace('App/', 'app/', $file);
        if(file_exists($file)) require $file;
    }
});

```


## config/config.php
```php
<?php
// Ajuste suas credenciais
const DB_HOST = '127.0.0.1';
const DB_NAME = 'tecj';
const DB_USER = 'root';
const DB_PASS = '';
if(session_status() === PHP_SESSION_NONE) session_start();

```


## public/.htaccess
```text
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule ^ index.php [QSA,L]

```


## public/index.php
```php
<?php
declare(strict_types=1);
require __DIR__ . '/../config/config.php';
require __DIR__ . '/../vendor/autoload.php';

use App\Core\Router;

$router = new Router();

// Home
$router->get('/', 'HomeController@index');

// Auth
$router->get('/login', 'AuthController@showLogin');
$router->post('/login', 'AuthController@login');
$router->get('/logout', 'AuthController@logout');
$router->get('/register', 'AuthController@showRegister');
$router->post('/register', 'AuthController@register');

// Perfil
$router->get('/perfil', 'ClienteController@profile');
$router->get('/perfil/editar', 'ClienteController@edit');
$router->post('/perfil/update', 'ClienteController@update');

// Skills, certificates, education
$router->post('/skill/add', 'ClienteController@addSkill');
$router->post('/skill/delete', 'ClienteController@deleteSkill');
$router->post('/certificate/add', 'ClienteController@addCertificate');
$router->post('/education/add', 'ClienteController@addEducation');

// Vagas
$router->get('/vagas', 'VagaController@index');
$router->post('/vagas/apply', 'VagaController@apply');
$router->post('/vagas/create', 'VagaController@create');

// Courses
$router->get('/courses', 'CourseController@index');
$router->post('/courses/enroll', 'CourseController@enroll');
$router->post('/courses/progress', 'CourseController@updateProgress');

// Publications
$router->post('/publicacao/create', 'PublicationController@create');

// Follow
$router->post('/follow', 'ClienteController@follow');

$router->dispatch();

```


## app/Core/Router.php
```php
<?php
namespace App\Core;
class Router {
    private array $routes = ['GET'=>[],'POST'=>[]];
    public function get(string $path, string $action){ $this->routes['GET'][$path]=$action; }
    public function post(string $path, string $action){ $this->routes['POST'][$path]=$action; }
    public function dispatch(): void {
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
        // normalize
        $uri = '/' . trim($uri, '/');
        $action = $this->routes[$method][$uri] ?? null;
        if(!$action){ http_response_code(404); echo '404 Not Found'; return; }
        [$controller, $methodAction] = explode('@', $action);
        $controllerClass = "App\\Controllers\\{$controller}";
        if(!class_exists($controllerClass)){
            // try to require file
            $file = __DIR__ . "/../Controllers/{$controller}.php";
            if(file_exists($file)) require_once $file;
        }
        if(!class_exists($controllerClass)){
            throw new \RuntimeException("Controller {$controllerClass} not found");
        }
        $c = new $controllerClass();
        if(!method_exists($c, $methodAction)) throw new \RuntimeException("Method {$methodAction} not found in {$controllerClass}");
        $c->$methodAction();
    }
}

```


## app/Core/Controller.php
```php
<?php
namespace App\Core;
class Controller {
    protected function view(string $view, array $data=[]){
        extract($data);
        $viewPath = __DIR__ . "/../Views/{$view}.php";
        require __DIR__ . "/../Views/layout.php";
    }
    protected function redirect(string $path){ header('Location: ' . $path); exit; }
}

```


## app/Core/DB.php
```php
<?php
namespace App\Core;
use PDO;
class DB {
    private static ?PDO $pdo = null;
    public static function get(): PDO{
        if(self::$pdo === null){
            $dsn = sprintf('mysql:host=%s;dbname=%s;charset=utf8mb4', DB_HOST, DB_NAME);
            self::$pdo = new PDO($dsn, DB_USER, DB_PASS, [PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION]);
        }
        return self::$pdo;
    }
}

```


## app/Models/Login.php
```php
<?php
namespace App\Models;
class Login { public ?int $id; public string $email; public string $senha; public function __construct($id=null,$email='',$senha=''){ \$this->id=$id; \$this->email=$email; \$this->senha=$senha; } }

```


## app/Models/Cliente.php
```php
<?php
namespace App\Models;
class Cliente { public ?int $id; public ?int $login_id; public string $nome; public ?string $telefone; public function __construct($id=null,$login_id=null,$nome='',$telefone=null){ \$this->id=$id; \$this->login_id=$login_id; \$this->nome=$nome; \$this->telefone=$telefone; } }

```


## app/Models/Vaga.php
```php
<?php
namespace App\Models;
class Vaga { public ?int $id; public string $area; public string $local; public ?int $empresa_id; public function __construct($id=null,$area='',$local='', $empresa_id=null){ \$this->id=$id; \$this->area=$area; \$this->local=$local; \$this->empresa_id=$empresa_id; } }

```


## app/Models/Skill.php
```php
<?php
namespace App\Models;
class Skill { public ?int $id; public ?int $cliente_id; public string $nome; public function __construct($id=null,$cliente_id=null,$nome=''){ \$this->id=$id; \$this->cliente_id=$cliente_id; \$this->nome=$nome; } }

```


## app/Models/Course.php
```php
<?php
namespace App\Models;
class Course { public ?int $id; public string $titulo; public string $descricao; public function __construct($id=null,$titulo='',$descricao=''){ \$this->id=$id; \$this->titulo=$titulo; \$this->descricao=$descricao; } }

```


## app/Models/Publication.php
```php
<?php
namespace App\Models;
class Publication { public ?int $id; public ?int $cliente_id; public string $conteudo; public function __construct($id=null,$cliente_id=null,$conteudo=''){ \$this->id=$id; \$this->cliente_id=$cliente_id; \$this->conteudo=$conteudo; } }

```


## app/Repositories/Contracts/LoginRepositoryInterface.php
```php
<?php
namespace App\Repositories\Contracts;
use App\Models\Login;
interface LoginRepositoryInterface {
    public function findByEmail(string $email): ?array;
    public function create(string $email, string $hash): int;
    public function findById(int $id): ?array;
}

```


## app/Repositories/Contracts/ClienteRepositoryInterface.php
```php
<?php
namespace App\Repositories\Contracts;
interface ClienteRepositoryInterface {
    public function findByLoginId(int $loginId): ?array;
    public function createForLogin(int $loginId, string $name=''): int;
    public function updateByLoginId(int $loginId, array $data): bool;
}

```


## app/Repositories/Contracts/VagaRepositoryInterface.php
```php
<?php
namespace App\Repositories\Contracts;
interface VagaRepositoryInterface {
    public function all(): array;
    public function find(int $id): ?array;
    public function create(array $data): int;
}

```


## app/Repositories/Contracts/SkillRepositoryInterface.php
```php
<?php
namespace App\Repositories\Contracts;
interface SkillRepositoryInterface {
    public function findByCliente(int $clienteId): array;
    public function add(int $clienteId, string $nome): int;
    public function delete(int $id): bool;
}

```


## app/Repositories/Contracts/CourseRepositoryInterface.php
```php
<?php
namespace App\Repositories\Contracts;
interface CourseRepositoryInterface {
    public function all(): array;
    public function enroll(int $clienteId, int $courseId): bool;
    public function updateProgress(int $clienteId, int $courseId, int $progress): bool;
}

```


## app/Repositories/Contracts/PublicationRepositoryInterface.php
```php
<?php
namespace App\Repositories\Contracts;
interface PublicationRepositoryInterface {
    public function create(int $clienteId, string $conteudo): int;
    public function all(): array;
}

```


## app/Repositories/MySQL/LoginRepository.php
```php
<?php
namespace App\Repositories\MySQL;
use App\Core\DB;
use App\Repositories\Contracts\LoginRepositoryInterface;
class LoginRepository implements LoginRepositoryInterface {
    public function findByEmail(string $email): ?array{ $pdo=DB::get(); $stmt=$pdo->prepare('SELECT * FROM login WHERE email=?'); $stmt->execute([$email]); $r=$stmt->fetch(); return $r?:null; }
    public function create(string $email, string $hash): int{ $pdo=DB::get(); $stmt=$pdo->prepare('INSERT INTO login (email, senha) VALUES (?,?)'); $stmt->execute([$email,$hash]); return (int)$pdo->lastInsertId(); }
    public function findById(int $id): ?array{ $pdo=DB::get(); $stmt=$pdo->prepare('SELECT * FROM login WHERE idlogin=?'); $stmt->execute([$id]); $r=$stmt->fetch(); return $r?:null; }
}

```


## app/Repositories/MySQL/ClienteRepository.php
```php
<?php
namespace App\Repositories\MySQL;
use App\Core\DB;
use App\Repositories\Contracts\ClienteRepositoryInterface;
class ClienteRepository implements ClienteRepositoryInterface {
    public function findByLoginId(int $loginId): ?array{ $pdo=DB::get(); $stmt=$pdo->prepare('SELECT * FROM cliente WHERE login_idlogin=?'); $stmt->execute([$loginId]); $r=$stmt->fetch(); return $r?:null; }
    public function createForLogin(int $loginId, string $name=''): int{ $pdo=DB::get(); $stmt=$pdo->prepare('INSERT INTO cliente (nome, login_idlogin) VALUES (?,?)'); $stmt->execute([$name?:'Usuário',$loginId]); return (int)$pdo->lastInsertId(); }
    public function updateByLoginId(int $loginId, array $data): bool{ $pdo=DB::get(); $stmt=$pdo->prepare('UPDATE cliente SET nome=?, telefone=? WHERE login_idlogin=?'); return $stmt->execute([$data['nome']??'',$data['telefone']??'', $loginId]); }
}

```


## app/Repositories/MySQL/VagaRepository.php
```php
<?php
namespace App\Repositories\MySQL;
use App\Core\DB;
use App\Repositories\Contracts\VagaRepositoryInterface;
class VagaRepository implements VagaRepositoryInterface {
    public function all(): array{ $pdo=DB::get(); return $pdo->query('SELECT v.*, e.nome as empresa FROM vagas v LEFT JOIN empresa e ON v.empresa_idempresa=e.idempresa')->fetchAll(); }
    public function find(int $id): ?array{ $pdo=DB::get(); $stmt=$pdo->prepare('SELECT * FROM vagas WHERE idVagas=?'); $stmt->execute([$id]); $r=$stmt->fetch(); return $r?:null; }
    public function create(array $data): int{ $pdo=DB::get(); $stmt=$pdo->prepare('INSERT INTO vagas (area_desejada, localidade, empresa_idempresa) VALUES (?,?,?)'); $stmt->execute([$data['area_desejada'],$data['localidade'],$data['empresa_id']??null]); return (int)$pdo->lastInsertId(); }
}

```


## app/Repositories/MySQL/SkillRepository.php
```php
<?php
namespace App\Repositories\MySQL;
use App\Core\DB;
use App\Repositories\Contracts\SkillRepositoryInterface;
class SkillRepository implements SkillRepositoryInterface {
    public function findByCliente(int $clienteId): array{ $pdo=DB::get(); $stmt=$pdo->prepare('SELECT * FROM skill WHERE cliente_id=?'); $stmt->execute([$clienteId]); return $stmt->fetchAll(); }
    public function add(int $clienteId, string $nome): int{ $pdo=DB::get(); $stmt=$pdo->prepare('INSERT INTO skill (cliente_id,nome) VALUES (?,?)'); $stmt->execute([$clienteId,$nome]); return (int)$pdo->lastInsertId(); }
    public function delete(int $id): bool{ $pdo=DB::get(); $stmt=$pdo->prepare('DELETE FROM skill WHERE id=?'); return $stmt->execute([$id]); }
}

```


## app/Repositories/MySQL/CourseRepository.php
```php
<?php
namespace App\Repositories\MySQL;
use App\Core\DB;
use App\Repositories\Contracts\CourseRepositoryInterface;
class CourseRepository implements CourseRepositoryInterface {
    public function all(): array{ $pdo=DB::get(); return $pdo->query('SELECT * FROM course')->fetchAll(); }
    public function enroll(int $clienteId, int $courseId): bool{ $pdo=DB::get(); $stmt=$pdo->prepare('INSERT IGNORE INTO enrollment (cliente_id, course_id) VALUES (?,?)'); return $stmt->execute([$clienteId,$courseId]); }
    public function updateProgress(int $clienteId, int $courseId, int $progress): bool{ $pdo=DB::get(); $stmt=$pdo->prepare('UPDATE enrollment SET progresso=? WHERE cliente_id=? AND course_id=?'); return $stmt->execute([$progress,$clienteId,$courseId]); }
}

```


## app/Repositories/MySQL/PublicationRepository.php
```php
<?php
namespace App\Repositories\MySQL;
use App\Core\DB;
use App\Repositories\Contracts\PublicationRepositoryInterface;
class PublicationRepository implements PublicationRepositoryInterface {
    public function create(int $clienteId, string $conteudo): int{ $pdo=DB::get(); $stmt=$pdo->prepare('INSERT INTO publication (cliente_id,conteudo) VALUES (?,?)'); $stmt->execute([$clienteId,$conteudo]); return (int)$pdo->lastInsertId(); }
    public function all(): array{ $pdo=DB::get(); return $pdo->query('SELECT p.*, c.nome as cliente FROM publication p LEFT JOIN cliente c ON p.cliente_id=c.idcliente ORDER BY criado_em DESC')->fetchAll(); }
}

```


## app/Controllers/HomeController.php
```php
<?php
namespace App\Controllers;
use App\Core\Controller;
use App\Repositories\MySQL\PublicationRepository;
use App\Repositories\MySQL\CourseRepository;
class HomeController extends Controller {
    public function index(){ $pubRepo = new PublicationRepository(); $pubs = $pubRepo->all(); $courseRepo = new CourseRepository(); $courses = $courseRepo->all(); $this->view('home/index',['publications'=>$pubs,'courses'=>$courses]); }
}

```


## app/Controllers/AuthController.php
```php
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

```


## app/Controllers/ClienteController.php
```php
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

```


## app/Controllers/VagaController.php
```php
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

```


## app/Controllers/CourseController.php
```php
<?php
namespace App\Controllers;
use App\Core\Controller;
use App\Repositories\MySQL\CourseRepository;
class CourseController extends Controller {
    public function index(){ $repo = new CourseRepository(); $courses = $repo->all(); $this->view('courses/index',['courses'=>$courses]); }
    public function enroll(){ if(empty($_SESSION['user_id'])) $this->redirect('/login'); $course=(int)($_POST['course_id']??0); $pdo=\App\Core\DB::get(); $cid=$pdo->query('SELECT idcliente FROM cliente WHERE login_idlogin='.(int)$_SESSION['user_id'])->fetchColumn(); $repo=new CourseRepository(); $repo->enroll((int)$cid,$course); $this->redirect('/courses'); }
    public function updateProgress(){ if(empty($_SESSION['user_id'])) $this->redirect('/login'); $course=(int)($_POST['course_id']??0); $progress=(int)($_POST['progress']??0); $pdo=\App\Core\DB::get(); $cid=$pdo->query('SELECT idcliente FROM cliente WHERE login_idlogin='.(int)$_SESSION['user_id'])->fetchColumn(); $repo=new CourseRepository(); $repo->updateProgress((int)$cid,$course,$progress); $this->redirect('/courses'); }
}

```


## app/Controllers/PublicationController.php
```php
<?php
namespace App\Controllers;
use App\Core\Controller;
use App\Repositories\MySQL\PublicationRepository;
class PublicationController extends Controller {
    public function create(){ if(empty($_SESSION['user_id'])) $this->redirect('/login'); $conteudo = $_POST['conteudo'] ?? ''; $pdo = \App\Core\DB::get(); $cid = $pdo->query('SELECT idcliente FROM cliente WHERE login_idlogin=' . (int)$_SESSION['user_id'])->fetchColumn(); $repo = new PublicationRepository(); $repo->create((int)$cid,$conteudo); $this->redirect('/'); }
}

```


## app/Views/layout.php
```php
<!doctype html>
<html lang="pt-br"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>TECJ</title>
<style>
body{font-family:Arial,Helvetica,sans-serif;background:#0f1720;color:#e6eef3;margin:0}
.header{background:#0b5cff;padding:14px;color:white;display:flex;justify-content:space-between;align-items:center}
.container{max-width:1000px;margin:18px auto;padding:0 16px}
.card{background:#122230;padding:18px;border-radius:10px;margin-bottom:16px;box-shadow:0 6px 18px rgba(0,0,0,0.4)}
.btn{background:#0b72d6;color:white;padding:10px 14px;border-radius:8px;text-decoration:none;border:none;cursor:pointer}
.input{width:100%;padding:10px;border-radius:8px;border:1px solid rgba(255,255,255,0.06);background:transparent;color:inherit}
.grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:12px}
.small{color:#9fb0bf;font-size:0.9rem}
.nav a{color:white;text-decoration:none;margin-left:12px}
</style>
</head><body>
<header class="header"><div><strong>TECJ</strong></div><nav class="nav"><a href="/">Início</a><a href="/courses">Cursos</a><a href="/vagas">Vagas</a><a href="/perfil">Perfil</a></nav></header>
<main class="container"><?php if(isset($viewPath)) include $viewPath; ?></main>
</body></html>

```


## app/Views/home/index.php
```php
<?php
// $publications, $courses
?>
<div class="card"><h2>Bem-vindo ao TECJ</h2><p class="small">Plataforma de exemplo com MVC + Repository.</p></div>
<div class="card"><h3>Cursos</h3><div class="grid"><?php foreach($courses as $c): ?><div class="card"><h4><?=htmlspecialchars($c['titulo'])?></h4><p class="small"><?=htmlspecialchars($c['descricao'])?></p><form method="post" action="/courses/enroll"><input type="hidden" name="course_id" value="<?=$c['id']?>"><button class="btn">Inscrever-se</button></form></div><?php endforeach; ?></div></div>
<div class="card"><h3>Feed</h3><?php foreach($publications as $p): ?><div class="card"><p><?=htmlspecialchars($p['conteudo'])?></p><p class="small">— <?=htmlspecialchars($p['cliente'] ?? 'Anônimo')?>, <?=htmlspecialchars($p['criado_em'])?></p></div><?php endforeach; ?></div>

```


## app/Views/auth/login.php
```php
<div class="card" style="max-width:520px;margin:30px auto"><h2>Login</h2><?php if(!empty($_SESSION['err'])){ echo '<p style="color:#ffb4b4">'.htmlspecialchars($_SESSION['err']).'</p>'; unset($_SESSION['err']); } ?><form method="post" action="/login"><div class="form-row"><input name="email" class="input" placeholder="E-mail"></div><div class="form-row"><input name="senha" class="input" type="password" placeholder="Senha"></div><button class="btn">Entrar</button></form><p class="small">ou <a href="/register">criar conta</a></p></div>
```


## app/Views/auth/register.php
```php
<div class="card" style="max-width:520px;margin:30px auto"><h2>Cadastro</h2><form method="post" action="/register"><div class="form-row"><input name="email" class="input" placeholder="E-mail"></div><div class="form-row"><input name="senha" class="input" type="password" placeholder="Senha"></div><button class="btn">Criar</button></form></div>
```


## app/Views/cliente/profile.php
```php
<?php $c = $client; ?>
<div class="card"><div style="display:flex;justify-content:space-between;align-items:center"><div><h3><?=htmlspecialchars($c['nome']??'Usuário')?></h3><p class="small">E-mail: <?=htmlspecialchars($c['email']??'')?></p></div><div><a class="btn" href="/perfil/editar">Editar Perfil</a></div></div></div>
<div class="card"><h3>Competências</h3><ul><?php foreach($skills as $s): ?><li><?=htmlspecialchars($s['nome'])?> <form style="display:inline" method="post" action="/skill/delete"><input type="hidden" name="skill_id" value="<?=$s['id']?>"><button class="btn" style="background:#c0392b">Excluir</button></form></li><?php endforeach; ?></ul><form method="post" action="/skill/add"><input name="skill" class="input" placeholder="Ex: Frontend"><button class="btn" style="margin-top:8px">Adicionar Competência</button></form></div>
<div class="card"><h3>Publicações</h3><?php foreach($publications as $p): ?><div class="card"><p><?=htmlspecialchars($p['conteudo'])?></p><p class="small">— <?=htmlspecialchars($p['cliente']??'')?></p></div><?php endforeach; ?></div>

```


## app/Views/cliente/edit.php
```php
<div class="card" style="max-width:700px;margin:30px auto"><h2>Editar Perfil</h2><form method="post" action="/perfil/update"><div class="form-row"><label>Nome</label><input name="nome" class="input" value="<?=htmlspecialchars($client['nome']??'')?>"></div><div class="form-row"><label>Telefone</label><input name="telefone" class="input" value="<?=htmlspecialchars($client['telefone']??'')?>"></div><button class="btn">Salvar</button></form></div>
```


## app/Views/vagas/index.php
```php
<div class="card"><h2>Vagas Disponíveis</h2><?php if(empty($vagas)): ?><p class="small">Nenhuma vaga.</p><?php else: ?><div class="grid"><?php foreach($vagas as $v): ?><div class="card"><h4><?=htmlspecialchars($v['area_desejada'])?></h4><p class="small"><?=htmlspecialchars($v['localidade'])?></p><form method="post" action="/vagas/apply"><input type="hidden" name="vaga_id" value="<?=$v['idVagas']?>"><button class="btn">Candidatar-se</button></form></div><?php endforeach; ?></div><?php endif; ?></div>
```


## app/Views/courses/index.php
```php
<div class="card"><h2>Cursos</h2><div class="grid"><?php foreach($courses as $c): ?><div class="card"><h4><?=htmlspecialchars($c['titulo'])?></h4><p class="small"><?=htmlspecialchars($c['descricao'])?></p><form method="post" action="/courses/enroll"><input type="hidden" name="course_id" value="<?=$c['id']?>"><button class="btn">Inscrever-se</button></form></div><?php endforeach; ?></div></div>
```
