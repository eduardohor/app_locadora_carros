<?php

namespace App\Http\Controllers;

use App\Models\Carro;
use App\Repositories\CarroRepository;
use Illuminate\Http\Request;

class CarroController extends Controller
{

    private $carro;

    public function __construct(Carro $carro)
    {
        $this->carro = $carro;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $carroRepository = new CarroRepository($this->carro);

        if ($request->has('atributos_modelo')) {
            $atributos_modelos = 'modelos:id,' . $request->atributos_modelos;
            $carroRepository->selectAtributosRegistrosRelacionados($atributos_modelos);
        } else {
            $carroRepository->selectAtributosRegistrosRelacionados('modelo');
        }


        if ($request->has('filtro')) {
            $carroRepository->filtro($request->filtro);
        }

        if ($request->has('atributos')) {
            $atributos = $request->atributos;
            $carroRepository->selectAtributos($atributos);
        }

        $carros = $carroRepository->getResultado();


        return response()->json($carros, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreCarroRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate($this->carro->rules());
        $carro = $this->carro->create($request->all());

        return response()->json($carro, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $carro = $this->carro->with('modelo')->find($id);
        if ($carro === null) {
            return response()->json(['erro' => 'Recurso pesquisado não existe'], 404);
        }
        return response()->json($carro, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Carro  $carro
     * @return \Illuminate\Http\Response
     */
    public function edit(Carro $carro)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCarroRequest  $request
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $carro = $this->carro->find($id);

        if ($carro === null) {
            return response()->json(['erro' => 'Impossível realizar a atualização, o recurso solicitado não existe.'], 404);
        }

        $regrasDinamicas = [];

        if ($request->method() === 'PATCH') {

            foreach ($carro->rules() as $input => $regra) {

                if (array_key_exists($input, $request->all())) {
                    $regrasDinamicas[$input] = $regra;
                }
            }

            $request->validate($regrasDinamicas);
        } else {

            $request->validate($carro->rules());
        }

        $carro->fill($request->all());

        $carro->save();

        return response()->json($carro, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $carro = $this->carro->find($id);
        if ($carro === null) {
            return response()->json(['erro' => 'Impossível realizar a exclusão, o recurso solicitado não existe.'], 404);
        }

        
        $carro->delete();

        return response()->json(['msg' => 'O carro foi removido com sucesso!'], 204);
    }
}
