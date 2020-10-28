<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use App\Models\Stock;
use App\Models\Cart;
use Illuminate\Support\Facades\Mail;
use App\Mail\Thanks;

class ShopController extends Controller
{
    /* 商品一覧を表示する */
    public function index(){
        $stocks = Stock::simplePaginate(6);
        return view('shop', ['stocks' => $stocks]);
    }

    /* カート情報を表示する */
    public function myCart(Cart $cart){
        $data = $cart->showCart();
        return view('mycart', $data);
    }

    /* カートに商品追加し表示する */
    public function addMycart(Request $request, Cart $cart){
        // カートに追加の処理
        $stock_id = $request->stock_id; // 送信されてきた商品のidを取得する。
        $message = $cart->addCart($stock_id);
        
        // 追加後のカート情報を取得
        $data = $cart->showCart();
        
        return view('mycart', $data)->with('message', $message);
    }

    /* カートの商品を削除する */
    public function deleteCart(Request $request, Cart $cart){
        // カートから削除の処理
        $stock_id = $request->stock_id;
        $message = $cart->deleteCart($stock_id);

        // 削除後のカート情報を取得
        $data = $cart->showCart();
        
        return view('mycart', $data)->with('message', $message);
    }

    /* カートの商品を購入する */
    public function checkout(Cart $cart){
        $user = Auth::user();
        $mail_data['user'] = $user->name;
        $mail_data['checkout_items'] = $cart->checkoutCart();
        Mail::to($user->email)->send(new Thanks($mail_data));
        return view('checkout');
    }
}
