<?php

namespace App\Http\Controllers;

use App\Http\Repositories\ProductRepository;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Image;
use Ramsey\Uuid\Uuid;

class ProductController extends Controller
{
    private ProductRepository $productRepo;

    public function __construct()
    {
        $this->productRepo  =   new ProductRepository();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Any filter params just pass into array for sorting
        $formData   =   [
            'search' => $request->input('search')
        ];

        // Get product list
        $products   =   $this->productRepo->getProductList($formData, true);

        return view('products.list', [
            'products'  =>  $products
        ]);
    }

    /**
     * Show the form for creating/updating a resource.
     * @param int|null $id
     * @return \Illuminate\Http\Response
     */
    public function productForm(int $id = null)
    {
        $product    =   null;

        // If id is available (edit route)
        if ($id)
        {
            // Auto return exception if product not found
            $product    =   $this->productRepo->findProduct($id);
        }

        return view('products.form', [
            'product'   =>  $product
        ]);
    }

    /**
     * Show the form for creating/updating a resource.
     * @param int|null $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function productStore(Request $request, int $id = null)
    {

        $message    =   'Product is added successfully'; // default message

        $validation =   [
            'title' => 'required|max:191', // 
            'description' => 'nullable', // Optional for user
            'quantity' => 'required|integer|min:0',// Integer, no decimal
            'price' => 'required|numeric|min:0', // numeric, w/o decimal
            'images'    =>  'required',
            'images.*'    =>    'image|mimes:jpg,jpeg,png,gif'
        ];

        // If this is edit
        if ($id)
        {
            $validation['images']   =   'nullable';
            $message    =   'Product is updated successfully';
        }

        // Form validation
        $formData = $request->validate($validation);

        // If $id is null, it will treat it as new record. Otherwise.
        $product    =   $this->productRepo->productStore($formData, $id);

        if ($request->hasfile('images'))
        {

            // If this is edit action
            if ($id)
            {
                // Remove previous files and delete db record
                $productImages  =   $product->images()->get();
                foreach($productImages as $image)
                {
                    Storage::disk('public')->delete($product->id.'/'.$image->title); // unlink file
                    $image->delete(); // delete db record
                }
            }

            // Upload file
            foreach ($request->file('images') as $image)
            {
            
                $imageName  =   Uuid::uuid4().time().'.jpg'; // UUID v4 uniqueness
                $imagePath  =   $product->id.'/'.$imageName;
               
                $compressedImage = \Image::make($image)
                ->resize(700, 700, function ($constraint) { // max-width/height = 400px
                    $constraint->aspectRatio(); // remain ration
                })->stream('jpg', 100);

                // Save file to folder
                Storage::disk('public')->put($imagePath, $compressedImage);

                // Save to db
                $product->images()->save(new ProductImage([
                    "pid"   =>  $product->id,
                    "title"  =>  $imageName,
                    "url"   =>  Storage::disk('public')->url($imagePath)
                ]));
            }
        }

        return back()->with([
            'success'   =>  $message
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function productDestroy($id)
    {
        // Auto return exception if ID not found
        $this->productRepo->productDestroy($id);

        return back()->with('success', 'Product has been removed from the system');
    }
}
