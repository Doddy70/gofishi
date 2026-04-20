<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class WishlistController extends Controller
{
    public function add($id)
    {
        if (!Auth::guard('web')->check()) {
            Session::flash('error', 'Silakan login terlebih dahulu untuk menambahkan ke wishlist.');
            return redirect()->route('user.login');
        }

        $userId = Auth::guard('web')->user()->id;

        $exists = Wishlist::where('user_id', $userId)->where('perahu_id', $id)->exists();

        if (!$exists) {
            Wishlist::create([
                'user_id' => $userId,
                'perahu_id' => $id,
            ]);
            Session::flash('success', 'Perahu telah ditambahkan ke wishlist Anda.');
        } else {
            Session::flash('success', 'Perahu ini sudah ada di wishlist Anda.');
        }

        return redirect()->back();
    }

    public function remove($id)
    {
        if (!Auth::guard('web')->check()) {
            Session::flash('error', 'Silakan login terlebih dahulu.');
            return redirect()->route('user.login');
        }

        $userId = Auth::guard('web')->user()->id;

        Wishlist::where('user_id', $userId)->where('perahu_id', $id)->delete();

        Session::flash('success', 'Perahu telah dihapus dari wishlist Anda.');

        return redirect()->back();
    }
}
