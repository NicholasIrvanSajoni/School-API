<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\API\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return User::all();
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
                'email' => ['required', 'email'],
                'password' => 'required',
                'id_role' => 'required',
                'birthdate' => ['required', 'date']
            ]
        );

        if ($validator->fails()) {
            return
                [
                    'message' => 'Validation Failed',
                    'errors' => $validator->errors(),
                    'code' => 500
                ];
        }
        $name = $request->name;
        $email = $request->email;
        $password = $request->password;
        $id_role = $request->id_role;
        $birthdate = $request->birthdate;

        //Create User baru
        $responseCreateUser = DB::insert('
        insert into user (email, password, name, birthdate, id_role) 
        values (?, ?, ?, ?, ?)
        ', [$email, $password, $name, $birthdate, $id_role]);
        if ($responseCreateUser == 1) {
            //Ambil id_user yang terakhir
            $currentUser = $this->getLastUserData();
            //Cek role
            if ($id_role == "R0002") {
                $responseCreateGuru = (new GuruController)->add($name, $currentUser['id_user']);
                if ($responseCreateGuru == true) {
                    return
                        [
                            'message' => 'Insert data guru success',
                            'code' => 200
                        ];
                } else {
                    return
                        [
                            'message' => 'Insert data guru failed',
                            'code' => 500
                        ];
                }
            }

            if ($id_role == "R0003") {
                $ResponseCreateMurid = (new MuridController)->add($name, $currentUser['id_user']);
                if ($ResponseCreateMurid == true) {
                    return
                        [
                            'message' => 'Insert data murid success',
                            'code' => 200
                        ];
                } else {
                    return
                        [
                            'message' => 'Insert data murid failed',
                            'code' => 500
                        ];
                }
            }
        } else {
            return
                [
                    'message' => 'Create User Failed',
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
        $target = User::where('id_user', $id)->first();
        
        if(!is_object($target))
        {
            return 
            [
            'message' => 'Data not found',
            'code' => 500
            ];
        }
        else
        {
        return $target;
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
                'email' => ['required', 'email'],
                'password' => 'required',
                'name' => 'required',
                'birthdate' => ['required', 'date'],
                'link_foto' => 'required',
                'izin_edit' => 'required'
            ]
        );

        if ($validator->fails()) {
            return
                [
                    'message' => 'Validation Failed',
                    'errors' => $validator->errors(),
                    'code' => 500
                ];
        }

        $email = $request->email;
        $password = $request->password;
        $name = $request->name;
        $birthdate = $request->birthdate;
        $link_foto = $request->link_foto;
        $izin_edit = $request->izin_edit;

        $getupdateid = $this->show($id);
        $responseUpdateUser = DB::update(
            'update user set email=?, password=?, name=?, birthdate=?, link_foto=?, izin_edit=?
         where id_user = ?',
            [$email, $password, $name, $birthdate, $link_foto, $izin_edit, $getupdateid['id_user']]
        );
        if ($responseUpdateUser == 1) {
            //Check Role
            //Guru
            if ($getupdateid['id_role'] == "R0002") {
                $responseUpdateGuru = (new GuruController)->update($name, $getupdateid['id_user']);
                if ($responseUpdateGuru == true) {
                    return
                        [
                            'message' => 'update data guru success',
                            'code' => 200
                        ];
                }
                if ($responseUpdateGuru == false) {
                    return
                        [
                            'message' => 'update data guru failed',
                            'code' => 500
                        ];
                }
            }
            //Murid
            if ($getupdateid['id_role'] == "R0003") {
                $responseUpdateMurid = (new MuridController)->update($name, $getupdateid['id_user']);
                if ($responseUpdateMurid == true) {
                    return
                        [
                            'message' => 'update data murid success',
                            'code' => 200
                        ];
                }

                if ($responseUpdateMurid == false) {
                    return
                        [
                            'message' => 'update data murid failed',
                            'code' => 500
                        ];
                }
            }
        } else {
            return
                [
                    'message' => 'Update user failed',
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
        $currentuser = $this->show($id);
        $responseDeleteUser = DB::table('user')->where('id_user', $id)->delete();
        if ($responseDeleteUser == 1) {
            //Check Role
            //Guru 
            if ($currentuser['id_role'] == 'R0002') {
                $responseDeleteGuru = (new GuruController)->destroy($currentuser['id_user']);
                if ($responseDeleteGuru == true) {
                    return
                        [
                            'message' => 'Delete Guru Success',
                            'code' => 200
                        ];
                } else {
                    return
                        [
                            'message' => 'Delete Guru Failed',
                            'kode' => 500
                        ];
                }
            }
            //Murid
            if ($currentuser['id_role'] == 'R0003') {
                $responseDeleteMurid = (new MuridController)->destroy($currentuser['id_user']);
                if ($responseDeleteMurid == true) {
                    return
                        [
                            'message' => 'Delete Murid Success',
                            'kode' => 200
                        ];
                } else {
                    return
                        [
                            'message' => 'Delete Murid Failed',
                            'kode' => 500
                        ];
                }
            }
        } else {
            return
                [
                    'message' => 'Delete Failed',
                    'code' => 500
                ];
        }
    }

    private function getLastUserData()
    {
        return User::orderby('id_user', 'desc')->first();
    }
}
