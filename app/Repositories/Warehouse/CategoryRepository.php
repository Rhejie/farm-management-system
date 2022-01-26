<?php

namespace App\Repositories\Warehouse;

use App\Models\Warehouse\Category;
use App\Repositories\Repository;

class CategoryRepository extends Repository {

    public function __construct(Category $model) {
        parent::__construct($model);
    }

    public function getCategories($params) {

        $categories = $this->model();

        if($params->search) {

            $categories = $categories
                ->where(function($query) use ($params) {
                    $query->where('name', 'LIKE', "%$params->search%");
                })
                ->orderBy('id', 'desc')->paginate($params->count, ['*'], 'page', $params->page);

            return $categories;
        }

        $categories = $categories->orderBy('id', 'desc')->paginate($params->count, ['*'], 'page', $params->page);

        return $categories;

    }

    public function storeCategory($request) {

        $data = new $this->model();
        $data->name = $request->name;
        if($data->save()) {
            return $data;
        }

    }

    public function updateCategory($id, $request) {

        $data = $this->model()->find($id);
        $data->name = $request->name;
        if($data->save()) {
            return $data;
        }

    }

    public function deleteCategory($id) {

        $data = $this->model()->find($id);
        if($data) {
            $data->delete();
        }

    }

    public function searchCategories($params) {

        $categories = $this->model();

        if($params->search) {

            $categories = $categories->where(function($query) use ($params) {
                $query->where('name', 'LIKE', "%$params->search%");
            })->limit(20)->get();

            return $categories;
        }

    }

}
