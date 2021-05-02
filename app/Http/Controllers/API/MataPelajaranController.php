<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\API\MataPelajaran;
use Illuminate\Http\Request;

class MataPelajaranController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return MataPelajaran::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    private function generateIdMataPelajaran()
    {
        // Get latest id
        $lastData = MataPelajaran::orderBy('id_mata_pelajaran', 'DESC')->first();
        $lastId = $lastData['id_mata_pelajaran'];
        $numberIdStr = substr($lastId, 2); // "0018"

        // Hitung numlah nol
        $zeroCount = 0;
        $tempNumberIdStr = $numberIdStr;
        while (true) {
            if (substr($tempNumberIdStr, 0, 1) != "0") {
                break;
            }
            $tempNumberIdStr = substr($tempNumberIdStr, 1);
            $zeroCount = $zeroCount + 1;
        }

        $numberIdAdded = $numberIdStr + 1;
        if (strlen((string)$numberIdAdded) > strlen((string)$numberIdStr)) $zeroCount -= 1;
        $generatedId = "";
        while ($zeroCount != 0) {
            $generatedId = $generatedId . "0";
            $zeroCount--;
        }
        $generatedId = "MP" . $generatedId . strval($numberIdAdded);
        return $generatedId;
    }
}
