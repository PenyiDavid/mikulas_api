<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PresentType;
use App\Models\Present;

class PresentController extends Controller
{
    public function index(){
        //present_type-ok lekérdezése az ajándékokkal együtt
        //timestamp nem látszik alapértelmezetten, modelben hidden mezőben vannak (kivéve deleted_at a present-ben)

        $present_types = PresentType::all()->load('presents');
        //visszaadjuk a választ JSON formátumban, 200-as státusszal
        return response()->json($present_types, 200);
    }

    public function index2(){
        //present_type-ok lekérdezése az ajándékokkal együtt, created_at és updated_at mezők láthatóak a típusoknál
        $present_types = PresentType::all()->makeVisible(['created_at','updated_at'])->load('presents');
        return response()->json($present_types, 200);
    }
    public function index3(){
        //present_type-ok lekérdezése az ajándékokkal együtt, created_at és updated_at mezők láthatóak a típusoknál, valamint a created_at mező az ajándékoknál
        //először lekérdezzük az összes típust és láthatóvá tesszük a created_at és updated_at mezőket
        $present_types = PresentType::all()->makeVisible(['updated_at', 'created_at']);
        //típusokhoz tartozó ajándékok betöltése, majd az ajándékok created_at mezőjének láthatóvá tétele closure segítségével
        //closure: névtelen függvény, amit átadunk egy másik függvénynek paraméterként (pl. itt az each-nek), 
        //ami minden egyes elemre alkalmazza a closure-ben definiált műveletet
        //each: egy kollekció minden elemére alkalmaz egy adott műveletet
        $present_types->each(function($type){
            $type->presents->makeVisible('created_at');
        });
        return response()->json($present_types, 200);
    }

    public function store(Request $request){
        //új ajándék létrehozása és validálás
        //validation hibakód: 422
        //validációs szabályok, egyedi hibaüzenetek és mezőnevek megadása
        //:attribute helyére a mező neve kerül
        //exists: ellenőrzi, hogy a megadott érték létezik-e a megadott táblában és oszlopban
        
        $request->validate([ 
            'name' => 'required|string',
            'present_type_id' => 'required|exists:present_types,id'
        ],
        [
            //ezek az általános hibaüzenetek
            'required' => 'A(z) :attribute kitöltése kötelező',
            'string' => 'A(z) :attribute szöveg típusú',
            'exists' => 'A(z) :attribute típus nem létezik' 
        ],
        [
            //ezek kerülnek a hibaüzenetekbe az :attribute helyére
            'name'=>'ajándék neve',
            'present_type_id'=>'ajándék típus'
        ]);

        //hagyományos létrehozás
        //ha tömb elemeit egyesével akarjuk megadni
        /*Present::create([
            'name' => $request['name'],
            'present_type_id' => $request['present_type_id']
        ]);*/
        //egyszerűsített létrehozás
        //ajándék létrehozása a kérés összes adatával, 
        //reqest->all() csak akkor használható, ha a modellben a fillable mezők megfelelően vannak beállítva és a request-ben csak a megengedett mezők szerepelnek
        //pl.: checkbox esetén érdemes külön kezelni a mezőket, mert ha nincs bejelölve, akkor nem kerül elküldésre a request-ben
        
        $present = Present::create($request->all());
        return response()->json(['message'=>'Ajándék létrehozva', 'data'=> $present], 201);
    }
}
