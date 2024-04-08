<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Carbon\Carbon;
use Image;


class ProductController extends Controller
{
    public function productadd() {

        return view('product.create',);

}
public function productstore(Request $request){

    $image = $request->file('product_thambnail');
    $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
    Image::make($image)->resize(917,1000)->save('upload/products/thambnail/'.$name_gen);
    $save_url = 'upload/products/thambnail/'.$name_gen;


    $product_id = Product::insertGetId([
      'name' => $request->name,
      'description' => $request->product_name_ar,
      'price' => $request->price,
      'quantity' => $request->quantity,
      'product_thambnail' => $save_url,
      'created_at' => Carbon::now(),
  ]);

  $notification = array(
    'message' => 'Product Inserted Successfully',
    'alert-type' => 'success'
);

return redirect()->route('manage-product')->with($notification);


}
public function ManageProduct(){

    $products = Product::latest()->get();

    return view('product.view',compact('products'));
}
public function EditProduct($id){
    $products = Product::findOrFail($id);
    return view('product.edit',compact('products'));

}
public function ProductDataUpdate(Request $request){

    $product_id = $request->id;
    Product::findOrFail($product_id)->update([
        'name' => $request->name,
        'description' => $request->product_name_ar,
        'price' => $request->price,
        'quantity' => $request->quantity,
        'created_at' => Carbon::now(),
    ]);

    $notification = array(
      'message' => 'Product Updated Successfully',
      'alert-type' => 'success'
  );

}
public function ThambnailImageUpdate(Request $request){
    $pro_id = $request->id;

     $oldImage = $request->old_img;
     @unlink($oldImage);

   $image = $request->file('product_thambnail');
       $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
       Image::make($image)->resize(917,1000)->save('upload/products/thambnail/'.$name_gen);
       $save_url = 'upload/products/thambnail/'.$name_gen;

       Product::findOrFail($pro_id)->update([
           'product_thambnail' => $save_url,
           'updated_at' => Carbon::now(),

       ]);


}
public function productdelete($id){

    Product::findOrFail($id)->delete();
    $notification = array(
        'message' => 'product Deleted Successfully',
        'alert-type' => 'danger'
      );
      return redirect()->back()->with($notification);


}


}









