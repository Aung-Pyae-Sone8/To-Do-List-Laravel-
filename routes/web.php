<?php

use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;



Route::redirect('/','customer/createPage')->name('post#home');
Route::get('customer/createPage',[PostController::class,'create'])->name('post#createPage');
Route::post('post/create',[PostController::class,'postCreate'])->name('post#create');

Route::get('post/delete/{id}',[PostController::class,'postDelete'])->name('post#delete');

Route::get('post/updatePage/{id}',[PostController::class,'updatePage'])->name('post#updatePage');

Route::get('post/editPage/{id}',[PostController::class,'editPage'])->name('post#editPage');
Route::post('post/update',[PostController::class,'update'])->name('post#update');

// database relation test
Route::get('product/list',function(){
    // select('categories.*')
    // select('product.*')
    // select('categories.*','product.*') == *
    // select('*','products.name as product_name','categories.name as category_name')
    $data = Product::select('products.*','categories.name as category_name','categories.desctiption as category_description')
            ->join('categories','products.category_id','categories.id')
            ->get();
    dd($data->toArray());
});

Route::get('order/list',function(){
    $data = Order::select('orders.customer_id','orders.product_id','customers.name as customer_name','customers.email','products.name as product_name')
            ->join('customers','orders.customer_id','customers.id')
            ->join('products','orders.product_id','products.id')
            ->get();
    dd($data->toArray());
});
