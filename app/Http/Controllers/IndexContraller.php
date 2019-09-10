<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use App\Category;

class IndexContraller extends Controller
{
    public function index(){
    	//ini untuk Ascending order atau default
    	//$productsAll = Product::get();

    	//ini untuk Descending order
    	$productsAll = Product::orderBy('id','DESC')->get();

    	//ini untuk random order
    	//$productsAll = Product::inRandomOrder()->get();

    	//pergi ke semua kategori dan sub kategori
    	$categories = Category::with('categories')->where(['parrent_id'=>0])->get();
    	//$categories = json_decode(json_encode($categories));
    	//echo "<pre>"; print_r($categories); die;
    	$categories_menu = "";
    	/*foreach ($categories as $cat ) {
			$categories_menu .= "<div class='panel-heading'>
									<h4 class='panel-title'>
										<a data-toggle='collapse' data-parent='#accordian' href='#".$cat->id."'>
											<span class='badge pull-right'><i class='fa fa-plus'></i></span>
											".$cat->name."
										</a>
									</h4>
								</div>
								<div id='".$cat->id."' class='panel-collapse collapse'>
									<div class='panel-body'>
										<ul>";
										$sub_categories = Category::where(['parrent_id'=>$cat->id])->get();
							    		foreach ($sub_categories as $subcat) {
							    			$categories_menu .= "<li><a href='".$subcat->url."'>".$subcat->name." </a></li>";
							    		}
										$categories_menu .= "</ul>
									</div>
								</div>
								";
    	}*/

    	return view('index')->with(compact('productsAll','categories_menu','categories'));
    }
}
