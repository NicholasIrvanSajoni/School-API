<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\API\Murid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MuridController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Murid::all();
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
        $target = Murid::where('id_murid', $id)->first();
        return $target;
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
        $responseUpdateMurid = DB::update('update murid set name=? where id_user = ?', [$name, $id_user]);
        if($responseUpdateMurid == 1)
        {
            return true;
        }
        if($responseUpdateMurid != 1)
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
        $responseDeleteMurid = DB::table('murid')->where("id_user", $id_user)->delete();
        if($responseDeleteMurid == 1)
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
        $generatedIdMurid = $this->generateIdMurid();
        $response = DB::insert('insert into murid (id_murid, name, id_user) 
        values (?, ?, ?)', [$generatedIdMurid, $name, $id_user]);
        if($response == 1)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    private function generateIdMurid()
    {
        // Get latest id
        $lastData = Murid::orderBy('id_murid', 'DESC')->first();
        $lastId = $lastData['id_murid']; // "MP0020"
        $symbolDigit = 1; // How Many Digit in Symbol
        $symbol = substr($lastId, 0, $symbolDigit); // "MP"
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
