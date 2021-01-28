<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Bill;
use App\Provider;
use App\User;
use Carbon\Carbon;
use SebastianBergmann\CodeUnitReverseLookup\Wizard;
use Illuminate\Support\Facades\Response;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\BillsExport;

class BillController extends Controller
{

    public function index()
    {
        $bills = Bill::whereDate('fecha', today())->paginate(10);
        $providers = Provider::all();
        return view('bills.index', [
            'bills' => $bills,
            'providers' => $providers,
        ]);
    }

    public function create()
    {
        $empleados = User::where('work_area','almacen')->get();
        $providers = Provider::all();
        return view('bills.create',compact('providers'))->with(compact('empleados'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'provider_id'  => 'required|numeric',
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

    public function filterDate(Request $request)
    {
        $fecha_inicial = $request -> input('desde');
        $fecha_final = $request -> input('hasta');
        $selectValue = $request -> input('provider_id');
        if ($fecha_inicial == null) {
            $fecha_inicial = Carbon::now()->toDateString();
        }
        switch ($request->input('action')) {
            case 'filtrar':
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
                    return view('bills.index',compact('providers'))->with(compact('bills'));
                }
                break;
            case 'imprimir':
                if($selectValue != "todos")
                {
                    $provider = $request -> input('provider_id');
                    $providers = Provider::all();
                    $bills = Bill::where('provider_id',$provider)->whereBetween('fecha',[new Carbon($fecha_inicial), new Carbon($fecha_final)])->get();
                    //dd($bills);
                    return Excel::download(new BillsExport($bills),'facturas.xlsx');
                }
                else
                {
                    $providers = Provider::all();
                    $bills = Bill::whereBetween('fecha',[new Carbon($fecha_inicial), new Carbon($fecha_final)])->get();
                    //dd($bills);
                    return Excel::download(new BillsExport($bills),'facturas.xlsx');
                }
                break;
        }
        
    }

    public function edit($id)
    {
        $bills = Bill::find($id);
        $providers = Provider::where('id','=',$bills->provider_id);
        return view('bills.edit', compact('bills', 'providers'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'folio' => 'required|numeric|min:3',
            'fecha' => 'required',
            'fecha_entrega' => 'required',
            'monto' => 'required|numeric',
            'empleado' => 'required',
        ]);

        $bill = $request->except('_token');
        Bill::where('id','=',$id)->update($bill);
        $bills = Bill::paginate(10);
        $providers = Provider::all();
        return view('bills.index', compact('bills','providers'));
    }

    public function destroy($id)
    {
        $bill = Bill::find($id);
        $bill->delete();
        return redirect()->route('bills.index')->with('msg','Factura eliminada correctamente');
    }

}
