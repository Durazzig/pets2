<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Bill;
use App\Provider;
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
        $bills = Bill::whereDate('fecha', today())->paginate(10);
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
        $providers = Provider::all();
        return view('bills.create',compact('providers'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $image_file = $request->user_image;
        //dd($image_file);
        $image = Image::make($image_file);
        Response::make($image->encode('jpeg'));
        //dd($image);
        $request->validate([
            'provider_id'  => 'required',
            'folio' => 'required',
            'fecha' => 'required',
            'fecha_entrega' => 'required',
            'monto' => 'required',
            'empleado' => 'required',
        ]);

        Bill::create([
            'provider_id' => $request->input('provider_id'),
            'folio' => $request->input('folio'),
            'fecha' => $request->input('fecha'),
            'fecha_entrega' => $request->input('fecha_entrega'),
            'monto' => $request->input('monto'),
            'empleado' => $request->input('empleado'),
            'imagen' => $image,
        ]);

        
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
            return view('bills.index', [
                'bills' => $bills,
                'providers' => $providers,
            ]);
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

    public function fetch_image($id){
        $image = User::findOrFail($id);
        $image_file = Image::make($image->imagen);
        $response = Response::make($image_file->encode('jpeg'));
        $response->header('Content-Type','image/jpeg');
        return $response;
    }
}
