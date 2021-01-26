<?php

namespace App\Http\Controllers;

use App\Provider;
use App\Bill;
use App\User;
use App\Role;
use Illuminate\Http\Request;

class ProviderController extends Controller
{

    public function index()
    {
        $providers = Provider::all();
        return view('providers.index', [
            'providers' => $providers,
        ]); 
    }


    public function create()
    {
        return view('providers.create');
    }


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'phone' => 'required',
        ]);
        
        Provider::create([
            'name'  => $request->input('name'),
            'phone' => $request->input('phone'),
        ]);

        return redirect()->route('providers.index');
    }


    public function show($id)
    {
        //
    }


    public function edit($id)
    {
        $providers = Provider::find($id);
        return view('providers.edit', compact('providers'));
    }


    public function update(Request $request, $id)
    {
        $provider = $request->except('_token');
        Provider::where('id','=',$id)->update($provider);
        $providers = Provider::all();
        return view('providers.index',compact('providers'));
    }


    public function destroy($id)
    {
        $provider = Provider::find($id);

        if($provider->bills()->count())
        {
            return redirect()->back()->with('msg','No puedes borrar este proveedor, hay una factura relacionada');
        }
        else{
            $provider->delete();
            return redirect()->back()->with('msg','Proveedor eliminado correctamente');
        }
    }
}
