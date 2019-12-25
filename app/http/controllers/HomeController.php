<?php
namespace app\http\controllers;

use app\repositories\todos\TodoRepository;
use Illuminate\Http\Request;

class HomeController extends Controller
{

    public function index(Request $request, TodoRepository $todoRepository)
    {
        $page      = ($request->get('page', 0) > 0) ? $request->get('page') : 1;
        $limit     = 3; // Number of posts on one page
        $skip      = ($page - 1) * $limit;
        $count     = $todoRepository->count(); // Count of all available posts


       return $this->renderer->render('home',[
           'pagination'    => [
               'needed'        => $count > $limit,
               'count'         => $count,
               'page'          => $page,
               'lastpage'      => (ceil($count / $limit) == 0 ? 1 : ceil($count / $limit)),
               'limit'         => $limit,
           ],
           // return list of Posts with Limit and Skip arguments
           'todos'         => $todoRepository->paginate($limit,$skip),
       ]);
    }

}
