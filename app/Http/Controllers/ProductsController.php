<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductsController extends Controller
{
    /**
     * Show list of products.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function list()
    {
        return view('product-list');
    }

    /**
     * Show list of products.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function getListData()
    {
        $productDataTable = [];
        $products = $this->getProducts();
        foreach($products as $product) {
            $productDataTable[] = [
                'id' => $product->id,
                'name' => $product->name,
                'stock' => $product->stock,
                'price' => '$'.$product->price,
                'date' => Carbon::parse($product->date)->format('Y-m-d H:i:s'),
                'total' => '$'.$product->stock*$product->price,
            ];
        }
        return $productDataTable;
    }

    /**
     * Get the file name for the Json file.
     *
     * @return string
     */
    private function getFileDataName()
    {
        return 'products.json';;
    }

    /**
     * Get all the products
     *
     * @return \Illuminate\Support\Collection
     */
    private function getProducts()
    {
        $jsonFile = $this->getFileDataName();
        $jsonFileData = Storage::get($jsonFile);
        if($jsonFileData == '') {
            $jsonFileData = '[]';
        }
        return collect(json_decode($jsonFileData))->sortBy('date');
    }

    /**
     * Get the next avaialble Id
     *
     * @return int|mixed
     */
    private function getNextId()
    {
        $products = $this->getProducts();
        if ($products->count() == 0) {
            return 1;
        } else {
            return $products->pluck('id')->max() +1;
        }

    }

    /**
     * Saves the product
     *
     * @param Request $request
     */
    public function saveProduct(Request $request)
    {
        // @TODO:: Add Validation for form input

        $productExists = false;
        $productFormData = [
            'name' => $request->name,
            'stock' => $request->stock,
            'price' => $request->price,
            'date' => Carbon::now(),
        ];

        $jsonFile = $this->getFileDataName();
        $products = $this->getProducts();
        // Check if we have a product id then update otherwise create
        if ($request->id) {
            foreach($products as $k=>$product) {
                if ($product->id == $request->id) {
                    $productExists = true;
                    $productFormData['id'] = $request->id;
                    $products[$k] = $productFormData;
                }
            }
        }
        if (!$productExists) {
            $productFormData['id'] = $this->getNextId();
            $products[] = $productFormData;
        }

        $jsonFileData = json_encode($products);
        Storage::put($jsonFile, $jsonFileData);
    }

    /**
     * Get the Product Item Data in Json format.
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getProductData(Request $request, $id)
    {
        $products = $this->getProducts();
        return response()->json($products->firstWhere('id', $id));

    }
}
