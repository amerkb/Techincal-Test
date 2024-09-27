<?php

function checkIfDuplicatedProducts(array $products): array
{
    $merged = [];

    foreach ($products as $product) {
        $productId = $product['product_id'];
        $quantity = (int) $product['qty'];

        if (isset($merged[$productId])) {
            $merged[$productId]['qty'] += $quantity;
        } else {
            $merged[$productId] = [
                'product_id' => $productId,
                'qty' => $quantity,
            ];
        }
    }

    return array_values($merged);
}
