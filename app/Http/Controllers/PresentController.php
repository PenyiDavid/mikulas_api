<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PresentType;
use App\Models\Present;

class PresentController extends Controller
{
    public function index(){
        $present_types = PresentType::all()->load('presents');
        return response()->json($present_types, 200);
    }

    public function index2(){
        $present_types = PresentType::all()->makeVisible('updated_at', 'created_at')->load('presents');
        return response()->json($present_types, 200);
    }
    public function index3(){
        $present_types = PresentType::all()->makeVisible('updated_at', 'created_at');
        $present_types->each(function($type){
            $type->presents->makeVisible('created_at');
        });
        return response()->json($present_types, 200);
    }

    public function store(Request $request){
        $request->validate([ //validation hibakód: 422
            'name' => 'required|string',
            'present_type_id' => 'required|exists:present_types,id'
        ],
        [
            'name.required' => 'A name kitöltése kötelező',
            'name.string' => 'A name egy szöveg',
            'present_type_id.exists' => 'Az ajándék típus nem létezik' 
        ],
        [
            'name'=>'Ajándék neve'
        ]);

        /*Present::create([
            'name' => $request['name'],
            'present_type_id' => $request['present_type_id']
        ]);*/

        $present = Present::create($request->all());
        return response()->json(['message'=>'Ajándék létrehozva', 'data'=> $present], 201);
    }
}
