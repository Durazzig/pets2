<?php

namespace App\Http\Controllers;

use App\Owner;
use App\Pet;
use Illuminate\Http\Request;

class OwnerController extends Controller
{
    
    public function index()
    {
        $owners = Owner::paginate(10);
        return view('owners.index', compact('owners'));
    }

    
    public function create()
    {
        return view('owners.create');
    }

    
    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required|regex:/^[\pL\s\-]+$/u',
            'address' => 'required',
            'phone' => 'required|numeric',
        ]);

        Owner::create([
            'name'  => $request->input('name'),
            'address'  => $request->input('address'),
            'phone' => $request->input('phone'),
        ]);

        return redirect()->route('owners.index');
    }
    public function filter(Request $request){
        
        $name = $request->input('name');
        $owners = Owner::where('name','LIKE','%'.$name.'%')->paginate(10);
        $owners->appends($request->all());
        return view('owners.index', compact('owners'));
    }

    
    public function show($id)
    {
        //
    }

    
    public function edit($id)
    {
        //
        $owners = Owner::find($id);
        return view('owners.edit', compact('owners'));
    }

    
    public function update(Request $request, $id)
    {
        //
        $request->validate([
            'name' => 'required|regex:/^[\pL\s\-]+$/u',
            'address' => 'required',
            'phone' => 'required|numeric',
        ]);

        $owner = $request->except('_token');
        Owner::where('id','=',$id)->update($owner);
        $owners = Owner::paginate(10);
        return view('owners.index', compact('owners'));
    }

    
    public function destroy($id)
    {
        $owner = Owner::find($id);
        if($owner->pets()->count())
        {
            return redirect()->route('owners.index')->with('msg','No puedes borrar este propietario, hay una mascota relacionada');
        }
        else{
            $owner->delete();
            return redirect()->route('owners.index')->with('msg','Propietario eliminado correctamente');
        }
    }

    public function pets($id){
        $pets = Pet::where('owner_id',$id)->paginate(10);
        $owner = owner::find($id);
        return view('owners.owner_pets', compact('pets'))->with(compact('owner'));
    }

    public function addOwnerPet($id){
        $owner = Owner::find($id);
        return view('owners.owner_addPet',compact('owner'));
    }
}
