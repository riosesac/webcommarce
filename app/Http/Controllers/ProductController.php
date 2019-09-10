<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Session;
use App\Category;
use App\Product;
use App\ProductsAttribute;
use App\ProductsImage;
use Illuminate\Support\Facades\Input;
use Image;
use App\IndexContraller;

class ProductController extends Controller
{
    public function addProduct(Request $request){

    	if ($request->isMethod('post')) {
    		$data = $request->all();
    		//echo "<pre>"; print_r($data); die;
    		if (empty($data['category_id'])) {
    			return redirect()->back()->with('flash_message_error','Produk gagal bertambah');
    		}
    		$product = new Product;
    		$product->category_id = $data['category_id'];
    		$product->product_name = $data['product_name'];
    		$product->product_code = $data['product_code'];
    		$product->product_color = $data['product_color'];
    		if (!empty($data['description'])) {
    			$product->description = $data['description'];	
    		}else{
    			$product->description = '';
    		}
            //care
            if (!empty($data['care'])) {
                $product->care = $data['care'];   
            }else{
                $product->care = '';
            }
    		$product->price = $data['price'];
    		//upload image
    		if ($request->hasFile('image')) {
    			$image_tmp = Input::file('image');
    			if ($image_tmp->isValid()) {
    				//echo "test";die;
    				//Atur Ukuran image
    				$extension = $image_tmp->getClientOriginalExtension();
    				$filename = rand(111,99999).'.'.$extension;
    				$large_image_path = 'images/backend_images/products/large/'.$filename;
    				$medium_image_path = 'images/backend_images/products/medium/'.$filename;
    				$small_image_path = 'images/backend_images/products/small/'.$filename;

    				Image::make($image_tmp)->save($large_image_path);
    				Image::make($image_tmp)->resize(600,600)->save($medium_image_path);
    				Image::make($image_tmp)->resize(300,300)->save($small_image_path);

    				//penyimpanan nama image di tabel produk
    				$product->image = $filename;
    			}
    		}
    		$product->save();
    		return redirect('/admin/view-products')->with('flash_message_success','Produk sukses bertambah');
    	}

    	$categories = Category::where(['parrent_id' => 0])->get();
    	$categories_dropdown = "<option value='' selected disabled>Select</option>";
    	//untuk memunculkan category
    	foreach ($categories as $cat) {
    		$categories_dropdown .= "<option value='".$cat->id."'>".$cat->name."</option>";
    		$sub_categories = Category::where(['parrent_id' => $cat->id])->get();
    		//untuk memunculkan sub category
    		foreach ($sub_categories as $sub_cat) {
    			$categories_dropdown .= "<option value = '".$sub_cat->id."'>&nbsp;--&nbsp;".$sub_cat->name."</option>";
    		}
    	}
    	return view('admin.products.add_product')->with(compact('categories_dropdown'));
    }

    public function editProduct(Request $request, $id=null){

    	if ($request->isMethod('post')) {
    		$data = $request->all();
    		//echo "<pre>"; print_r($data); die;

    		//upload image
    		if ($request->hasFile('image')) {
    			$image_tmp = Input::file('image');
    			if ($image_tmp->isValid()) {
    				//echo "test";die;
    				//Atur Ukuran image
    				$extension = $image_tmp->getClientOriginalExtension();
    				$filename = rand(111,99999).'.'.$extension;
    				$large_image_path = 'images/backend_images/products/large/'.$filename;
    				$medium_image_path = 'images/backend_images/products/medium/'.$filename;
    				$small_image_path = 'images/backend_images/products/small/'.$filename;

    				Image::make($image_tmp)->save($large_image_path);
    				Image::make($image_tmp)->resize(600,600)->save($medium_image_path);
    				Image::make($image_tmp)->resize(300,300)->save($small_image_path);

    			}
    		}else{
    			$filename = $data['current_image'];
    		}

    		if (empty($data['description'])) {
    			$data['description'] = '';
    		}

            if (empty($data['care'])) {
                $data['care'] = '';
            }

    		Product::where(['id' => $id])->update(['category_id'=>$data['category_id'],'product_name'=>$data['product_name'],'product_code'=>$data['product_code'],'product_color'=>$data['product_color'],'description'=>$data['description'],'care'=>$data['care'],'price'=>$data['price'],'image'=>$filename]);
    		return redirect('/admin/view-products')->with('flash_message_success','Product berhasil diperbaharui');
    	}

    	$productDetails = Product::where(['id'=>$id])->first();

    	$categories = Category::where(['parrent_id' => 0])->get();
    	$categories_dropdown = "<option value='' selected disabled>Select</option>";
    	//untuk memunculkan category
    	foreach ($categories as $cat) {
    		if ($cat->id == $productDetails->category_id) {
    			$selected = "selected";
    		}else{
    			$selected ="";
    		}
    		$categories_dropdown .= "<option value='".$cat->id."' ".$selected.">".$cat->name."</option>";
    		$sub_categories = Category::where(['parrent_id' => $cat->id])->get();
    		//untuk memunculkan sub category
    		foreach ($sub_categories as $sub_cat) {
	    		if ($sub_cat->id == $productDetails->category_id) {
	    			$selected = "selected";
	    		}else{
	    			$selected ="";
	    		}
    			$categories_dropdown .= "<option value = '".$sub_cat->id."' ".$selected.">&nbsp;--&nbsp;".$sub_cat->name."</option>";
    		}
    	}
    	return view('admin.products.edit_product')->with(compact('categories_dropdown','productDetails'));
    }

