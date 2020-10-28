<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Auth;

class Cart extends Model
{
    protected $fillable = [
        'stock_id',
        'user_id'
    ];

    public function showCart(){
        $user_id = Auth::id();
        $data['carts'] = $this->where('user_id', $user_id)->get();

        $data['count'] = 0;
        $data['sum'] = 0;

        foreach($data['carts'] as $cart){
            $data['count']++;
            $data['sum'] += $cart->stock->fee;
        }

        return $data;
    }

    public function stock(){
        return $this->belongsTo('\App\Models\Stock');
    }

    public function addCart($stock_id){
        $user_id = Auth::id();

        // 全く同じレコードが存在確認をして、存在する場合は取得し、存在しない場合はレコードを作成する。
        $cart_add_info = $this->firstOrCreate(['stock_id' => $stock_id, 'user_id' => $user_id]);

        // 今回のリクエストでレコードが作成された場合
        if($cart_add_info->wasRecentlyCreated){
            $message = 'カートに追加しました';
        }else{ // 今回のリクエストでレコードが作成されなかった場合
            $message = 'カートに登録済みです';
        }

        return $message;
    }

    public function deleteCart($stock_id){
        $user_id = Auth::id();

        // 全く同じレコードが存在確認をして、存在する場合は取得し、存在しない場合はレコードを作成する。
        $delete = $this->where('user_id', $user_id)->where('stock_id', $stock_id)->delete();

        // レコードが削除された場合
        if($delete > 0){
            $message = 'カートから1つの商品を削除しました';
        }else{ // レコードが削除できなかった場合
            $message = '削除に失敗しました';
        }

        return $message;
    }

    public function checkoutCart(){
        $user_id = Auth::id();
        $checkout_items = $this->where('user_id', $user_id)->get();
        $this->where('user_id', $user_id)->delete();

        return $checkout_items;
    }
}
