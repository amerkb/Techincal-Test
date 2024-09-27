<?php

namespace App\Traits;

use App\Models\Product;

trait MakeOrderTrait
{
    public function validateProductQuantities(array $products): array
    {
        foreach ($products as $product) {
            $productModel = Product::find($product['product_id']);

            if ($productModel->quantity < $product['qty']) {
                return [
                    'valid' => false,
                    'message' => "Not enough stock for product with ID {$product['product_id']}. Available quantity: {$productModel->quantity}.",
                ];
            }

        }

        return [
            'valid' => true,
        ];
    }
}
