<?php

namespace App\Repositories\HR;

use App\Models\HR\QrCode;
use App\Repositories\Repository;
use QrCodeGenerator;

class QrCodeRepository extends Repository {

    public function __construct(QrCode $model) {
        parent::__construct($model);
    }

    public function getQrCode($params){
        $code = $this->model()->where('is_used', $params->isUsed);
        if($params->search) {

            $code = $code->where('name', 'LIKE', "%$params->search%")->orderBy('id', 'desc')->paginate($params->count, ['*'], 'page', $params->page);

            return $code;
        }

        $code = $code->orderBy('id', 'desc')->paginate($params->count, ['*'], 'page', $params->page);

        return $code;
    }

    public function storeQRcode() {

        $number = $this->model()->orderBy('id', 'desc')->first();

        $name = 'FARM';
        if(!empty($number)) {
            $propasalNumber = $number->name;
            foreach(explode('-', $propasalNumber) as $data) {
                $form_id = (int)$data + 1;
            }
        }
        else {
            $form_id = 1;
        }

        $qr_name = $name.'-'.str_pad($form_id, 6, '0', STR_PAD_LEFT);

        $data = new $this->model();
        $data->name = $qr_name;

        if($data->save()) {
            return $data;
        }
    }

    public function generate($id) {

        $data = $this->model()->findOrFail($id);

        return $data->name;

    }

    public function verified($request) {

        $code = $this->model()->where('name', $request->name)->first();

        if(!empty($code)) {
            if($code->is_used == 1) {
                return 'already_use';
            }
            else {
                return 'ready_use';
            }
        }
        else {

            return 'used';
        }

    }

    public function updateQrCodeData($qrcode) {
        $code = $this->model()->where('name', $qrcode)->first();
        $code->is_used = 1;
        if($code->save()) {
            return 'save';
        }
    }

}
