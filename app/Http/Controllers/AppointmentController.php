<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Carbon\Carbon;
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
            $apts = Appointment::all();
            if ($apts->isEmpty()) {
                return response()->json(['status' => 'ok', 'data' => []], 200);
            }
            foreach ($apts as $apt) {
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
                //! falta poner esto en el post y put, y que se valide que la fecha del req body sea lun-vie 9am-6pm
                'dia_borrar' => Carbon::now()->dayOfWeek,
                //!
                'status' => 'ok',
                'data' => $data,
            ], 200);
        } catch (\Throwable$th) {
            return response()->json(['status' => 'nok'], 400);
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
            foreach (Appointment::all() as $apt) {
                if ($apt->date == $appointment->date) {
                    return response()->json(['status' => 'nok', 'message' => 'appointment already exists'], 400);
                }
            }
            $appointment->start_time = $request->input('start_time');
            $appointment->first_name = $request->input('first_name');
            $appointment->last_name = $request->input('last_name');
            $appointment->phone_number = $request->input('phone_number');
            $appointment->email = $request->input('email');
            $appointment->save();
            return response()->json(
                ['status' => 'ok', 'message' => 'appointment created successfully', 'id' => $appointment->id], 200
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
        try {
            $appointment = Appointment::findOrFail($id);
            $data = [
                'id' => $appointment->id,
                'date' => $appointment->date,
                'start_time' => $appointment->start_time,
                'contact_info' => [
                    'first_name' => $appointment->first_name,
                    'last_name' => $appointment->last_name,
                    'email' => $appointment->email,
                ],
            ];
            return response()->json([
                'status' => 'ok',
                'data' => $data,
            ], 200);
        } catch (\Throwable$th) {
            return response()->json(['status' => 'nok'], 400);
        }
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
    public function update(Request $request, $id)
    {
        try {
            $appointment = Appointment::findOrFail($id);
            $appointment->date = $request->input('date');
            foreach (Appointment::all() as $apt) {
                if ($apt->date == $appointment->date) {
                    return response()->json(['status' => 'nok', 'message' => 'appointment already exists'], 400);
                }
            }
            $appointment->start_time = $request->input('start_time');
            $appointment->first_name = $request->input('first_name');
            $appointment->last_name = $request->input('last_name');
            $appointment->phone_number = $request->input('phone_number');
            $appointment->email = $request->input('email');
            $appointment->save();
            return response()->json(['status' => 'ok', 'message' => 'appointment updated successfully', 'id' => $appointment->id], 200);

        } catch (\Throwable$th) {
            return response()->json(['status' => 'nok'], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Appointment  $appointment
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $appointment = Appointment::findOrFail($id);
            $appointment->delete();
            return response()->json(['status' => 'ok', 'message' => 'appointmnet deleted successfully'], 200);
        } catch (\Throwable$th) {
            return response()->json(['status' => 'nok'], 400);
        }
    }
}
