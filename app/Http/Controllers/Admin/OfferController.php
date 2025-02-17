<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Offer;
use Illuminate\Http\Request;

class OfferController extends Controller
{
    
    public function index()
    {
        return response()->json(Offer::with('product')->get());
    }


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'discount_percentage' => 'required|numeric|min:0|max:100',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'product_id' => 'required|exists:products,id',
        ]);

        $offer = Offer::create($request->all());

        return response()->json(['success' => true, 'data' => $offer], 201);
    }


    public function show($id)
    {
        $offer = Offer::with('product')->find($id);
        if (!$offer) {
            return response()->json(['error' => 'Offer not found'], 404);
        }
        return response()->json($offer);
    }


    public function update(Request $request, $id)
    {
        $offer = Offer::find($id);
        if (!$offer) {
            return response()->json(['error' => 'Offer not found'], 404);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'discount_percentage' => 'required|numeric|min:0|max:100',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'product_id' => 'required|exists:products,id',
        ]);

        $offer->update($request->all());

        return response()->json(['success' => true, 'data' => $offer]);
    }


    public function destroy($id)
    {
        $offer = Offer::find($id);
        if (!$offer) {
            return response()->json(['error' => 'Offer not found'], 404);
        }

        $offer->delete();

        return response()->json(['success' => true, 'message' => 'Offer deleted']);
    }
}
