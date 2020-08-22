<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Authors;

class AuthorsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Authors::all();

        return view('pages.author', ['data' => $data]);
    }

    /**
     * Display a detail of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Authors::find($id);

        return response()->json([
            'message' => "Data Berhasil Didapatkan.",
            'data' => $data,
        ], 200);
    }

    public function find()
    {
        $keyword = request()->input('keyword');
        $data = Authors::select(['id', DB::raw("CONCAT(first_name,' ', middle_name,' ',last_name) AS text")])->where('first_name', 'like', '%' . $keyword . '%')->orWhere('middle_name', 'like', '%' . $keyword . '%')->orWhere('last_name', 'like', '%' . $keyword . '%')->limit(5)->get();

        return response()->json([
            'message' => "Data Berhasil Ditemukan.",
            'data' => $data,
        ], 200);
    }


    protected function requestForm()
    {
        $form = request()->validate([
            'first_name' => 'string|required',
            'middle_name' => 'string|required',
            'last_name' => 'string|required',
        ]);
        return $form;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $this->requestForm();
        $action = Authors::create($data);

        if (!$action) return App::abort(400);

        return response()->json([
            'message' => "Data Berhasil Disimpan.",
            'data' => $data,
        ], 201);
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
        $data = $this->requestForm();
        $action = Authors::find($id);
        $action->update($data);
        if (!$action) return App::abort(400);

        return response()->json([
            'message' => "Data Berhasil Diubah.",
            'data' => $action,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $action = Authors::find($id);
        $action->delete();
        $action->books()->detach($id);
        if (!$action) return App::abort(400);

        return response()->json([
            'message' => "Data Berhasil Dihapus.",
            'data' => $action,
        ], 200);
    }
}
