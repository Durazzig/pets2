<?php

namespace App\Http\Controllers;

use App\Cita;
use Illuminate\Http\Request;

class CitaController extends Controller
{
    
    public function index()
    {
        $citas = Cita::all();
        return view('citas.index',compact('citas'));
    }

    
    public function create()
    {
        return view('citas.create');
    }

    
    public function store(Request $request)
    {
        //
    }

    
    public function show($id)
    {
        //
    }

  
    public function edit($id)
    {
        //
    }

    
    public function update(Request $request, $id)
    {
        //
    }

    
    public function destroy($id)
    {
        //
    }
}
