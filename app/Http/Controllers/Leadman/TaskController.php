<?php

namespace App\Http\Controllers\Leadman;

use App\Http\Controllers\Controller;
use App\Repositories\Leadman\TaskRepository;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    private $taskRepository;

    public function __construct(TaskRepository $taskRepository) {

        $this->taskRepository = $taskRepository;

    }

    public function index() {

        return view('leadman.task.index');

    }

    public function getTasks(Request $request) {

        $page = $request->page ? $request->page : 1;
        $count = $request->count ? $request->count : 10;
        $search = $request->search && $request->search != '' && $request->search !== 'null' ? $request->search : null;

        $params = [
            'page' => $page,
            'count' => $count,
            'search' => $search
        ];

        $tasks = $this->taskRepository->getTasks(json_decode(json_encode($params)));

        return response()->json($tasks, 200);

    }

    public function storeTask(Request $request) {

        $task = $this->taskRepository->storeTask(json_decode(json_encode($request->all())));

        return response()->json($task, 200);

    }

    public function updateTask($id, Request $request) {

        $task = $this->taskRepository->updateTask($id, json_decode(json_encode($request->all())));

        return response()->json($task, 200);

    }

    public function deleteTask($id) {

        $task = $this->taskRepository->deleteTask($id);

        return response()->json($task, 200);

    }

    public function searchTask(Request $request) {

        $search = $request->search ? $request->search : null;

        $params = [
            'search' => $search
        ];

        $task = $this->taskRepository->searchTask(json_decode(json_encode($params)));

        return response()->json($task, 200);
    }
}
