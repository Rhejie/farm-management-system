<?php

namespace App\Http\Controllers\Warehouse;

use App\Http\Controllers\Controller;
use App\Repositories\Warehouse\ItemRepository;
use Illuminate\Http\Request;

class ItemController extends Controller
{

    private $itemRepository;
    public function __construct(ItemRepository $itemRepository) {

        $this->itemRepository = $itemRepository;

    }

    public function index() {

        return view('warehouse.item.index');

    }

    public function getItems(Request $request) {

        $page = $request->page ? $request->page : 1;
        $count = $request->count ? $request->count : 10;
        $search = $request->search && $request->search != '' && $request->search !== 'null' ? $request->search : null;

        $params = [
            'page' => $page,
            'count' => $count,
            'search' => $search
        ];

        $item = $this->itemRepository->getItems(json_decode(json_encode($params)));

        return response()->json($item, 200);

    }

    public function storeItem(Request $request) {

        $item = $this->itemRepository->storeItem(json_decode(json_encode($request->all())));

        return response()->json($item, 200);

    }

    public function updateItem($id, Request $request) {

        $item = $this->itemRepository->updateItem($id, json_decode(json_encode($request->all())));

        return response()->json($item, 200);

    }

    public function deleteItem($id) {

        $item = $this->itemRepository->deleteItem($id);

        return response()->json($item, 200);

    }

    public function searchItem(Request $request) {

        $search = $request->search ? $request->search : null;

        $params = [
            'search' => $search
        ];

        $items = $this->itemRepository->searchItems(json_decode(json_encode($params)));

        return response()->json($items, 200);

    }

}
