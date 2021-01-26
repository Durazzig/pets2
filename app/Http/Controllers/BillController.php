<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Bill;
use App\Provider;
use App\User;
use Carbon\Carbon;
use SebastianBergmann\CodeUnitReverseLookup\Wizard;
use Image;
use Illuminate\Support\Facades\Response;

class BillController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $bills = Bill::whereDate('fecha', today())->paginate(5);
        $providers = Provider::all();
        //dd($bills);
        return view('bills.index', [
            'bills' => $bills,
            'providers' => $providers,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $empleados = User::where('work_area','almacen')->get();
        $providers = Provider::all();
        return view('bills.create',compact('providers'))->with(compact('empleados'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //dd($request);
        $request->validate([
            'provider_id'  => 'required',
            'folio' => 'required|numeric|min:3',
            'fecha' => 'required',
            'fecha_entrega' => 'required',
            'monto' => 'required|numeric',
            'empleado' => 'required',
        ]);


        $bill = new Bill();
        $bill->provider_id = $request->input('provider_id');
        $bill->folio = $request->input('folio');
        $bill->fecha = $request->input('fecha');
        $bill->fecha_entrega = $request->input('fecha_entrega');
        $bill->monto = $request->input('monto');
        $bill->empleado = $request->input('empleado');

        if ($request->hasFile('user_image')) {
            $file = $request->file('user_image');
            $extension = $file->getClientOriginalExtension();
            $filename = 'Factura' . time() . '.' . $extension;
            $file->move('uploads/facturas/',$filename);
            $bill->imagen = $filename;
        }else{
            $bill->imagen = '';
        }

        $bill->save();
        
        return redirect()->route('bills.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    public function filterDate(Request $request)
    {
        $fecha_inicial = $request -> input('desde');
        $fecha_final = $request -> input('hasta');
        $selectValue = $request -> input('provider_id');
        if($selectValue != "todos")
        {
            $provider = $request -> input('provider_id');
            $providers = Provider::all();
            $bills = Bill::where('provider_id',$provider)->whereBetween('fecha',[new Carbon($fecha_inicial), new Carbon($fecha_final)])->paginate(10);
            return view('bills.index', [
                'bills' => $bills,
                'providers' => $providers,
            ]);
        }
        else
        {
            $providers = Provider::all();
            $bills = Bill::whereBetween('fecha',[new Carbon($fecha_inicial), new Carbon($fecha_final)])->paginate(10);
            return redirect()->route('bills.index')->with(compact('providers'))->with('bills');
        }
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $bills = Bill::find($id);
        $providers = Provider::where('id','=',$bills->provider_id);
        return view('bills.edit', compact('bills', 'providers'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $bill = $request->except('_token');
        Bill::where('id','=',$id)->update($bill);
        $bills = Bill::paginate(10);
        $providers = Provider::all();
        return view('bills.index', compact('bills','providers'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $bill = Bill::find($id);
        $bill->delete();
        return redirect()->back()->with('msg','Factura eliminado correctamente');
    }

}
