<?php

namespace App\Http\Controllers\Warehouse;

use App\Http\Controllers\Controller;
use App\Repositories\Warehouse\CategoryRepository;
use Illuminate\Http\Request;

class CategoryController extends Controller
{

    private $categoryRepository;
    public function __construct(CategoryRepository $categoryRepository) {

        $this->categoryRepository = $categoryRepository;

    }

    public function index() {

        return view('warehouse.category.index');

    }

    public function getCategories(Request $request) {

        $page = $request->page ? $request->page : 1;
        $count = $request->count ? $request->count : 10;
        $search = $request->search && $request->search != '' && $request->search !== 'null' ? $request->search : null;

        $params = [
            'page' => $page,
            'count' => $count,
            'search' => $search
        ];

        $categroies = $this->categoryRepository->getCategories(json_decode(json_encode($params)));

        return response()->json($categroies, 200);

    }

    public function storeCategory(Request $request) {

        $category = $this->categoryRepository->storeCategory(json_decode(json_encode($request->all())));

        return response()->json($category, 200);

    }

    public function updateCategory($id, Request $request) {

        $category = $this->categoryRepository->updateCategory($id, json_decode(json_encode($request->all())));

        return response()->json($category, 200);

    }

    public function deleteCategory($id) {

        $category = $this->categoryRepository->deleteCategory($id);

        return response()->json($category, 200);

    }

    public function searchCategory(Request $request) {

        $search = $request->search ? $request->search : null;

        $params = [
            'search' => $search
        ];

        $categories = $this->categoryRepository->searchCategories(json_decode(json_encode($params)));

        return response()->json($categories, 200);

    }

}
