<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use DB;
use Illuminate\Http\Request;

class MainController extends Controller
{
    // this page will show index blade
    public function index()
    {
        $allProducts = Product::all();
        $newArrivals = Product::where("type", "new-arrivals")->get();
        $hotSale = Product::where("type", "hot-sale")->get();
        return view("frontend.index", compact('allProducts', 'newArrivals', 'hotSale'));
    }

    // this page will show about blade
    public function about()
    {
        return view("frontend.about");
    }

    // this page will show shop blade
    public function shop()
    {
        return view("frontend.shop");
    }

    // this page will show shop details blade
    public function singleView($id)
    {
        $product = Product::find($id);
        return view('frontend.single_view', compact('product'));
    }
    

    // this page will show cart blade
    public function cart()
    {
        $cartItems = DB::table("products")->join("carts", "carts.productId", "products.id")->select("products.title", "products.quantity as pQuantity", "products.price", "products.picture", "carts.*")->where("carts.customerId", session()->get("id"))->get();
        return view("frontend.shopping-cart", compact("cartItems"));
    }

    public function checkout(Request $data)
    {
        if (session()->has("id")) {
            $order = new Order();
            $order->status = "Pending";
            $order->customerId = session()->get("id");
            $order->bill = $data->bill;
            $order->fullname = $data->fullname;
            $order->address = $data->address;
            $order->phone = $data->phone;
            if ($order->save()) {
                $carts = Cart::where('customerId', session()->get('id'))->get();

                foreach ($carts as $item) {
                    $product = Product::find($item->productId);
                    $orderItem = new OrderItem();
                    $orderItem->productId = $item->productId;
                    $orderItem->quantity = $item->quantity;
                    $orderItem->price = $product->price;
                    $orderItem->orderId = $order->id;
                    $orderItem->save();
                    $item->delete();
                }
            }
            return redirect()->back()->with("success", "Your order has been placed successfully! 🎉");
        } else {
            return redirect()->route("login")->with("error", "Please login first 🙋‍♂️");
        }
    }

    // this page will show register blade
    public function register()
    {
        return view("frontend.register");
    }

    // this page will show login blade
    public function login()
    {
        return view("frontend.login");
    }

    // this page will show loginUser
    public function loginUser(Request $data)
    {
        $user = User::where("email", $data->email)->where("password", $data->password)->first();

        // condition
        if ($user) {
            session()->put("id", $user->id);
            session()->put("role", $user->role);

            // customer
            if ($user->role == "Customer") {
                return redirect()->route("index");
            }
        } else {
            return redirect()->route("login")->with("error", "Email / Password is incorrect 😐");
        }
    }

    // this page will show registerUser
    public function registerUser(Request $data)
    {
        $newUser = new User();
        $newUser->fullname = $data->fullname;
        $newUser->email = $data->email;
        $newUser->password = $data->password;

        // image
        $images = $data->picture;
        $imgExt = $images->getClientOriginalExtension();
        $imagName = 'ecom' . '_' . time() . '.' . $imgExt;
        // upload image on folder
        $images->move(public_path('uploads/profiles'), $imagName);

        $newUser->picture = $imagName;
        $newUser->role = "Customer";

        // condition
        if ($newUser->save()) {
            return redirect()->route("login")->with("success", "Congratulation! Your account has been created successfully.😍");
        }
    }

    // this page will show logout
    public function logout()
    {
        session()->forget("id");
        session()->forget("role");
        return redirect()->route("login");
    }

    // add to cart
    public function addToCart(Request $data)
    {
        if (session()->has("id")) {
            $item = new Cart();
            $item->productId = $data->id;
            $item->quantity = $data->quantity;
            $item->customerId = session()->get("id");
            $item->save();
            return redirect()->route("cart")->with("success", "Item added successfully");
        } else {
            return redirect()->route("login")->with("error", "Please login first 🙋‍♂️");
        }
    }

    // updateCart
    public function updateCart(Request $data)
    {
        if (session()->has("id")) {
            $item = Cart::find($data->input("id"));
            $item->quantity = $data->quantity;
            $item->save();
            return redirect()->back()->with("success", "Item updated successfully");
        } else {
            return redirect()->route("login")->with("error", "Please login first 🙋‍♂️");
        }
    }

    // this page will show deleteCartItem
    public function deleteCartItem($id)
    {
        $item = Cart::find($id);
        $item->delete();
        return redirect()->back()->with("success", "1 item has been deleted from cart");
    }
}
