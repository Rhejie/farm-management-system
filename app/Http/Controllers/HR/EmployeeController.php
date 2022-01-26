<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Http\Requests\HR\StoreEmployeeRequest;
use App\Http\Requests\HR\UpdateEmployeeRequest;
use App\Repositories\HR\EmployeeRepository;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    private $employeeRepository;
    public function __construct(EmployeeRepository $employeeRepository) {
        $this->employeeRepository = $employeeRepository;
    }

    public function index() {

        return view('employee.index');
    }

    public function getEmployees(Request $request) {

        $page = $request->page ? $request->page : 1;
        $count = $request->count ? $request->count : 10;
        $search = $request->search && $request->search != '' && $request->search !== 'null' ? $request->search : null;

        $params = [
            'page' => $page,
            'count' => $count,
            'search' => $search
        ];

        $employees = $this->employeeRepository->getEmployees(json_decode(json_encode($params)));

        return response()->json($employees, 200);
    }

    public function storeEmployee(StoreEmployeeRequest $request) {

        $employee = $this->employeeRepository->storeEmployee($request);

        return response()->json($employee, 200);

    }

    public function updateEmployee($id, UpdateEmployeeRequest $request) {

        $employee = $this->employeeRepository->updateEmployee($id, $request);

        return response()->json($employee, 200);

    }

    public function deleteEmployee($id) {

        $employee = $this->employeeRepository->deleteEmployee($id);

        return response()->json($employee, 200);

    }

    public function searchEmployee(Request $request) {

        $search = $request->search ? $request->search : null;

        $params = [
            'search' => $search
        ];

        $employee = $this->employeeRepository->searchEmployee(json_decode(json_encode($params)));

        return response()->json($employee, 200);
    }

    public function getProfile($id) {

        $employee = $this->employeeRepository->getProfile($id);

        return response()->json($employee, 200);

    }
}
