<?php

namespace App\Repository\Admin;

use App\Abstract\BaseRepositoryImplementation;
use App\ApiHelper\ApiResponseCodes;
use App\ApiHelper\ApiResponseHelper;
use App\ApiHelper\Result;
use App\Http\Resources\ProductResource;
use App\Interfaces\Admin\ProductInterface;
use App\Models\Product;
use Illuminate\Support\Facades\Cache;

class ProductRepository extends BaseRepositoryImplementation implements ProductInterface
{
    public function model()
    {
        return Product::class;
    }

    public function storeproduct($data)
    {
        $product = $this->create($data);
        $product = ProductResource::make($product);

        return ApiResponseHelper::sendResponse(new Result($product, 'product_created'), ApiResponseCodes::CREATED);
    }

    public function updateProduct($id, $data)
    {

        $product = $this->updateById($id, $data);
        $product = ProductResource::make($product);

        return ApiResponseHelper::sendResponse(new Result($product, 'updated'));
    }

    public function deleteProduct($id)
    {
        $this->deleteById($id);

        return ApiResponseHelper::sendMessageResponse(' deleted');
    }

    public function getproduct()
    {
        $product = Cache::remember('product', 60 * 60, function () {
            return $this->get();
        });
        $product = ProductResource::collection($product);

        return ApiResponseHelper::sendResponse(new Result($product, 'get_products'));
    }
}
