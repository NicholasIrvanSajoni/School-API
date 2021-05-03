<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\API\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class KelasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Kelas::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),
        [
            'name' => 'required'
        ]);

        if($validator->fails())
        {
            return
            [
                'message' => 'Validation Failed',
                'error' => $validator->errors(),
                'code' => 500
            ];
        }

        $generatedId = $this->getidkelas(); 
        $response = DB::insert('insert into kelas (id_kelas, name) values (?, ?)', [$generatedId, $request->name]);
        if($response == 1)
        {
            return
            [
                'message' => 'Insert Data Success',
                'code' => 200
            ];
        }
        else
        {
            return
            [
                'message' => 'Insert Data Failed',
                'code' => 500
            ];
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return $response = Kelas::where('id_kelas', $id)->first();
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
        $validator = Validator::make($request->all(),
        [
            'name' => 'required'
        ]);

        if($validator->fails())
        {
            return
            [
                'message' => 'Validation Failed',
                'error' => $validator->errors(),
                'code' => 500
            ];
        }

        $response = DB::update('update kelas set name = ? where id_kelas = ?', [$request->name, $id]);
        if($response == 1)
        {
            return
            [
                'message' => 'Update Data Success',
                'code' => 200
            ];
        }
        else
        {
            return
            [
                'message' => 'Update data Failed',
                'code' => 500
            ];
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $response = Kelas::where('id_kelas', $id)->delete();
        if($response == 1)
        {
            return
            [
                'message' => 'Data Deleted',
                'code' => 200
            ];
        }

        else
        {
            return
            [
                'message' => 'Delete Fail',
                'Code' => 500
            ];
        }
    }

    public function getidkelas()
    {
        // Get latest id
        // $lastData = MataPelajaran::orderBy('id_mata_pelajaran', 'DESC')->first();
        $lastData = DB::select('select * from kelas order by id_kelas desc limit 1');
        $lastData = $this->toArray($lastData);
        $lastId = $lastData[0]['id_kelas']; // "K0020"
        $symbolDigit = 1; // How Many Digit in Symbol
        $symbol = substr($lastId, 0, $symbolDigit); // "K"
        $numberIdStr = substr($lastId, $symbolDigit); // "0009"

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

        $numberIdAdded = $numberIdStr + 1; // 21
        if (strlen((string)(int)$numberIdAdded) > strlen((string)(int)$numberIdStr)) $zeroCount -= 1;
        $generatedZero = "";
        while ($zeroCount != 0) {
            $generatedZero = $generatedZero . "0";
            $zeroCount--;
        }
        $generatedId = $symbol . $generatedZero . (string)$numberIdAdded; // Add everything
        return $generatedId;
    }

    private function toArray($data){
        $data = array_map(function ($value) {
            return (array)$value;
        }, $data);
        return $data;
    }
}
