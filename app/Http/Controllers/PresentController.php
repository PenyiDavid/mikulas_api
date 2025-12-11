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
        
        //AI által javasolt megoldás (mindkettő eager loading, ugyanazt csinálják, csak más szintaxissal):
        //először jelezzük, hogy a present_type-okhoz tartozó presents-eket is le akarjuk kérdezni, és csak utána hajtjuk végre a lekérdezést
        $present_types = PresentType::with('presents')->get();
        //eddig így csináltuk, ez is teljesen jó:
        //lekérdezzük az összes present_type-ot, majd betöltjük hozzá az ajándékokat
        //$present_types = PresentType::all()->load('presents');
        
        //visszaadjuk a választ JSON formátumban, 200-as státusszal
        return response()->json($present_types, 200);
    }

    public function index2(){
        //present_type-ok lekérdezése az ajándékokkal együtt, created_at és updated_at mezők láthatóak a típusoknál
        //$present_types = PresentType::all()->makeVisible(['created_at','updated_at'])->load('presents');
        //vagy így is lehet:
        $present_types = PresentType::with('presents')->get()->makeVisible(['created_at','updated_at']);
        return response()->json($present_types, 200);
    }
    /*public function index3(){
        //ilyet nem fogunk használni, csak bemutató jelleggel

        //present_type-ok lekérdezése az ajándékokkal együtt, created_at és updated_at mezők láthatóak a típusoknál, valamint a created_at mező az ajándékoknál

        //először lekérdezzük az összes típust és láthatóvá tesszük a created_at és updated_at mezőket
        //típusokhoz tartozó ajándékok betöltése, majd az ajándékok created_at mezőjének láthatóvá tétele closure segítségével
        //closure: névtelen függvény, amit átadunk egy másik függvénynek paraméterként (pl. itt az each-nek), 
        //ami minden egyes elemre alkalmazza a closure-ben definiált műveletet
        //each: egy kollekció minden elemére alkalmaz egy adott műveletet
        //órai: ez már kevésbé hatékony, mert lazy loadingot használ, azaz minden egyes típushoz külön lekérdezést hajt végre az ajándékok betöltésére
        //$present_types = PresentType::all()->makeVisible(['updated_at', 'created_at'])
        //    ->each(function($type){
        //        $type->presents->makeVisible('created_at');
        //});
        //
        //eager loading használata closure-rel az ajándékok created_at mezőjének láthatóvá tételéhez
        $present_types = PresentType::with(['presents' => function($query){
            $query->get()->makeVisible('created_at');
        }])->get()->makeVisible(['created_at','updated_at']);
        return response()->json($present_types, 200);
    }*/

    

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

    public function update(Request $request, $id){
        $present = Present::find($id);
        if(!$present){
            return response()->json(['message'=>'Ajándék nem található'], 404);
        }

        $request->validate([ 
            'name' => 'sometimes|string',
            'present_type_id' => 'sometimes|exists:present_types,id'
        ],
        [
            'string' => 'A(z) :attribute szöveg típusú',
            'exists' => 'A(z) :attribute típus nem létezik' 
        ],
        [
            'name'=>'ajándék neve',
            'present_type_id'=>'ajándék típus'
        ]);

        $present->update($request->all());
        return response()->json(['message'=>'Ajándék frissítve', 'data'=> $present], 200);
    }

    public function destroy($id){
        $present = Present::find($id);
        if(!$present){
            return response()->json(['message'=>'Ajándék nem található'], 404);
        }

        $present->delete();
        return response()->json(['message'=>'Ajándék törölve'], 200);
    }
}
