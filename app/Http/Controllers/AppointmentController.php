<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAppointmentRequest;
use App\Http\Requests\UpdateAppointmentRequest;
use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index()
    {
        # code...
    }

    public function store(StoreAppointmentRequest $request)
    {
        $formInputs = $request->only([
            'lead_id',
            'created_by',
            'title',
            'description',
            'start',
            'end',
        ]);

        Appointment::create($formInputs);

        return response(['message' => 'Cita registrada correctamente']);
    }

    public function edit(UpdateAppointmentRequest $request)
    {
        # code...
    }
}
