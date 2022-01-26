<?php

namespace App\Repositories\Warehouse;

use App\Models\Warehouse\Item;
use App\Repositories\Repository;

class ItemRepository extends Repository {

    public function __construct(Item $model) {

        parent::__construct($model);

    }

    public function getItems($params) {

        $items = $this->model()->with(['category']);

        if($params->search) {

            $items = $items
                ->where(function($query) use ($params) {
                    $query->orWhere('name', 'LIKE', "%$params->search%");
                    $query->orWhereHas('category', function($query) use ($params) {
                        $query->where(function($query) use ($params) {
                            $query->where('name', 'LIKE', "%$params->search%");
                        });
                    });
                })
                ->orderBy('id', 'desc')->paginate($params->count, ['*'], 'page', $params->page);

            return $items;
        }

        $items = $items->orderBy('id', 'desc')->paginate($params->count, ['*'], 'page', $params->page);

        return $items;

    }

    public function storeItem($request) {

        $data = new $this->model();
        $data->name = $request->name;
        $data->category_id = $request->category_id;
        if($data->save()) {

            return $this->model()->with(['category'])->find($data->id);

        }

    }

    public function updateItem($id, $request) {

        $data = $this->model()->find($id);

        $category_id = $request->category_id_id ? $request->category_id_id : $request->category_id;

        $data->name = $request->name;
        $data->category_id = $category_id;

        if($data->save()) {

            return $this->model()->with(['category'])->find($id);

        }

    }

    public function deleteItem($id) {

        $data = $this->model()->find($id);
        if($data) {
            $data->delete();
        }

    }

    public function searchItems($params) {

        $items = $this->model()->with(['category']);

        if($params->search) {

            $items = $items->where(function($query) use ($params) {
                $query->where('name', 'LIKE', "%$params->search%");
            })->limit(20)->get();

            return $items;
        }

    }

}
