<?php

namespace App\Repositories\Warehouse;

use App\Models\Warehouse\Deploy;
use App\Repositories\Repository;
use Carbon\Carbon;

class DeployRepository extends Repository {

    private $stockRepository;

    public function __construct(Deploy $model, StockRepository $stockRepository) {

        $this->stockRepository = $stockRepository;
        parent::__construct($model);

    }

    public function getDeploy($params) {

        $deploy = $this->model()->with(['area', 'stock', 'item'])
                ->where(\DB::raw("(DATE_FORMAT(date,'%d-%m-%Y'))"), (new Carbon($params->date))->format('d-m-Y'));

        if($params->search) {

            $deploy = $deploy->where(function ($qeury) use ($params) {
                $qeury->orWhereHas('stock', function ($qeury) use ($params) {
                    $qeury->whereHas('item', function ($qeury) use ($params) {
                        $qeury->where('name', 'LIKE', "%$params->search%");
                    });
                });
                $qeury->orWhereHas('area', function ($qeury) use ($params) {
                    $qeury->where(function ($qeury) use ($params) {
                        $qeury->where('name', 'LIKE', "%$params->search%");
                    });
                });
                $qeury->orWhereHas('item', function ($qeury) use ($params) {
                    $qeury->where(function ($qeury) use ($params) {
                        $qeury->where('name', 'LIKE', "%$params->search%");
                    });
                });
            })->orderBy('id', 'desc')->paginate($params->count, ['*'], 'page', $params->page);

            return $deploy;

        }

        $deploy = $deploy->orderBy('id', 'desc')->paginate($params->count, ['*'], 'page', $params->page);

        return $deploy;

    }

    public function storeDeploy($request) {

        $data = new $this->model();
        $data->stock_id = $request->stock_id;
        $data->item_id = $request->item_id;
        $data->area_id = $request->area_id;
        $data->quantity = $request->quantity;
        $data->date = $request->date;

        if($data->save()) {

            $deploy = [
                'id' => $data->stock_id,
                'quantity' => $data->quantity,
            ];

            $this->stockRepository->deducQuantityAddUsed(json_decode(json_encode($deploy)));

            return $this->model()->with(['area', 'stock', 'item'])->find($data->id);

        }

    }

    public function updateDeploy($id, $request) {

        $data = $this->model()->find($id);

        $stock_id = $request->stock_id_id ? $request->stock_id_id : $request->stock_id;
        $area_id = $request->area_id_id ? $request->area_id_id : $request->area_id;


        $data->stock_id = $stock_id;
        $data->area_id = $area_id;
        $data->item_id = $request->item_id;
        $data->quantity = $request->quantity;
        $data->date = $request->date;

        if($data->save()) {

            return $this->model()->with(['area', 'stock', 'item'])->find($id);
        }

    }

    public function deleteDeploy($id) {

        $data = $this->model()->find($id);

        if($data->delete()) {
            return 'delete';
        }

    }
}
