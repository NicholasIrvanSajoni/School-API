<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\API\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Notification::all();
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
            'from_id_user' => 'required',
            'to_id_user' => 'required',
            'message' => 'required',
            'read' => 'required'
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

        // $generateId = $this->generateIdNotification();
        $from_id_user = $request->from_id_user;
        $to_id_user = $request->to_id_user;
        $message = $request->message;
        $read = $request->read;

        $responseCreateNotification = 
        DB::table('notification')->insert([
            'from_id_user' => $from_id_user,
            'to_id_user' => $to_id_user,
            'message' => $message,
            'read' => $read
        ]);
        if($responseCreateNotification == 1)
        {
            return 
            [
                'message' => 'Create Notification Success',
                'code' => 200
            ];
        }
        else
        {
            return 
            [
                'message' => 'Create Notification Failed',
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
        $responseShowNotification = Notification::where('id_notification', $id)->first();
        if(is_object($responseShowNotification))
        {
            return $responseShowNotification;
        }
        else
        {
            return
            [
                'message' => 'Data Not Found',
                'Code' => 500
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
            'from_id_user' => 'required',
            'to_id_user' => 'required',
            'message' => 'required',
            'read' => 'required'
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
        $from_id_user = $request->from_id_user;
        $to_id_user = $request->to_id_user;
        $message = $request->message;
        $read = $request->read;
        $getidnotification = $this->show($id);
        $responseUpdateNotification = DB::table('notification')
        ->where('id_notification', $getidnotification['id_notification'])
        ->update([
            'from_id_user' => $from_id_user,
            'to_id_user' => $to_id_user,
            'message' => $message,
            'read' => $read
            
        ]);
        if($responseUpdateNotification == 1)
        {
            return
            [
            'message' => 'Update Notification Success',
            'code' => 200
            ];
        }
        else
        {
            return
            [
                'message' => 'Update Notification Failed',
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
        $responseDeleteNotification = DB::table('notification')->where('id_notification', $id)->delete();
        if($responseDeleteNotification)
        {
            return
            [
                'message' => 'Delete Notification Success',
                'code' => 200
            ];
        }
        else
        {
            return
            [
                'message' => 'Delete Notification Failed',
                'code' => 500
            ];
        }
    }
}
