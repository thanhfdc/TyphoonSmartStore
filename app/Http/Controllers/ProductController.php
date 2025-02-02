<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use App\Category;
use App\Producer;
use App\Brand;
use App\Cart;
use App\Order;
use App\ImageProduct;
use Stripe\Charge;
use Stripe\Stripe;
use Stripe\Customer;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Mail\ThankMail;
use Illuminate\Support\Facades\Mail;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = DB::select(
        'select p.*,c.title cate_title,b.name brand_name,r.name producer_name from products p,categories c,brands b,producers r
         where p.category_id = c.id and p.brand_id = b.id and p.producer_id = r.id'
        );
        return view('admin.products.listProduct',['products'=>$products]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::all();
        $producers = Producer::all();
        $brands = Brand::all();
        return view('admin.products.addProduct',['categories'=>$categories,'producers' => $producers,'brands' => $brands]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->hasFile('images')) {
            //  Let's do everything here
            if ($request->file('images')[0]->isValid()) {

                //
                $validated = $request->validate([
                    'title' => 'required',
                    'price' => 'required',
                    'content' => 'required',
                    'category_id' => 'required',
                    'producer_id' => 'required',
                    'brand_id' => 'required',
                    'images.*' => 'mimes:jpeg,png,webp|max:1014',
                    'quantity' => 'required',
                    'sku' => 'required'
                ],['images.*.mimes' => 'Chỉ được chọn file ảnh']);
                if (count($request->file('images')) > 5)
                {
                    return redirect()->back()->with("invalid","File không đượng lớn hơn 5");
                }
                $product = Product::create([
                   'title' => $validated['title'],
                   'price' => $validated['price'],
                   'category_id' => $validated['category_id'],
                   'image_path' => $request->file('images')[0]->getClientOriginalName(),
                   'producer_id' => $validated['producer_id'],
                   'description' => $validated['content'],
                   'brand_id' => $validated['brand_id'],
                   'quantity' => $validated['quantity'],
                   'sku' => $validated['sku']
                ]);
                $product->save();
                foreach($request->file('images') as $img)
                {
                    // $extension = $img->extension();
                    // dd($img->getClientOriginalName());
                    $img->storeAs('/public/images/products', $img->getClientOriginalName());
                    ImageProduct::create([
                        'path_image'=> $img->getClientOriginalName(),
                        'product_id' => $product->id
                    ]);
                }

                return redirect()->route('product.list')->with("success","Lưu thành công");
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::find($id);
        $comments = DB::table('comments')->join('customers', 'customers.id', '=', 'comments.customer_id')->where('comments.product_id','=',$id)->orderBy('comments.created_at','desc')->paginate(2,['comments.*','customers.username'],'comment');
        $replies = DB::select('select a.*,b.username customer_name from replies a,customers b where a.customer_id = b.id');
        $randomProduct = Product::inRandomOrder()->limit(3)->get();
        $imgDetail = ImageProduct::where('product_id' , $id)->get();
        return view('product-detail',['product'=>$product,'randomProduct'=>$randomProduct,'comments'=>$comments,'replies'=>$replies ,'imgDetail' => $imgDetail]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $categories = Category::all();
        $producers = Producer::all();
        $brands = Brand::all();
        $product = Product::find($id);
        $imagesByProduct = ImageProduct::where('product_id' , $id)->get();
        return view('admin.products.editProduct',['product' => $product,'categories'=>$categories,'producers' => $producers,'brands' => $brands ,'imagesByProduct' => $imagesByProduct]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $images = ImageProduct::where('product_id' , $id)->get();
        if($request->hasFile('images')){
            if (count($request->file('images')) > 5)
            {
                return redirect()->back()->with("invalid","File không đượng lớn hơn 5");
            }
            ImageProduct::where('product_id' , $id)->delete();
            foreach ($images as $imgDb)
            {
                if (file_exists('storage/images/products/'.$imgDb->path_image))
                {
                    unlink('storage/images/products/'.$imgDb->path_image);
                }
            }
            foreach ($request->file('images') as $img)
            {
                ImageProduct::create([
                    'path_image'=> $img->getClientOriginalName(),
                    'product_id' => $id
                ]);
                $img->storeAs('/public/images/products', $img->getClientOriginalName());
            }
        }



                $product = Product::find($id);
                $product->title = $request->input('title');
                $product->price = $request->input('price');
                $product->category_id = $request->input('category_id');
                $product->producer_id = $request->input('producer_id');
                $product->brand_id = $request->input('brand_id');
                $product->description = $request->input('content');
                $product->quantity = $request->input('quantity');
                $product->sku = $request->input('sku');
                $product->image_path = data_get($request->file('images') ,'0') ? data_get($request->file('images') ,'0')->getClientOriginalName() : $product->image_path;
                $product->save();




        return redirect()->route('product.list')->with("success","Sửa thành công");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
         $orders = Order::where('product_id' , $id)->get();
        foreach($orders as $order)
        {
             Order::where('id' , $order->id)->delete();
        }
        $product = Product::find($id);
        $isDelete =  $product->delete();
        if($isDelete)
        {
            return redirect()->route('product.list')->with("success","Xóa thành công");
        }

    }

    /**
     * Disable status product
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function disable($id)
    {
        $product = Product::find($id);
        $product->status = 0;
        $product->save();
        return redirect()->route('product.list')->with("success","Vô hiệu hóa thành công");
    }

    /**
     * Enable status product
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function enable($id)
    {
        $product = Product::find($id);
        $product->status = 1;
        $product->save();
        return redirect()->route('product.list')->with("success","Mở thành công");
    }

    /**
     * Add to cart
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function addToCart(Request $request, $id)
    {
        if(Auth::check()){
            $product = Product::find($id);
            $oldCart = Session::has('cart') ? Session::get('cart') : null;
            $cart = new Cart($oldCart);
            $cart->add($product,$product->id);
            $request->session()->put('cart',$cart);
            return redirect()->route('product.detail',['id' => $product->id])->with('success','Thêm giỏ hàng thành công');
        }else{
            return redirect()->route('login')->with("invalid","Vui lòng đăng nhập trước khi mua hàng");
        }
    }

    /**
     * Show item in cart
     *
     * @return \Illuminate\Http\Response
     */
    public function getCart(){
        if(!Session::has('cart')){
            return view('cart');
        }
        $oldCart = Session::get('cart');
        $cart = new Cart($oldCart);
        return view('cart', ['products' => $cart->items, 'totalPrice' => $cart->totalPrice]);
    }

    /**
     * Delete item in cart
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteItem($id){
        $oldCart = Session::has('cart') ? Session::get('cart') : null;
        $cart = new Cart($oldCart);
        $cart->deleteItem($id);
        if(count($cart->items) > 0){
            Session::put('cart',$cart);
        }else{
            Session::forget('cart');
        }
        return redirect()->route('cart');
    }

    /**
     * Checkout
     *
     * @return \Illuminate\Http\Response
     */
    public function checkout(){
        if(!Session::has('cart')){
            return view('cart');
        }
        if(Session::get('customer'))
        {
            $oldCart = Session::get('cart');
            $id = Session::get('customer')->id;
            $customer = DB::select(
                'select u.*,c.name c_name,d.name d_name,w.name w_name
                from customers u,cities c,districts d,wards w
                where u.city_id = c.matp and u.district_id = d.maqh and u.ward_id = w.xaid and id = ?',[$id]
            );
            $cart = new Cart($oldCart);
            return view('checkout', ['products' => $cart->items, 'totalPrice' => $cart->totalPrice, 'customer' => $customer[0]]);
        }else{
            return redirect()->route('login');
        }
    }

        /**
     * Pay
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function pay(Request $request){
        if(!Session::has('cart')){
            return view('cart');
        }
        $promotion = DB::select('select id from promotions where code = ?', [$request->input('promotion')]);
        $oldCart = Session::get('cart');
        $cart = new Cart($oldCart);
        Stripe::setApiKey('sk_test_51HgKtQA1q67YUalkoHBcyRRhWX9wzd92uQKM3ES7tpdifxzzRdF6LpeymEdsAXcVTtjRgEvHes0Wh4x8c0ftuYgO008aIpQ0IQ');
        $customer = new Customer();
        $customerDetailsAry = array(
            'email' => $request->input('email'),
            'source' => $request->input('stripeToken')
        );
        $customerDetails = $customer->create($customerDetailsAry);
        try {
            $charge = Charge::create(array(
                "customer" => $customerDetails->id,
                "amount" => $cart->totalPrice,
                "currency" => $request->input('currency_code'),
            ));
            foreach($cart->items as $row){
                $order = new Order();
                $order->customer_id = $request->input('customer_id');
                $order->qty = $row['qty'];
                $order->price = $row['price'];
                $order->product_id = $row['item']['id'];
                $order->order_code = $charge->id;
                $order->promotion_id = isset($promotion[0]) ? $promotion[0]->id:'';
                $order->save();
                $product = Product::find($row['item']['id']);
                Product::where('id',$row['item']['id'])->update(['quantity' => $product['quantity'] - $row['qty']]);
            }
        } catch (\Exception $e) {
            return redirect()->route('checkout')->with('error', $e->getMessage());
        }
        Mail::to($request->input('email'))->send(new ThankMail());
        Session::forget('cart');
        return view('thank');
    }

    /**
     * Decrease item in cart
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function decreaseItem($id){
        $oldCart = Session::has('cart') ? Session::get('cart') : null;
        $cart = new Cart($oldCart);
        $cart->decreaseItemByOne($id);
        if(count($cart->items) > 0){
            Session::put('cart',$cart);
        }else{
            Session::forget('cart');
        }
        return redirect()->route('cart');
    }

    /**
     * Increase item in cart
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function increaseItem($id){
        $oldCart = Session::has('cart') ? Session::get('cart') : null;
        $cart = new Cart($oldCart);
        $cart->increaseItemByOne($id);
        Session::put('cart',$cart);
        return redirect()->route('cart');
    }
}
