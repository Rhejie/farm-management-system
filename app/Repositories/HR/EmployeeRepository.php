<?php

namespace App\Repositories\HR;

use App\Models\HR\Employee;
use App\Repositories\Repository;

class EmployeeRepository extends Repository {

    private $qrCodeRepository;
    public function __construct(Employee $model, QrCodeRepository $qrCodeRepository) {
        $this->qrCodeRepository = $qrCodeRepository;
        parent::__construct($model);

    }

    public function getEmployees($params) {

        $employees = $this->model();

        if($params->search) {

            $employees = $employees->where(function ($query) use ($params) {
                $query->orWhere('firstname', 'like', "%$params->search%");
                $query->orWhere('middlename', 'like', "%$params->search%");
                $query->orWhere('lastname', 'like', "%$params->search%");
                $query->orWhere('position', 'like', "%$params->search%");
                $query->orWhere('gender', 'like', "%$params->search%");
                $query->orWhere('birthday', 'like', "%$params->search%");
                $query->orWhere('suffix', 'like', "%$params->search%");
            })->orderBy('id', 'desc')->paginate($params->count, ['*'], 'page', $params->page);

            return $employees;

        }

        $employees = $employees->orderBy('id', 'desc')->paginate($params->count, ['*'], 'page', $params->page);

        return $employees;

    }

    public function storeEmployee($request) {

        $data = new $this->model();

        $data->firstname = $request->firstname;
        $data->lastname = $request->lastname;
        $data->middlename = $request->middlename;
        $data->suffix = $request->suffix;
        $data->birthday = $request->birthday;
        $data->gender = $request->gender;
        $data->contact = $request->contact;
        $data->position = $request->position;
        $data->address = $request->address;
        $data->qrcode = $request->qrcode;



        if($request->hasFile('file')) {
            $folder = '/employee/profile/';
            $media = $request->file('file');
            $name = time() . '.' . $media->extension();
            $link = $media->storeAs($folder, $name, 'public');

            $data->profile = $name;
            $data->profile_path = $folder;
            $data->profile_name = $request->file_name;
        }

        $this->qrCodeRepository->updateQrCodeData($data->qrcode);

        if($data->save()) {

            return $data;
        }

    }

    public function updateEmployee($id, $request) {

        $data = $this->model()->find($id);

        $data->firstname = $request->firstname;
        $data->lastname = $request->lastname;
        $data->middlename = $request->middlename;
        $data->suffix = $request->suffix;
        $data->birthday = $request->birthday;
        $data->gender = $request->gender;
        $data->contact = $request->contact;
        $data->position = $request->position;
        $data->address = $request->address;

        if($request->hasFile('file')) {

            \Storage::disk('public')->delete("/employee/profile/". $data->profile);

            $folder = '/employee/profile/';
            $media = $request->file('file');
            $name = time() . '.' . $media->extension();
            $link = $media->storeAs($folder, $name, 'public');


            $data->profile = $name;
            $data->profile_path = $folder;
            $data->profile_name = $request->file_name;
        }
        else if($request->remove_file) {

            \Storage::disk('public')->delete("/employee/profile/". $data->profile);
            $data->profile = '';
            $data->profile_path = '';
            $data->profile_name = '';
        }

        if($data->save()) {

            return $data;
        }
    }

    public function deleteEmployee($id) {
        $data = $this->model()->find($id);
        if($data) {
            $data->delete();
        }
    }

    public function searchEmployee($params) {

        $employee = $this->model();

        if($params->search) {

            $employee = $employee->where(function ($query) use ($params) {
                $query->orWhere('firstname', 'LIKE', "%$params->search%");
                $query->orWhere('lastname', 'LIKE', "%$params->search%");
            })->limit(20)->get();

            return $employee;

        }

    }

    public function findEmployeeByQrCode($qrcode) {

        $employee = $this->model()->where('qrcode', $qrcode)->first();
        if(empty($employee)) {
            return 'no_employee';
        }
        return $employee;

    }

    public function getProfile($id) {
        $employee = $this->model()->find($id);

        return $employee;
    }
}
