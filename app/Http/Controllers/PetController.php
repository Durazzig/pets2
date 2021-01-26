<?php

namespace App\Http\Controllers;

use App\Pet;
use App\Owner;
use Illuminate\Http\Request;

class PetController extends Controller
{
    
    public function index()
    {
        $pets = Pet::all();
        return view('pets.index', compact('pets'));
    }

    
    public function create()
    {
        return view('pets.create');
    }

    
    public function store(Request $request)
    {
        //dd($request->input('owner'));
        $request->validate([
            'name'  => 'required',
            'species' => 'required',
            'raze' => 'required',
            'age' => 'required',
            'status' => 'required',
            'owner_id' => 'required',
        ]);

        Pet::create([
            'name'  => $request->input('name'),
            'species'  => $request->input('species'),
            'raze' => $request->input('raze'),
            'age' => $request->input('age'),
            'status' => $request->input('status'),
            'owner_id' => $request->input('owner_id'),
        ]);


        $pets = Pet::all();
        return view('pets.index',compact('pets'));
    }

    public function storeFromOwner(Request $request,$id)
    {
        //dd($id);
        $request->validate([
            'name'  => 'required',
            'species' => 'required',
            'raze' => 'required',
            'age' => 'required',
            'status' => 'required',
        ]);

        Pet::create([
            'name'  => $request->input('name'),
            'species'  => $request->input('species'),
            'raze' => $request->input('raze'),
            'age' => $request->input('age'),
            'status' => $request->input('status'),
            'owner_id' => $id,
        ]);


        $pets = Pet::where('owner_id',$id)->get();
        $owner = owner::find($id);
        //return redirect()->back();
        return view('owners.owner_pets', compact('pets'))->with(compact('owner'));
    }

    
    public function show($id)
    {
        //
    }

    
    public function editFromOwner($id)
    {
        $pet = Pet::find($id);
        return view('owners.owner_editPet',compact('pet'));
    }

    
    public function update(Request $request, $id)
    {
        //
    }

    public function updateFromOwner(Request $request, $id)
    {
        $pet = $request->except('_token');
        Pet::where('id','=',$id)->update($pet);
        $owners = Owner::all();
        return view('owners.index',compact('owners'));
    }

    
    public function destroy($id)
    {
        $pet = Pet::find($id);
        $pet->delete();
        return redirect()->back();
    }
}