    public function deleteProductImage($id = null){
        
        //ke nama produk image 
        $productImage = Product::where(['id'=>$id])->first();
        //echo $productImage->image; die;
        
        //ke path produk image
        $large_image_path = 'images/backend_images/products/large/';
        $medium_image_path = 'images/backend_images/products/medium/';
        $small_image_path = 'images/backend_images/products/small/';
        
        //delete image
        if (file_exists($large_image_path.$productImage->image)) {
            unlink($large_image_path.$productImage->image);
        }
        if (file_exists($medium_image_path.$productImage->image)) {
            unlink($medium_image_path.$productImage->image);
        }
        if (file_exists($small_image_path.$productImage->image)) {
            unlink($small_image_path.$productImage->image);
        }

        //Deleteimage dari tabel products
    	Product::where(['id'=>$id])->update(['image'=>'']);
    	return redirect()->back()->with('flash_message_success','Product Image telah terhapus!!');
    }

    public function deleteProduct($id = null){
    	Product::where(['id'=>$id])->delete();
    	return redirect()->back()->with('flash_message_success','Product telah terhapus!!');
    }

    public function viewProducts(){
    	$products = Product::orderby('id','DESC')->get();
    	$products = json_decode(json_encode($products));
    	foreach ($products as $key => $val) {
    		$category_name = Category::where(['id'=>$val->category_id])->first();
    		$products[$key]->category_name = $category_name->name;
    	}
    	return view('admin.products.view_products')->with(compact('products'));
    }

    public function addAttributes(Request $request, $id = null){
        $productDetails = Product::with('attributes')->where(['id' => $id])->first();
        //$productDetails = json_decode(json_encode($productDetails));
        //echo "<pre>"; print_r($productDetails); die;

        if($request->isMethod('post')){
            $data = $request->all();
            //echo "<pre>"; print_r($data); die;
            foreach ($data['sku'] as $key => $value) {
                if(!empty($value)){

                    // untuk cek SKU
                    $attrCountSKU = ProductsAttribute::where('sku',$value)->count();
                    if ($attrCountSKU > 0) {
                        return redirect('admin/add-attributes/'.$id)->with('flash_message_error','Gagal, '.$value.' sudah terdaftar');
                    }

                    //untuk cek size
                    $attrCountSize = ProductsAttribute::where(['product_id' => $id, 'size' => $data['size'][$key]])->count();
                    if ($attrCountSize > 0) {
                        return redirect('admin/add-attributes/'.$id)->with('flash_message_error','Gagal, '.$data['size'][$key].' sudah terdaftar');
                    }

                    $attribute = new ProductsAttribute;
                    $attribute->product_id = $id;
                    $attribute->sku = $value;
                    $attribute->size = $data['size'][$key];
                    $attribute->price = $data['price'][$key];
                    $attribute->stock = $data['stock'][$key];
                    $attribute->save();
                }
            }
            return redirect('admin/add-attributes/'.$id)->with('flash_message_success','Sukses Menambah data Produk Atribut');
        }

        return view('admin.products.add_attributes')->with(compact('productDetails'));
    }

    public function editAttributes(Request $request, $id = null){
        if ($request->isMethod('post')) {
            # code...
            $data = $request->all();
            //echo "<pre>"; print_r($data); die;
            foreach ($data['idAttr'] as $key => $attr) {
                # code...
                ProductsAttribute::where(['id'=>$data['idAttr'][$key]])->update(['price'=>$data['price'][$key],'stock'=>$data['stock'][$key]]);
            }
            return redirect()->back()->with('flash_message_success','produk atribut berhasil terupdate');
        }
    }

