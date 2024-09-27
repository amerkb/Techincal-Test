<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\makeOrderRequest;
use App\Interfaces\user\OrderInterface;

class OrderController extends Controller
{
    protected $order;

    public function __construct(OrderInterface $auth)
    {
        $this->order = $auth;
    }

    /**
     * @OA\Post(
     *     path="/api/user/order",
     *     tags={"Order"},
     *     summary="Create a new order",
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *             required={"products"},
     *
     *             @OA\Property(
     *                 property="products",
     *                 type="array",
     *
     *                 @OA\Items(
     *
     *                     @OA\Property(property="product_id", type="integer"),
     *                     @OA\Property(property="qty", type="integer"),
     *                 )
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=201,
     *         description="Order created successfully.",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="CREATED")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=400,
     *         description="Invalid request or insufficient stock.",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="Not enough stock for product with ID 1. Available quantity: 10.")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Product not found.",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="Product with ID 1 not found.")
     *         )
     *     )
     * )
     */
    public function makeOrder(makeOrderRequest $request)
    {
        return $this->order->makeOrder($request->validated());

    }
}
