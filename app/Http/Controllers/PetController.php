<?php

namespace App\Http\Controllers;

use App\Pet;
use App\Owner;
use Illuminate\Http\Request;

class PetController extends Controller
{
    
    public function index()
    {
        $pets = Pet::orderBy('owner_id')->paginate(10);
        return view('pets.index', compact('pets'));
    }

    
    public function create()
    {
        $owners=Owner::all();
        return view('pets.create',compact('owners'));
    }

    
    public function store(Request $request)
    {
        //dd($request->input('owner'));
        //dd($request);
        $request->validate([
            'name'  => 'required',
            'species' => 'required',
            'raza' => 'required',
            'dob' => 'required',
            'status' => 'required',
        ]);
        $pet = new Pet();
        $pet->name = $request->input('name');
        $pet->species = $request->input('species');  
        $pet->raze = $request->input('raza'); 
        $pet->dob = $request->input('dob'); 
        $pet->status = $request->input('status'); 
        if ($request->input('owner') != "No tiene dueño") {
            $pet->owner_id = $request->input('owner');
        }
        $pet->save();

        $pets = Pet::orderBy('owner_id')->paginate(10);
        return view('pets.index', compact('pets'));
    }

    public function storeFromOwner(Request $request,$id)
    {
        //dd($request->input('dob'));
        $request->validate([
            'name'  => 'required',
            'species' => 'required',
            'raze' => 'required|alpha',
            'dob' => 'required',
            'status' => 'required',
        ]);

        $pet = new Pet();
        $pet->name = $request->input('name');
        $pet->owner_id = $id;
        $pet->species = $request->input('species');  
        $pet->raze = $request->input('raze'); 
        $pet->dob = $request->input('dob'); 
        $pet->status = $request->input('status'); 
        $pet->save();


        $pets = Pet::where('owner_id',$id)->paginate(10);
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

        $request->validate([
            'name'  => 'required',
            'species' => 'required',
            'raza' => 'required|regex:/^[\pL\s\-]+$/u',
            'dob' => 'required',
            'status' => 'required',
        ]);

        $pet = Pet::where('id',$id)->first();
        $pet->name = $request->input('name');
        $pet->species = $request->input('species');  
        $pet->raze = $request->input('raza'); 
        $pet->dob = $request->input('dob'); 
        $pet->status = $request->input('status'); 
        $pet->save();
        $owners = Owner::paginate(10);
        return view('owners.index',compact('owners'));
    }

    
    public function destroy($id)
    {
        $pet = Pet::find($id);
        $pet->delete();
        return redirect()->route('pets.index')->with('msg','Mascota eliminada correctamente');
    }
    public function destroyFromOwner($id)
    {
        $pet = Pet::find($id);
        $pet->delete();
        return redirect()->route('owners.index')->with('msg','Mascota eliminada correctamente');
    }
    public function filter(Request $request){
        
        $name = $request->input('name');
        $pets = Pet::where('name','LIKE','%'.$name.'%')->paginate(10);
        $pets->appends($request->all());
        return view('pets.index', compact('pets'));
    }
}
