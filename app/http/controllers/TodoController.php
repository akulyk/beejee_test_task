<?php
namespace app\http\controllers;

use app\http\requests\todos\TodoRequest;
use app\http\requests\todos\TodoUpdateRequest;
use app\http\requests\ValidationErrorsEnum;
use app\repositories\todos\TodoRepository;

class TodoController extends Controller
{
    /**
     * @var TodoRepository
     */
    private $todoRepository;


    public function __construct(TodoRepository $todoRepository)
    {
        parent::__construct();
        $this->todoRepository = $todoRepository;
    }

    public function create(){
        return $this->render('/todo/add');
    }

    public function add(TodoRequest $request){
        $username = $request->post('username');
        $email = $request->post('email');
        $content = $request->post('text');
        if($request->hasErrors()) {
            $this->session->getFlashBag()->set(ValidationErrorsEnum::TODO_VALIDATION_ERRORS, $request->errors()->getMessages());
            $this->session->getFlashBag()->set('danger','Todo validation Error!');
            return $this->redirect('/todo/create');
        }else{
            if($this->todoRepository->add($username,$email,$content)) {
                $this->session->getFlashBag()->set('success', 'Todo successfully added!');
            } else{
                $this->session->getFlashBag()->set('danger', 'Todo save error!');
                return $this->redirect('/todo/create');
            }
        }
        return $this->redirect('/');
    }

    public function update($id){
        $todo = $this->todoRepository->findById($id);

        return $this->render('/todo/update',['todo' => $todo]);
    }

    public function makeUpdate($id, TodoUpdateRequest $request){
       if($this->todoRepository->update($id,$request->post('text'))){
           $this->session->getFlashBag()->set('success', "Todo with id `{$id}`  successfully updated!");
           return $this->redirect('/');
       }
       $todo = $this->todoRepository->findById($id);
       $todo->text = $request->post('text');
        return $this->render('/todo/update',['todo' => $todo]);
    }

    public function finish($id){
        if($this->todoRepository->finish($id)){
            $this->session->getFlashBag()->set('success', "Todo with id `{$id}` successfully finished!");
        }else{
            $this->session->getFlashBag()->set('danger', "Todo with id `{$id}` got finish try error!");
        }
        return $this->redirect('/');
    }

    public function delete($id){
        if($this->todoRepository->delete($id)){
            $this->session->getFlashBag()->set('info', 'Todo deleted!');
        }else{
            $this->session->getFlashBag()->set('danger', 'Todo delete error!');
        }
        return $this->redirect('/');
    }
}
