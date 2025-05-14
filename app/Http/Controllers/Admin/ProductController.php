<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // Untuk upload gambar
use Illuminate\Support\Facades\Validator; // Untuk validasi

class ProductController extends Controller
{
    public function index()
    {
        return response()->json(Product::orderBy('created_at', 'desc')->get());
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            // 'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,PNG,JPG|max:2048', // Validasi gambar
            'image' => 'required|image', // Validasi gambar
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $request->only(['name', 'description', 'price']);

        if ($request->hasFile('image')) {
            // Simpan gambar ke public/storage/products
            // Pastikan sudah menjalankan `php artisan storage:link`
            $path = $request->file('image')->store('products', 'public');
            $data['image_url'] = Storage::url($path); // Dapatkan URL publik
        }
        // var_dump($data, $request->hasFile('image'));
        // die;

        $product = Product::create($data);
        return response()->json($product, 201);
    }

    public function show(Product $product)
    {
        return response()->json($product);
    }

    public function update(Request $request, Product $product)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'sometimes|required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $request->only(['name', 'description', 'price']);

        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada dan bukan placeholder
            if ($product->image_url && !str_contains($product->image_url, 'placeholder.com')) {
                $oldPath = str_replace(Storage::url(''), '', $product->image_url);
                Storage::disk('public')->delete($oldPath);
            }
            $path = $request->file('image')->store('products', 'public');
            $data['image_url'] = Storage::url($path);
        } elseif ($request->input('remove_image')) { // Opsi untuk menghapus gambar tanpa mengganti
            if ($product->image_url && !str_contains($product->image_url, 'placeholder.com')) {
                $oldPath = str_replace(Storage::url(''), '', $product->image_url);
                Storage::disk('public')->delete($oldPath);
            }
            $data['image_url'] = null; // Atau set ke placeholder default
        }


        $product->update($data);
        return response()->json($product);
    }

    public function destroy(Product $product)
    {
        // Hapus gambar terkait jika ada
        if ($product->image_url && !str_contains($product->image_url, 'placeholder.com')) {
            $oldPath = str_replace(Storage::url(''), '', $product->image_url);
            Storage::disk('public')->delete($oldPath);
        }
        $product->delete();
        return response()->json(null, 204);
    }
}
