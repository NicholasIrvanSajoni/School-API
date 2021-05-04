<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\API\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

use function Symfony\Component\VarDumper\Dumper\esc;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Role::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required',
                'description' => 'required'
            ]
        );

        if ($validator->fails()) {
            return
                [
                    'message' => 'Validation failed',
                    'error' => $validator->errors(),
                    'code' => 500
                ];
        }
        $generatedId = $this->generateIdRole();
        $response = DB::insert(
            'insert into role (id_role, name, description) values (?, ?, ?)',
            [$generatedId, $request->name, $request->description]
        );

        if ($response == 1) {
            return
                [
                    'messages' => 'Add Role Success',
                    'code' => 200
                ];
        } else {
            return
                [
                    'messages' => 'Add Role Failed',
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
        $roletarget = Role::where('id_role', $id)->first();
        if (is_object($roletarget)) {
            return $roletarget;
        } else {
            return
                [
                    'message' => 'Role Not Found',
                    'code' => 500
                ];
        }
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
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required',
                'description' => 'required'
            ]
        );

        if ($validator->fails()) {
            return
                [
                    'message' => 'Validation Failed',
                    'error' => $validator->errors(),
                    'code' => 500
                ];
        }
        $getroledata = $this->show($id);
        $responseUpdateRole = DB::update(
            'update role set name=?, description=? where id_role = ?',
            [$request->name, $request->description, $getroledata['id_role']]
        );
        if ($responseUpdateRole == 1) {
            return
                [
                    'message' => 'Update Role Success',
                    'code' => 200
                ];
        } else {
            return
                [
                    'message' => 'Update Role Failed',
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
        $getroledata = $this->show($id);
        $responseDeleteRole = Role::where('id_role', $id)->delete();
        if ($responseDeleteRole == 1) {
            return
                [
                    'message' => 'Delete Role Success',
                    'role' => 200
                ];
        } else {
            return
                [
                    'message' => 'Delete Role Failed',
                    'role' => 500
                ];
        }
    }

    private function generateIdRole()
    {
        // Get latest id
        $lastData = Role::orderBy('id_role', 'DESC')->first();
        $lastId = $lastData['id_role']; // "MP0020"
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
