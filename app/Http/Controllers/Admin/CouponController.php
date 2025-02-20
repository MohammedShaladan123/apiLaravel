<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CouponController extends Controller
{

    public function index()
    {
        return response()->json(Coupon::all());
    }


    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|unique:coupons,code',
            'discount_percentage' => 'required|numeric|min:1|max:100',
            'expires_at' => 'required|date|after:today',
        ]);

        $coupon = Coupon::create($request->all());

        return response()->json(['success' => true, 'data' => $coupon], 201);
    }

    public function show($id)
    {
        $coupon = Coupon::find($id);
        if (!$coupon) {
            return response()->json(['error' => 'Coupon not found'], 404);
        }
        return response()->json($coupon);
    }


    public function update(Request $request, $id)
    {
        $coupon = Coupon::find($id);
        if (!$coupon) {
            return response()->json(['error' => 'Coupon not found'], 404);
        }

        $request->validate([
            'code' => 'required|string|unique:coupons,code,' . $id,
            'discount_percentage' => 'required|numeric|min:1|max:100',
            'expires_at' => 'required|date|after:today',
        ]);

        $coupon->update($request->all());

        return response()->json(['success' => true, 'data' => $coupon]);
    }

    public function destroy($id)
    {
        $coupon = Coupon::find($id);
        if (!$coupon) {
            return response()->json(['error' => 'Coupon not found'], 404);
        }

        $coupon->delete();

        return response()->json(['success' => true, 'message' => 'Coupon deleted']);
    }
//  هون بنتاكد اذا الكبون صالح او لا\

    public function validateCoupon(Request $request)
    {
        $request->validate([
            'code' => 'required|string|exists:coupons,code',
        ]);

        $coupon = Coupon::where('code', $request->code)->first();

        if (!$coupon || !$coupon->isValid()) {
            return response()->json(['error' => 'Invalid or expired coupon'], 400);
        }

        return response()->json(['success' => true, 'discount_percentage' => $coupon->discount_percentage]);
    }
}
