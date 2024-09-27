<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductRequest;
use App\Interfaces\Admin\ProductInterface;

/**
 * @OA\Tag(
 *     name="Product",
 *     description="Product operations for admin"
 * )
 */

/**
 * @OA\Schema(
 *     schema="Product",
 *     type="object",
 *     required={"id", "name", "price", "quantity"},
 *
 *     @OA\Property(property="id", type="string", example="12345"),
 *     @OA\Property(property="name", type="string", example="Product Name"),
 *     @OA\Property(property="price", type="number", format="float", example=99.99),
 *     @OA\Property(property="description", type="string", example="A brief description of the product."),
 *     @OA\Property(property="quantity", type="integer", example=10)
 * )
 */
class ProductController extends Controller
{
    /**
     * @OA\Schema(
     *     schema="ProductRequest",
     *     type="object",
     *     required={"name", "price", "quantity"},
     *
     *     @OA\Property(property="name", type="string", example="Product Name"),
     *     @OA\Property(property="price", type="number", format="float", example=99.99),
     *     @OA\Property(property="description", type="string", example="A brief description of the product."),
     *     @OA\Property(property="quantity", type="integer", example=10)
     * )
     */
    public function __construct(ProductInterface $product)
    {
        $this->product = $product;
    }

    /**
     * @OA\Get(
     *     path="/api/product",
     *     tags={"Product"},
     *     summary="Get list of products",
     *
     *     @OA\Response(
     *         response=200,
     *         description="List of products",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="get_products"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *
     *                 @OA\Items(ref="#/components/schemas/Product")
     *             )
     *         )
     *     )
     * )
     */
    public function index()
    {
        return $this->product->getproduct();
    }

    /**
     * @OA\Post(
     *     path="/api/admin/product",
     *     tags={"Product"},
     *     summary="Store a new product",
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(ref="#/components/schemas/ProductRequest")
     *     ),
     *
     *     @OA\Response(
     *         response=201,
     *         description="Product created successfully",
     *
     *         @OA\JsonContent(ref="#/components/schemas/Product")
     *     ),
     *
     *     @OA\Response(
     *         response=422,
     *         description="Validation Error",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="Validation error message.")
     *         )
     *     )
     * )
     */
    public function store(ProductRequest $request)
    {
        return $this->product->storeproduct($request->validated());
    }

    /**
     * @OA\Put(
     *     path="/api/admin/product/{id}",
     *     tags={"Product"},
     *     summary="Update a product",
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the product to update",
     *
     *         @OA\Schema(type="string")
     *     ),
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(ref="#/components/schemas/ProductRequest")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Product updated successfully",
     *
     *         @OA\JsonContent(ref="#/components/schemas/Product")
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Product not found",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="Product not found.")
     *         )
     *     )
     * )
     */
    public function update(string $id, ProductRequest $request)
    {
        return $this->product->updateproduct($id, $request->validated());
    }

    /**
     * @OA\Delete(
     *     path="/api/admin/product/{id}",
     *     tags={"Product"},
     *     summary="Delete a product",
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the product to delete",
     *
     *         @OA\Schema(type="string")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Product deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Product not found",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="Product not found.")
     *         )
     *     )
     * )
     */
    public function destroy(string $id)
    {
        return $this->product->deleteproduct($id);
    }
}
