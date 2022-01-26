<?php

namespace App\Repositories\Leadman;

use App\Models\Leadman\Harvest;
use App\Repositories\Repository;
use Carbon\Carbon;

class HarvestRepository extends Repository {
    public function __construct(Harvest $model) {
        parent::__construct($model);
    }

    public function getHarvest($params) {

        $harvest =  $this->model()->with('area');

        if($params->search) {

            $harvest = $harvest->where(function($query) use ($params) {
                $query->whereHas('area', function($query) use ($params) {
                    $query->where(function($query) use ($params) {
                        $query->where('name', 'LIKE', "%$params->search%");
                    });
                });
            })->orderBy('id', 'desc')->paginate($params->count, ['*'], 'page', $params->page);

            return $harvest;

        }

        $harvest = $harvest->orderBy('id', 'desc')->paginate($params->count, ['*'], 'page', $params->page);

        return $harvest;

    }

    public function storeHarvest($request) {

        $data = new $this->model();

        $data->arae_id = $request->area_id;
        $data->stem_cut = $request->stem_cut;
        $data->date = $request->date;

        if($data->save()) {
            return $this->model()->with(['area'])->find($data->id);
        }

    }

    public function updateHarvest($id, $request) {

        $data = $this->model()->find($id);
        $arae_id = $request->area_id_id ? $request->area_id_id : $request->area_id;

        $data->arae_id = $arae_id;
        $data->stem_cut = $request->stem_cut;
        $data->date = $request->date;

        if($data->save()) {
            return $this->model()->with(['area'])->find($id);
        }

    }

    public function deleteHarvest($id) {

        $data = $this->model()->find($id);

        $data->delete();

    }
}
