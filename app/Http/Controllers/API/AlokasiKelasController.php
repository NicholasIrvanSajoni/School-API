<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\API\AlokasiKelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AlokasiKelasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return AlokasiKelas::all();
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
            'id_kelas' => 'required',
            'id_guru' => 'required',
            'id_mata_pelajaran' => 'required',
            'nilai_tugas' => 'required',
            'nilai_uts' => 'required',
            'nilai_uas' => 'required',
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
        return $datanilai_akhir = $this->generatenilaiakhir($request->nilai_tugas, $request->nilai_uts, $request->nilai_uas);
        $nilai_akhir = (double)$datanilai_akhir;
        $responseAddAlokasiKelas = DB::table('alokasi_kelas')
        ->insert([
            'id_kelas' => $request->id_kelas,
            'id_murid' => $request->id_murid,
            'id_guru' => $request->id_guru,
            'id_mata_pelajaran' => $request->id_mata_pelajaran,
            'nilai_tugas' => $request->nilai_tugas,
            'nilai_uts' => $request->nilai_uts,
            'nilai_uas' => $request->nilai_uas,
            'nilai_akhir' => $nilai_akhir
        ]);
        return $responseAddAlokasiKelas;
    }

    private function generatenilaiakhir($nilai_tugas, $nilai_uts, $nilai_uas)
    {
        $nilaiakhir = ((0.3 * $nilai_tugas) + (0.3 * $nilai_uts) + (0.4 * $nilai_uas));
        return $nilaiakhir;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $response = AlokasiKelas::where('id_alokasi', $id)->first();
        if(is_object($response))
        {
            return $response;
        }
        else
        {
            return
            [
                'message' => 'Data Not Found',
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
        $validator = Validator::make($request->all(),
        [
            'id_kelas' => 'required',
            'id_guru' => 'required',
            'id_mata_pelajaran' => 'required',
            'nilai_tugas' => 'required',
            'nilai_uts' => 'required',
            'nilai_uas' => 'required',
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
        $getnilaiakhir = $this->generatenilaiakhir($request->nilai_tugas, $request->nilai_uts, $request->nilai_uas);
        $nilai_akhir = (double)$getnilaiakhir;
        $responseUpdateAlokasiKelas = DB::table('alokasi_kelas')->where('id_alokasi',$id)
        ->update([
            'id_kelas' => $request->id_kelas,
            'id_murid' => $request->id_murid,
            'id_guru' => $request->id_guru,
            'id_mata_pelajaran' => $request->id_mata_pelajaran,
            'nilai_tugas' => $request->nilai_tugas,
            'nilai_uts' => $request->nilai_uts,
            'nilai_uas' => $request->nilai_uas,
            'nilai_akhir' => $nilai_akhir
        ]);
        if($responseUpdateAlokasiKelas == 1)
        {
            return
            [
                'message' => 'Update Alokasi Kelas Success',
                'code' => 200
            ];
        }
        else
        {
            return
            [
                'message' => 'Update Alokasi Kelas Failed',
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
        $responseDeleteAlokasiKelas = DB::table('alokasi_kelas')->where('id_alokasi', $id)->delete();
        if($responseDeleteAlokasiKelas == 1)
        {
            return 
            [
                'message' => 'Delete Alokasi Kelas Success',
                'code' => 200
            ];
        }
        else
        {
            return
            [
                'message' => 'Delete Alokasi Kelas Failed',
                'code' => 500
            ];
        }
    }
}
