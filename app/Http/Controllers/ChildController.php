<?php

namespace App\Http\Controllers;

use App\Models\Child;
use Illuminate\Http\Request;

class ChildController extends Controller
{
    public function index(){
        $children = Child::all();
        return response()->json($children, 200);
    }

    public function store(Request $request){
        $request->validate([
            'name' => 'required|string',
            'age' => 'required|integer|min:0'
        ],
        [
            'required' => 'A(z) :attribute kitöltése kötelező',
            'string' => 'A(z) :attribute szöveg típusú',
            'integer' => 'A(z) :attribute egész szám',
            'min' => 'A(z) :attribute nem lehet kisebb, mint :min'
        ],
        [
            'name'=>'gyerek neve',
            'age'=>'gyerek kora'
        ]);

        $child = Child::create($request->all());
        return response()->json(['message'=>'Gyerek létrehozva', 'data'=> $child], 201, ['Access-Control-Allow-Origin'=>'*'],JSON_UNESCAPED_UNICODE);
    }

    public function update(Request $request, $id){
        $child = Child::find($id);
        if(!$child){
            return response()->json(['message'=>'Gyerek nem található'], 404);
        }

        $request->validate([
            'name' => 'sometimes|string',
            'age' => 'sometimes|integer|min:0'
        ],
        [
            'string' => 'A(z) :attribute szöveg típusú',
            'integer' => 'A(z) :attribute egész szám',
            'min' => 'A(z) :attribute nem lehet kisebb, mint :min'
        ],
        [
            'name'=>'gyerek neve',
            'age'=>'gyerek kora'
        ]);

        $child->update($request->all());
        return response()->json(['message'=>'Gyerek frissítve', 'data'=> $child], 200);
    }
    public function destroy($id){
        $child = Child::find($id);
        if(!$child){
            return response()->json(['message'=>'Gyerek nem található'], 404);
        }

        $child->delete();
        return response()->json(['message'=>'Gyerek törölve'], 200);
    }
}
