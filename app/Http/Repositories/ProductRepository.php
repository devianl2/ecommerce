<?php
namespace App\Http\Repositories;

use App\Models\Product;

class ProductRepository 
{
    
    /**
     * Get product list
     * @param array $query
     * @param bool $paginate
     * @param int $limit
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Database\Eloquent\Collection
     */
    public function getProductList(array $query = [], bool $paginate = false, int $limit = null)
    {
        $search =   null;

        if (!$limit)
        {
            $limit  =   10; // default limit items
        }

        /**
        * Could add more sorting parameter. E.g:
        *   if (array_key_exists('quantity', $query) && !empty($query['quantity']))
        *   {
        *       $quantity =   $query['quantity'];
        *    }
        */
        
        // If found search parameter
        if (array_key_exists('search', $query) && !empty($query['search']))
        {
            $search =   $query['search'];
        }

        // Construct query
        $products   =   Product::with('images');

        // If search is not empty
        if ($search)
        {
            $products   =   $products->where('title', 'LIKE', '%'.$search.'%');
        }

        // If respond is request for pagination
        if ($paginate)
        {
            return $products->paginate($limit);
        }
        else
        {
            return $products->limit($limit)->get();
        }
    }

    /**
     * Find product
     * @param int $aid
     * @return Product
     */
    public function findProduct(int $pid)
    {
        return Product::with('images')->findOrFail($pid);
    }

     /**
     * Save product
     * @param array $aid
     * @param int|null $pid
     * @return Product
     */
    public function productStore(array $formData, int $pid = null) 
    {
       
        if ($pid)
        {
             // For update
            $product =   $this->findProduct($pid);
        }
        else
        {
            // For new product
            $product =   new Product();
        }

        /**
         * In some cases, you only want to modify single column like quantity, status etc. 
         * By passing necessary data into $formData array will update the respective column
         */
        if (array_key_exists('title', $formData))
        {
            $product->title = $formData['title'];
        }

        if (array_key_exists('description', $formData))
        {
            $product->description = $formData['description'];
        }

        if (array_key_exists('quantity', $formData))
        {
            $product->quantity = $formData['quantity'];
        }

        if (array_key_exists('price', $formData))
        {
            $product->price = $formData['price'];
        }

        $product->save(); // save column

        return $product;
    }

     /**
     * Delete product
     * @param int $pid
     * @return Product
     */
    public function productDestroy(int $pid)
    {
        // Auto throw exception if id not found
        $product    =   $this->findProduct($pid);
        
        // Delete associated images
        foreach($product->images as $image)
        {
            $image->delete();
        }

        // delete product
        $product->delete();

        return $product;
    }
}