    public function addImages(Request $request, $id = null){
        $productDetails = Product::with('attributes')->where(['id' => $id])->first();

        if($request->isMethod('post')){
            $data = $request->all();
            //echo "<pre>"; print_r($data); die;
            if ($request->hasFile('image')) {
                $files = $request->file('image');
                //echo "<pre>"; print_r($files); die;
                foreach ($files as $file) {
                    # code...
                    $image = new ProductsImage;
                    $extension = $file->getClientOriginalExtension();
                    $filename = rand(111,9999).'.'.$extension;
                    $large_image_path = 'images/backend_images/products/large/'.$filename;
                    $medium_image_path = 'images/backend_images/products/medium/'.$filename;
                    $small_image_path = 'images/backend_images/products/small/'.$filename;
                    Image::make($file)->save($large_image_path);
                    Image::make($file)->resize(600,600)->save($medium_image_path);
                    Image::make($file)->resize(300,300)->save($small_image_path);
                    $image->image = $filename;
                    $image->product_id = $data['Product_id'];
                    $image->save();                    
                }
            }
            return redirect('admin/add-images/'.$id)->with('flash_message_success','Foto Produk sukses ditambah');
        }

        $productsImages = ProductsImage::where(['product_id'=>$id])->get();

        return view('admin.products.add_images')->with(compact('productDetails','productsImages'));
    }

    public function deleteAttributes(Request $request, $id = null){
        ProductsAttribute::where(['id'=>$id])->delete();
        return redirect()->back()->with('flash_message_success','attribt terhapus');
    }

    public function deleteAltImages($id = null){
        //ke nama produk image 
        $productImage = ProductsImage::where(['id'=>$id])->first();
        //echo $productImage->image; die;
        
        //ke path produk image
        $large_image_path = 'images/backend_images/products/large/';
        $medium_image_path = 'images/backend_images/products/medium/';
        $small_image_path = 'images/backend_images/products/small/';
        
        //delete image
        if (file_exists($large_image_path.$productImage->image)) {
            unlink($large_image_path.$productImage->image);
        }
        if (file_exists($medium_image_path.$productImage->image)) {
            unlink($medium_image_path.$productImage->image);
        }
        if (file_exists($small_image_path.$productImage->image)) {
            unlink($small_image_path.$productImage->image);
        }

        //Deleteimage dari tabel products
        ProductsImage::where(['id'=>$id])->delete();
        return redirect()->back()->with('flash_message_success','Product Alternative Image telah terhapus!!');
    }

    public function products($url = null){

        // Menampilkan 404 ketika url kategori tidak terdaftar
        $countCategory = Category::where(['url'=>$url, 'status'=>1 ])->count();
        //echo $countCategory; die;
        if ($countCategory==0) {
            abort(404);
        }
        //pergi ke semua kategori dan sub kategori
        $categories = Category::with('categories')->where(['parrent_id'=>0])->get();
        //echo $url; die;
        $categoryDetails = Category::where(['url' => $url])->first();

        if ($categoryDetails->parrent_id==0) {
            //if url is main category url
            $subCategories = Category::where(['parrent_id'=>$categoryDetails->id])->get();
            //$cat_ids = "";
            foreach ($subCategories as $subcat) {
                //if ($key==1) $cat_ids .= ",";
                $cat_ids[] = $subcat->id;
            }
            //print_r($cat_ids); die;
            //echo $cat_ids; die;

            //untuk menampilkan semua sub kategori dari kategori
            $productsAll = Product::whereIn('category_id', $cat_ids)->get();
            $productsAll = json_decode(json_encode($productsAll));
            //echo "<pre>"; print_r($productsAll); die;
        }else{
            //if url is sub category url
            //echo $categoryDetails->id; die;
            $productsAll = Product::where(['category_id' => $categoryDetails->id])->get();
        }
        
        //echo $productsAll; die;
        return view('products.listing')->with(compact('categories','categoryDetails','productsAll'));
    }

    public function product($id = null){
        
        //ke produk detail
        $productDetails = Product::with('attributes')->where(['id' => $id])->first();
        $productDetails = json_decode(json_encode($productDetails));
        //echo "<pre>"; print_r($productDetails); die;

        $relatedProducts = Product::where('id','!=',$id)->where(['category_id'=>$productDetails->category_id])->get();
        //$relatedProducts =  json_decode(json_encode($relatedProducts));
        //echo "<pre>"; print_r($relatedProducts); die;

        /*foreach ($relatedProducts->chunk(3) as $chunk) {
            # code...
            foreach ($chunk as $item) {
                # code...
                echo $item; echo"<br>";
            }
            echo "<br><br><br>";
        }
        die;*/

        $categories = Category::with('categories')->where(['parrent_id'=>0])->get();

        $productAltImages = ProductsImage::where('product_id',$id)->get();
        //$productAltImages = json_decode(json_encode($productAltImages));
        //echo "<pre>"; print_r($productAltImages); die;

        $total_stock = ProductsAttribute::where('product_id',$id)->sum('stock');

        return view('products.detail')->with(compact('productDetails','categories','productAltImages','total_stock','relatedProducts'));
    }

    public function getProductPrice(Request $request){
        $data = $request->all();
        //echo "<pre>"; print_r($data); die;
        $proArr = explode("-", $data['idSize']);
        //echo $proArr[0]; echo $proArr[1]; die;
        $proAttr = ProductsAttribute::where(['product_id' => $proArr[0], 'size' => $proArr[1]])->first();
        echo $proAttr->price;
        echo "#";
        echo $proAttr->stock;
    }
}
