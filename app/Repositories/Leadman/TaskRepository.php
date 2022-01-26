<?php

namespace App\Repositories\Leadman;

use App\Models\Leadman\Task;
use App\Repositories\Repository;
use Carbon\Carbon;

class TaskRepository extends Repository {

    public function __construct(Task $model) {

        parent::__construct($model);

    }

    public function getTasks($params) {

        $tasks = $this->model();

        if($params->search) {

            $tasks = $tasks->where(function($query) use ($params) {
                $query->where('name', 'LIKE', "%$params->search%");
            })->orderBy('id', 'desc')->paginate($params->count, ['*'], 'page', $params->page);

            return $tasks;

        }
        $tasks = $tasks->orderBy('id', 'desc')->paginate($params->count, ['*'], 'page', $params->page);

        return $tasks;
    }

    public function storeTask($request) {

        $data = new $this->model();
        $data->name = $request->name;
        $data->description = $request->description;
        if($data->save()) {
            return $data;
        }

    }

    public function updateTask($id, $request) {

        $data = $this->model()->find($id);
        $data->name = $request->name;
        $data->description = $request->description;
        if($data->save()) {
            return $data;
        }

    }

    public function deleteTask($id) {

        $data = $this->model()->find($id);
        $data->delete();

    }

    public function searchTask($params) {

        $task = $this->model();

        if($params->search) {

            $task = $task->where(function($query) use ($params) {
                $query->where('name', 'LIKE', "%$params->search%");
            })->limit(20)->get();

            return $task;
        }

    }
}
