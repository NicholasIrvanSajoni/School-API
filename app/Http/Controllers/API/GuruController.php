<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\API\Guru;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GuruController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Guru::all();
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
        $response = Guru::where('id_guru', $id)->first();
        return $response;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($name, $id_user)
    {
        $responseUpdateGuru = DB::update('update guru set name=? where id_user = ?', [$name, $id_user]);
        if($responseUpdateGuru == 1)
        {
            return true;
        }
        if($responseUpdateGuru != 1)
        {
            return false;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id_user)
    {
        $responseDeleteGuru = DB::table('guru')->where('id_user', $id_user)->delete();
        if($responseDeleteGuru == 1)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function add($name, $id_user)
    {
        $generatedIdGuru = $this->generateIdGuru();
        $responseCreateGuru = DB::insert('insert into guru (id_guru, name, id_user) 
        values (?, ?, ?)', [$generatedIdGuru, $name, $id_user]);
        if ($responseCreateGuru == 1)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    private function generateIdGuru()
    {
        // Get latest id
        $lastData = Guru::orderBy('id_guru', 'DESC')->first();
        $lastId = $lastData['id_guru']; // "G0020"
        $symbolDigit = 1; // How Many Digit in Symbol
        $symbol = substr($lastId, 0, $symbolDigit); // "G"
        $numberIdStr = substr($lastId, $symbolDigit); // "0020"

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
}
