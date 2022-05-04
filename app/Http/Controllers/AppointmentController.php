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
                return response()->json([
                    'status' => 'ok',
                    'data' => [],
                ], 200);
            }
            foreach ($apts as $apt) {
                $data[] = [
                    'id' => $apt->id,
                    'date' => $apt->date,
                    'start_time' => $apt->start_time,
                    'contact_info' => [
                        'first_name' => $apt->first_name,
                        'last_name' => $apt->last_name,
                        'phone_number' => $apt->phone_number,
                        'email' => $apt->email,
                    ],
                ];
            }
            return response()->json([
                'status' => 'ok',
                'data' => $data,
            ], 200);
        } catch (\Throwable$th) {
            return response()->json(['status' => 400], 400);
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
            $appointment->first_name = trim($request->input('first_name'));
            $appointment->last_name = trim($request->input('last_name'));
            $appointment->phone_number = $request->input('phone_number');
            $appointment->email = trim($request->input('email'));
            if (!str_contains($appointment->email, '@')) {
                return response()->json([
                    'status' => 'nok',
                    'message' => 'Enter a valid email address',
                ], 200);
            }
            $num_length = strlen((string) $appointment->phone_number);
            if ($num_length != 9) {
                return response()->json([
                    'status' => 'nok',
                    'message' => 'Enter a 9 digit phone number',
                ], 200);
            };
            foreach (Appointment::all() as $apt) {
                if ($apt->date == $appointment->date) {
                    if ($apt->first_name == $appointment->first_name && $apt->last_name == $appointment->last_name) {
                        return response()->json([
                            'status' => 'nok',
                            'message' => 'User can have only one appointment per day',
                        ], 200);
                    }
                    if ($apt->start_time == $appointment->start_time) {
                        return response()->json([
                            'status' => 'nok',
                            'message' => 'Appointment already exists',
                        ], 200);
                    }
                }
            }
            $dayNum = Carbon::parse($appointment->date)->dayOfWeek;
            if ($dayNum == 0 || $dayNum == 6) {
                return response()->json([
                    'status' => 'nok',
                    'message' => 'Appointment day must be between Monday and Friday',
                ], 200);
            }
            $allowedHrs = [
                '09:00',
                '10:00',
                '11:00',
                '12:00',
                '13:00',
                '14:00',
                '15:00',
                '16:00',
                '17:00',
            ];
            $bool = false;
            foreach ($allowedHrs as $hr) {
                if ($hr == $appointment->start_time) {
                    $bool = true;
                }
            }
            if ($bool != true) {
                return response()->json([
                    'status' => 'nok',
                    'message' => 'Appointment must be between 09:00 and 18:00 hrs',
                ], 200);
            }
            $appointment->save();
            return response()->json([
                'status' => 'ok',
                'message' => 'Appointment created successfully',
                'id' => $appointment->id,
            ], 200);
        } catch (\Throwable$th) {
            return response()->json(['status' => 400], 400);
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
        if (str_contains($id, '-')) {
            try {
                $data = Appointment::where('date', $id)->get();
                if ($data->isEmpty()) {
                    return throw 'error';
                }
                return response()->json([
                    "status" => 'ok',
                    "data" => $data,
                ], 200);
            } catch (\Throwable$th) {
                return response()->json(['status' => 400], 400);
            }
        } else {
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
                return response()->json(['status' => 400], 400);
            }
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
            $appointment->start_time = $request->input('start_time');
            $appointment->first_name = trim($request->input('first_name'));
            $appointment->last_name = trim($request->input('last_name'));
            $appointment->phone_number = $request->input('phone_number');
            $appointment->email = trim($request->input('email'));
            if (!str_contains($appointment->email, '@')) {
                return response()->json([
                    'status' => 'nok',
                    'message' => 'Enter a valid email address',
                ], 200);
            }
            $num_length = strlen((string) $appointment->phone_number);
            if ($num_length != 9) {
                return response()->json([
                    'status' => 'nok',
                    'message' => 'Enter a 9 digit phone number',
                ], 200);
            };
            foreach (Appointment::all() as $apt) {
                if ($apt->date == $appointment->date) {
                    if ($apt->start_time == $appointment->start_time) {
                        if ($apt->id != $appointment->id) {
                            return response()->json([
                                'status' => 'nok',
                                'message' => 'Appointment already exists',
                            ], 200);
                        }
                        //the same user can update a previous appointment without the need of changing the appointment hour
                    }
                }
            }
            $dayNum = Carbon::parse($appointment->date)->dayOfWeek;
            if ($dayNum == 0 || $dayNum == 6) {
                return response()->json([
                    'status' => 'nok',
                    'message' => 'Appointment day must be between Monday and Friday',
                ], 200);
            }
            $allowedHrs = [
                '09:00',
                '10:00',
                '11:00',
                '12:00',
                '13:00',
                '14:00',
                '15:00',
                '16:00',
                '17:00',
            ];
            $bool = false;
            foreach ($allowedHrs as $hr) {
                if ($hr == $appointment->start_time) {
                    $bool = true;
                }
            }
            if ($bool != true) {
                return response()->json([
                    'status' => 'nok',
                    'message' => 'Appointment must be between 09:00 and 18:00 hrs',
                ], 200);
            }
            $appointment->save();
            return response()->json([
                'status' => 'ok',
                'message' => 'Appointment updated successfully',
                'id' => $appointment->id,
            ], 200);

        } catch (\Throwable$th) {
            return response()->json(['status' => 400], 400);
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
            return response()->json([
                'status' => 'ok',
                'message' => 'Appointment deleted successfully',
            ], 200);
        } catch (\Throwable$th) {
            return response()->json(['status' => 400], 400);
        }
    }
}
