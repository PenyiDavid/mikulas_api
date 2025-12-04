<?php

namespace App\Http\Controllers;

use App\Models\Child;
use Illuminate\Http\Request;

class ChildPresentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //lekérdezi az összes gyereket az ajándékaikkal és az ajándék típusokkal együtt
        $wishes = Child::with(['presents', 'presents.present_type'])->get();
        return response()->json($wishes, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //új kívánság hozzáadása (gyerek-ajándék kapcsolat létrehozása a pivot táblában)
        $request->validate([
            'child_id' => 'required|exists:children,id',
            'present_id' => 'required|exists:presents,id',
            'quantity' => 'required|integer|min:1'
        ],
        [
            'required' => 'A(z) :attribute kitöltése kötelező',
            'exists' => 'A(z) :attribute nem létezik',
            'integer' => 'A(z) :attribute egész szám',
            'min' => 'A(z) :attribute nem lehet kisebb, mint :min'
        ],
        [   
            'child_id'=>'gyerek azonosító',
            'present_id'=>'ajándék azonosító',
            'quantity'=>'mennyiség'
        ]);

        //megkeressük a gyereket és hozzáadjuk az ajándékot a pivot táblához a megadott mennyiséggel
        $child = Child::find($request->child_id);
        $child->presents()->attach($request->present_id, ['quantity'=>$request->quantity]);
        return response()->json(['message'=>'Kívánság hozzáadva'], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
