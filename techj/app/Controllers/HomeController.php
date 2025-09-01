<?php
namespace App\Controllers;
use App\Core\Controller;
use App\Repositories\MySQL\PublicationRepository;
use App\Repositories\MySQL\CourseRepository;
class HomeController extends Controller {
    public function index(){ $pubRepo = new PublicationRepository(); $pubs = $pubRepo->all(); $courseRepo = new CourseRepository(); $courses = $courseRepo->all(); $this->view('home/index',['publications'=>$pubs,'courses'=>$courses]); }
}
