<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishListController extends Controller
{
    public function AddToWishlist(Request $request, $course_id) {
        if (Auth::check()) {
            $exists = Wishlist::where([
                ['user_id', '=', Auth::id()],
                ['course_id', '=', $course_id]
            ])->exists();
            
            if (!$exists) {
                Wishlist::create([
                    'user_id' => Auth::id(),
                    'course_id' => $course_id,
                ]);

                return response()->json(['success' => 'Successfully Added on your Wishlist']);
            }else {
                return response()->json(['error' => 'This course already on your Wishlist']);
            }
        }else {
            return response()->json(['error' => 'Login first to your account']);
        }
    }//end method
}
