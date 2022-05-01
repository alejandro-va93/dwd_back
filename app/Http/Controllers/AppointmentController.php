<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            foreach (Appointment::all() as $apt) {
                $data[] = [
                    'id' => $apt->id,
                    'date' => $apt->date,
                    'start_time' => $apt->start_time,
                    'contact_info' => [
                        'first_name' => $apt->first_name,
                        'last_name' => $apt->last_name,
                        'email' => $apt->email,
                    ],
                ];
            }
            return response()->json([
                'status' => 'ok',
                'data' => $data,
            ], 200);
        } catch (\Throwable$th) {
            return response()->json(['status' => "nok"], 400);
        }
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $appointment = new Appointment;
            $appointment->date = $request->input('date');
            $appointment->start_time = $request->input('start_time');
            $appointment->first_name = $request->input('first_name');
            $appointment->last_name = $request->input('last_name');
            $appointment->phone_number = $request->input('phone_number');
            $appointment->email = $request->input('email');
            $appointment->save();
            return response()->json(
                ['status' => 'ok', 'id' => $appointment->id], 200
            );
        } catch (\Throwable$th) {
            return response()->json(
                ['status' => 'nok'], 400
            );
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Appointment  $appointment
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Appointment::findOrFail($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Appointment  $appointment
     * @return \Illuminate\Http\Response
     */
    public function edit(Appointment $appointment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Appointment  $appointment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Appointment $appointment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Appointment  $appointment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Appointment $appointment)
    {
        //
    }
}
