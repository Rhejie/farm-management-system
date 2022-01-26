<?php

namespace App\Http\Controllers\Warehouse;

use App\Http\Controllers\Controller;
use App\Repositories\Warehouse\StockRepository;
use Illuminate\Http\Request;

class StockController extends Controller
{

    private $stockRepository;
    public function __construct(StockRepository $stockRepository) {

        $this->stockRepository = $stockRepository;

    }

    public function index() {

        return view('warehouse.stock.index');

    }

    public function getStocks(Request $request) {

        $page = $request->page ? $request->page : 1;
        $count = $request->count ? $request->count : 10;
        $search = $request->search && $request->search != '' && $request->search !== 'null' ? $request->search : null;

        $params = [
            'page' => $page,
            'count' => $count,
            'search' => $search
        ];

        $stocks = $this->stockRepository->getStocks(json_decode(json_encode($params)));

        return response()->json($stocks, 200);

    }

    public function storeStock(Request $request) {

        $stock = $this->stockRepository->storeStock(json_decode(json_encode($request->all())));

        return response()->json($stock, 200);

    }

    public function updateStock($id, Request $request) {

        $stock = $this->stockRepository->updateStock($id, json_decode(json_encode($request->all())));

        return response()->json($stock, 200);

    }

    public function deleteStock($id) {

        $stock = $this->stockRepository->deleteStock($id);

        return response()->json($stock, 200);

    }

    public function restock($id, Request $request) {

        $stock = $this->stockRepository->reStock($id, json_decode(json_encode($request->all())));

        return response()->json($stock, 200);
    }

}
