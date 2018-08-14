<?php

namespace App\Http\Controllers;

use File;
use Image;
use App\Product;
use App\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->orderBy('created_at', 'DESC')->paginate(10);
        return view('products.index', compact('products'));
        // return $products;  
    }

    public function create()
    {
        $categories = Category::orderBy('name', 'ASC')->get();
        return view('products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'code' => 'required|string|max:10|unique:products',
            'name' => 'required|string|max:100',
            'description' => 'nullable|string|max:100',
            'stock' => 'required|integer',
            'price' => 'required|integer',
            'category_id' => 'required|exists:categories,id',
            'photo' => 'nullable|image|mimes:jpg,png,jpeg'
        ]);

        try {
            //default $photo = null
            $photo = null;
            //jika terdapat file yang dikirim
            if ($request->hasFile('photo')) {
            //maka menjalankan method savefile()
                $photo = $this->saveFile($request->name, $request->file('photo'));
            }

        // simpan data ke dalam table product
        $product = Product::create([
            'code' => $request->code,
            'name' => $request->name,
            'description' => $request->description,
            'stock' => $request->stock,
            'price' => $request->price,
            'category_id' => $request->category_id,
            'photo' => $photo
        ]);

            //jika berhasil redirect ke produk.index
            return redirect(route('produk.index'))
            ->with(['success' => '<strong>' . $product->name . '</strong> Ditambahkan']);
        } catch (\Exception $e) {
            //jika gagal dikembalikan ke halaman yg sama ditambahkan tampilan error
            return redirect()->back()
                ->with(['error' => $e->getMessage()]);
        }
    }

    private function saveFile($name, $photo)
    {
        //set nama file adalah gabungan dari nama file dan waktunya ekstensi ttetap dipertahankan
        $images = str_slug($name) . time() . '.' . $photo->getClientOriginalExtension();
        //set path untuk menyimpan gambar
        $path = public_path('uploads/product');

        //cek jika uploads/product bukan direktori / folder
        if(!File::isDirectory($path)) {
            //maka folder tersebut dibuat
            File::makeDirectory($path, 0777, true, true);
        }
        //simpan gambar yang diupload ke folder uploads/produk
        Image::make($photo)->save($path . '/' . $images);
        // mengembalikan nama file yg ditampung divariable $images
        return $images;
    }

    public function destroy($id)
    {
        //melakukan query select berdasarkan id
        $products = Product::findOrFail($id);
        //mengecek apakah photo ada atau tidak
        if (!empty($products->photo)) {
            //file akan dihapus dari foldernya
            File::delete(public_path('uploads/product/' . $products->photo));
        }

        //hapus data dari produk
        $products->delete();
        return redirect()->back()->with(['success' => '<strong>' . $products->name . '</strong> telah dihapus!']);
    }

    public function edit($id)
    {
        //query select berdasarkan id
        $product = Product::findOrFail($id);
        $categories = Category::orderBy('name', 'ASC')->get();
        return view('products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, $id)
    {
        //validasi
        $this->validate($request, [
            'code' => 'required|string|max:10|exists:products,code',
            'name' => 'required|string|max:100',
            'description' => 'nullable|string|max:100',
            'stock' => 'required|integer',
            'price' => 'required|integer',
            'category_id' => 'required|exists:categories,id',
            'photo' => 'nullable|image|mimes:jpg,png,jpeg'
        ]);

        try {
            //query select berdasarkan id
            $product = Product::findOrFail($id);
            $photo = $product->photo;


            //cek jika ada file yg dikirim melalui form
            if ($request->hasFile('photo')) {
                //cek, jika photo tidak kosong  maka file yang ada di dalam folder uploads/products akan dihapus
                !empty($photo) ? File::delete(public_path('uploads/product/' . $photo)):null;
                //uploading file dengan menggunakan method saveFile() yg telah dibuat sebelumnya
                $photo = $this->saveFile($request->name, $request->file('photo'));
            }

            // memperbarui database
            $product->update([
                'name' => $request->name,
                'description' => $request->description,
                'stock' => $request->stock,
                'price' => $request->price,
                'category_id' => $request->category_id,
                'photo' => $photo
            ]);

            // $product->update($request->all());
            // $product->photo = $photo;
            // $product->save();

            return redirect(route('produk.index'))
                ->with(['success' => '<strong>' . $product->name . '</strong> Telah diubah!']);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with(['errror' => $e->getMessage()]);
        }
    }
}
