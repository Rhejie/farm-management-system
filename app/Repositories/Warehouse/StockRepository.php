<?php

namespace App\Repositories\Warehouse;

use App\Models\Warehouse\Stock;
use App\Repositories\Repository;

class StockRepository extends Repository {

    public function __construct(Stock $model) {

        parent::__construct($model);

    }

    public function getStocks($params) {

        $stocks = $this->model()->with(['item' => function ($query) {
            $query->with(['category']);
        }]);

        if($params->search) {

            $stocks = $stocks->where(function ($query) use ($params) {
                $query->orWhere('quantity', 'LIKE', "%$params->search%");
                $query->orWhereHas('item', function ($query) use ($params) {
                    $query->where(function ($query) use ($params) {
                        $query->orWhere('name', 'LIKE', "%$params->search%");
                        $query->orWhereHas('category', function ($query) use ($params) {
                            $query->where(function ($query) use ($params) {
                                $query->where('name', 'LIKE', "%$params->search%");
                            });
                        });
                    });
                });
            })->orderBy('id', 'desc')->paginate($params->count, ['*'], 'page', $params->page);

            return $stocks;

        }

        $stocks = $stocks->orderBy('id', 'desc')->paginate($params->count, ['*'], 'page', $params->page);

        return $stocks;

    }

    public function storeStock($request) {

        $data = new $this->model();
        $data->item_id = $request->item_id;
        $data->quantity = $request->quantity;
        $data->unit = $request->unit;
        if($data->save()) {

            return $this->model()->with(['item' => function ($query) {
                $query->with(['category']);
            }])->find($data->id);

        }

    }

    public function updateStock($id, $request) {

        $data = $this->model()->find($id);
        $item_id = $request->item_id_id ? $request->item_id_id : $request->item_id;

        $data->item_id = $item_id;
        $data->quantity = $request->quantity;
        $data->unit = $request->unit;
        if($data->save()) {
            return $this->model()->with(['item' => function ($query) {
                $query->with(['category']);
            }])->find($id);
        }

    }

    public function deleteStock($id) {

        $data = $this->model()->find($id);
        if($data) {
            $data->delete();
        }

    }

    public function reStock($id, $request) {
        $data = $this->model()->find($id);

        $item_id = $request->item_id_id ? $request->item_id_id : $request->item_id;

        $data->item_id = $item_id;
        $data->quantity += $request->quantity;
        if($data->save()) {
            return $this->model()->with(['item' => function ($query) {
                $query->with(['category']);
            }])->find($id);
        }
    }

    public function deducQuantityAddUsed($request) {

        $data = $this->model()->find($request->id);
        $data->quantity -= $request->quantity;
        $data->used += $request->quantity;
        if($data->save()) {

            return 'success';

        }


    }

}
