<?php

namespace App\Services;

use App\Models\ProductCategory;
use Illuminate\Http\Request;

class ProductCategoryService
{
    /**
     * Instance of ProductCategory model.
     * 
     * @var ProductCategory
     */
    private ProductCategory $productCategory;

    public function __construct(ProductCategory $productCategory)
    {
        $this->productCategory = $productCategory;
    }

    /**
     * Create the product category.
     *
     * @param Request $request
     * @return ProductCategory
     */
    public function create(Request $request): ProductCategory
    {
        $data = $request->validated();
        $data['image'] = $this->productCategory->storeImage($request->image);
        return $this->productCategory->create($data);
    }

    /**
     * Create the product category.
     *
     * @param Request $request
     * @param ProductCategory $productCategory
     * @return void
     */
    public function update(Request $request, ProductCategory $productCategory): void
    {
        $data = $request->validated();
        $data['image'] = $this->productCategory->storeImage($request->image);
        $productCategory->update($data);
    }
}
