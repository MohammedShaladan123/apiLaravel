<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BrandController extends Controller
{

    public function index()
    {
        return response()->json(Brand::all(), Response::HTTP_OK);
    }


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:brands,name|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $brand = new Brand();
        $brand->name = $request->name;

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('logos', 'public');
            $brand->logo = $path;
        }

        $brand->save();

        return response()->json([
            'success' => true,
            'data' => $brand
        ], 201);
    }



    public function show(Brand $brand)
    {
        return response()->json($brand, Response::HTTP_OK);
    }


    public function update(Request $request, Brand $brand)
    {
        $request->validate([
            'name' => 'required|unique:brands,name,' . $brand->id . '|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $brand->name = $request->name;

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('logos', 'public');
            $brand->logo = $path;
        }

        $brand->save();

        return response()->json($brand, Response::HTTP_OK);
    }

    
    public function destroy(Brand $brand)
    {
        $brand->delete();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
