<?php
namespace App\Controllers;
use App\Core\Controller;
use App\Repositories\MySQL\CourseRepository;
class CourseController extends Controller {
    public function index(){ $repo = new CourseRepository(); $courses = $repo->all(); $this->view('courses/index',['courses'=>$courses]); }
    public function enroll(){ if(empty($_SESSION['user_id'])) $this->redirect('/login'); $course=(int)($_POST['course_id']??0); $pdo=\App\Core\DB::get(); $cid=$pdo->query('SELECT idcliente FROM cliente WHERE login_idlogin='.(int)$_SESSION['user_id'])->fetchColumn(); $repo=new CourseRepository(); $repo->enroll((int)$cid,$course); $this->redirect('/courses'); }
    public function updateProgress(){ if(empty($_SESSION['user_id'])) $this->redirect('/login'); $course=(int)($_POST['course_id']??0); $progress=(int)($_POST['progress']??0); $pdo=\App\Core\DB::get(); $cid=$pdo->query('SELECT idcliente FROM cliente WHERE login_idlogin='.(int)$_SESSION['user_id'])->fetchColumn(); $repo=new CourseRepository(); $repo->updateProgress((int)$cid,$course,$progress); $this->redirect('/courses'); }
}
