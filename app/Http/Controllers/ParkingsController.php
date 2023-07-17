<?php

namespace App\Http\Controllers;

use App\Models\Parking;
use App\Models\Userclient;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ParkingsController extends Controller
{
    public function store(Request $request)
    {

        $name = Userclient::find($request->user_id)->name;
        $table_name = '';
        $user = User::find($request->user_id);
        switch ($request->type) {
            case "floor":
                $table_name = 'floor_slots';
                break;
            case "side":
                $table_name = 'side_slots';
                break;
            case "standard":
                $table_name = 'category_wise_parkzone_slots';
                break;
        }
        $Parkings = Parking::where('slot_id', $request->slot_id)->where('table_name', $table_name)->where('category_id', $request->category_id)->get();
        foreach ($Parkings as $Parking) {
            $check = $this->checkDate(date('Y-m-d H:i:s', strtotime($Parking->in_time)), date('Y-m-d H:i:s', strtotime($Parking->out_time)), date('Y-m-d H:i:s', strtotime($request->in_time)));

            if ($check == "Between") {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Parking already exists',
                ], 200);
            } else {
                $check = $this->checkDate(date('Y-m-d H:i:s', strtotime($Parking->in_time)), date('Y-m-d H:i:s', strtotime($Parking->out_time)), date('Y-m-d H:i:s', strtotime($request->out_time)));
                if ($check == "Between") {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Parking already exists',
                    ], 200);
                }
            }
        }

        $data = [
            'user_id' => $request->user_id,
            'category_id' => $request->category_id,
            'slot_id' => $request->slot_id,
            'table_name' => $table_name,
            'driver_name' => $name,
            "out_time" => date_create_from_format('Y-m-d\TH:i:s.u\Z', $request->out_time)->format('Y-m-d H:i:s'),
            "in_time" => date_create_from_format('Y-m-d\TH:i:s.u\Z', $request->in_time)->format('Y-m-d H:i:s'),
            "amount" => $request->tariff,
            "created_by" => $request->user_id,
            "vehicle_no" => "123456",
            "paid" => "1",
            "status" => "1",
            'modified_by'   => $request->user_id,
            'barcode'       => date('YmdHis') . $user->id,
            'driver_mobile' => '1234567890',
        ];
        $parking = Parking::create($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Parking created successfully',
            'data' => $parking
        ], 200);
    }

    public function checkDate($start, $end, $given)
    {
        // dd("start" . $start, "end" . $end,"give" . $given);
        $startDate = date('Y-m-d H:i:s', strtotime($start));
        $startDate = strtotime($startDate);
        $endDate = date('Y-m-d H:i:s', strtotime($end));
        $endDate = strtotime($end);
        $givenDate = date('Y-m-d H:i:s', strtotime($given));
        $givenDate = strtotime($given);
        if ($startDate <= $givenDate && $givenDate <= $endDate) {
            return "Between";
            // dd("start" . $start, "end" . $end,"give" . $given, "Between");
        } else {
            return "Not Between";
            // dd("start" . $start, "end" . $end,"give" . $given, "Not Between");
        }
    }
    // public fun
}
