<?php

namespace App\Http\Controllers;

use App\Models\PresentType;
use Illuminate\Http\Request;

class PresentTypeController extends Controller
{
    public function index(){
        $present_types = PresentType::all();
        return response()->json($present_types, 200);
    }

    public function store(Request $request){
        $request->validate([
            'name' => 'required|string|unique:present_types,name'
        ],
        [
            'required' => 'A(z) :attribute kitöltése kötelező',
            'string' => 'A(z) :attribute szöveg típusú',
            'unique' => 'A(z) :attribute már létezik'
        ],
        [
            'name'=>'ajándék típus'
        ]);

        $present_type = PresentType::create($request->all());
        return response()->json(['message'=>'Ajándék típus létrehozva', 'data'=> $present_type], 201);
    }
}
