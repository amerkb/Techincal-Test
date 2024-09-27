<?php

namespace App\Repository\User;

use App\Abstract\BaseRepositoryImplementation;
use App\ApiHelper\ApiResponseCodes;
use App\ApiHelper\ApiResponseHelper;
use App\Interfaces\user\OrderInterface;
use App\Models\Order;
use App\Models\Product;
use App\Traits\MakeOrderTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderRepository extends BaseRepositoryImplementation implements OrderInterface
{
    use MakeOrderTrait;

    public function makeOrder($data)
    {
        try {

            $products = checkIfDuplicatedProducts($data['products']);
            $result = $this->validateProductQuantities($products);
            $totalPrice = 0;

            if (! $result['valid']) {
                return response()->json([
                    'message' => $result['message'],
                ], 400);
            }
            $productData = [];
            DB::beginTransaction();
            foreach ($products as $product) {
                $productModel = Product::find($product['product_id']);
                $productModel->quantity -= $product['qty'];
                $totalPrice += $productModel->price * $product['qty'];
                $productData[$product['product_id']] = [
                    'quantity' => $product['qty'],
                    'price' => $productModel->price,
                ];
                $productModel->save();
            }
            $order = $this->create(['user_id' => Auth::id(), 'total' => $totalPrice]);
            $order->Products()->sync($productData);
            DB::commit();

            return ApiResponseHelper::sendMessageResponse('CREATED', ApiResponseCodes::CREATED);

        } catch (\Exception $exception) {
            DB::rollBack();

            return ApiResponseHelper::sendMessageResponse($exception->getMessage(), 400, false);

        }
    }

    public function model()
    {
        return Order::class;
    }
}
