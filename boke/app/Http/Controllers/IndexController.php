<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class IndexController extends Controller{

	public function index(){
		echo 123;
	}

	public function adddo(){
		echo 456;
	}

	public function goods($id=0){
		echo "ID is:==".$id;
	}
}