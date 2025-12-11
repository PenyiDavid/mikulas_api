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

    public function update(Request $request, $id){
        $present_type = PresentType::find($id);
        if(!$present_type){
            return response()->json(['message'=>'Ajándék típus nem található'], 404);
        }

        $request->validate([
            'name' => 'required|string|unique:present_types,name,'.$id
        ],
        [
            'required' => 'A(z) :attribute kitöltése kötelező',
            'string' => 'A(z) :attribute szöveg típusú',
            'unique' => 'A(z) :attribute már létezik'
        ],
        [
            'name'=>'ajándék típus'
        ]);

        $present_type->update($request->all());
        return response()->json(['message'=>'Ajándék típus frissítve', 'data'=> $present_type], 200);
    }

    public function destroy($id){
        $present_type = PresentType::find($id);
        if(!$present_type){
            return response()->json(['message'=>'Ajándék típus nem található'], 404);
        }

        $present_type->delete();
        return response()->json(['message'=>'Ajándék típus törölve'], 200);
    }
}
