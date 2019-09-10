<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use App\Category;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public static function mainCategories(){
    	$mainCategoires = Category::where(['parrent_id' => 0])->get();
    	//$mainCategoires = json_decode(json_encode($mainCategoires));
    	//echo "<pre>"; print_r($mainCategoires);
    	return $mainCategoires;
    }
}
