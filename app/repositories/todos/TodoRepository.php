<?php namespace app\repositories\todos;

use app\models\todo\Todo;
use app\models\todo\TodoStatusEnum;
use Illuminate\Http\Request;

class TodoRepository
{
    /**
     * @param int $id
     * @return Todo|null
     */
    public function findById(int $id)
    {
        return Todo::find($id);
    }

    public function add($username, $email, $text){
        $todo = new Todo();
        $todo->username = $username;
        $todo->email = $email;
        $todo->text = htmlspecialchars(strip_tags($text));
        $todo->status = TodoStatusEnum::NEW;
        return $todo->save();
    }

    public function update($id, $text){
        if($todo = $this->findById($id)){
            $todo->text = htmlspecialchars(strip_tags($text));
            $todo->status = TodoStatusEnum::EDITED_BY_ADMIN;
            return $todo->save();
        }
    }

    public function finish($id){
        if($todo = $this->findById($id)){
            $todo->status = TodoStatusEnum::FINISHED;
            return $todo->save();
        }
    }

    public function delete($id){
        if($todo = $this->findById($id)){
            return $todo->delete();
        }
    }

    public function count(){
        return Todo::count();
    }

    public function paginate($limit, $offset = 0){
      return Todo::limit($limit)->offset($offset)->ordered(app()->get(Request::class))->get();
    }

}
