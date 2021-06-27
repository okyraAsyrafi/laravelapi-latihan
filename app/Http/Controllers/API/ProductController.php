<?php

namespace App\Http\Controllers\API;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    public function all(Request $request)
    {
        $id = $request->input('id');
        $limit = $request->input('limit',6);
        $name = $request->input('name');
        $descriptiion = $request->input('description');
        $tags = $request->input('tags');
        $categories = $request->input('categories');
        $price_form = $request->input('price_form');
        $price_to = $request->input('price_to');

        // jika id ada 
        if ($id) {
            $product = Product::with(['productCategory', 'productGalleries'])->find($id);

            if ($product) {
                return ResponseFormatter::success(
                    $product,
                    'Data produk berhasil diambil'
                );
            } else {
                return ResponseFormatter::error(
                    null,
                    'Data produk tidak ada',
                    404
                );
            }
        }

        $product = Product::with(['productCategory', 'productGalleries']);

        if ($name) {
            $product->where('name', 'like', '%' . $name . '%');
        }

        if ($descriptiion) {
            $product->where('description', 'like', '%' . $descriptiion . '%');
        }

        if ($tags) {
            $product->where('tags', 'like', '%' . $tags . '%');
        }

        if ($price_form) {
            $product->where('price', '>=', $price_form);
        }

        if ($price_to) {
            $product->where('price', '<=', $price_to);
        }

        if($categories){
            $product->where('categories', $categories);
        }

        return ResponseFormatter::success(
            $product->paginate($limit),
            'Data list produk berhasil diambil'
        );
    }
}
