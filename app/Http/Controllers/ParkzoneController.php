<?php

namespace App\Http\Controllers;

use App\Models\Parkzone;
use App\Models\Category;
use App\Models\Quartier;
use App\Models\CategoryWiseParkzoneSlot;
use App\Models\CategoryWiseParkzoneSlotNumber;
use App\Models\Floor;
use Illuminate\Support\Facades\Storage;
use App\Models\Gallery;




use App\Models\cities;
use App\Models\FloorSlot;
use App\Models\Parking;
use App\Models\Side_slot;
use App\Models\Sides;
use Exception;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\App;
use Mockery\Undefined;

class ParkzoneController extends Controller
{
    public function slotbytypeandid($type, $id)
    {
        $data = [];
        $now = date('Y-m-d H:i:s');
        $after24 = date('Y-m-d H:i:s', strtotime('+24 hours'));
        $availableTime = [];
        $all = [];
        if ($type == 'standard') {
            $table = "category_wise_parkzone_slots";
            $data = Parking::where('slot_id', $id)->where("table_name", $table)->get();
        } elseif ($type == 'floor') {
            $table = "floor_slots";
            $data = Parking::where('slot_id', $id)->where("table_name", $table)->get();
        } elseif ($type == 'side') {
            $table = "side_slots";
            $data = Parking::where('slot_id', $id)->where("table_name", $table)->get();
        }
        $newnow = strtotime($now);
        $newafter24 = strtotime($after24);
        array_push($all, $newnow, $newafter24);
        foreach ($data as $index => $da) {
            $newintime = strtotime(date('Y-m-d H:i:s', strtotime($da->in_time)));
            $newouttime = strtotime(date('Y-m-d H:i:s', strtotime($da->out_time)));
            array_push($all, $newintime, $newouttime);
        }

        $num = 0;
        for ($i = $newnow; $i <= $newafter24; $i++) {
            foreach ($all as $index => $al) {
                if ($i == $al) {
                    $availableTime[$num] = date('H:i', $i);
                    $num++;
                }
            }
        }

        $temp = [];
        $temp = $availableTime;
        if (count($availableTime) % 2 != 0) {
            array_shift($temp);
        }
        $availableTime = [];
        $rnd = 0;
        $num = 0;
        foreach ($temp as $tmp) {
            if ($rnd == 0) {
                $availableTime[$num]["from"] = $tmp;
                $rnd = 1;
            }else{
                $availableTime[$num]["to"] = $tmp;
                $rnd = 0;
                $num++;
            }
        }

        return response()->json($availableTime);
    }


    public function readApi()
    {
        $data = [];
        $parkzones = Parkzone::all();
        $categories = Category::all();
        foreach ($parkzones as $index => $parkzone) {
            $data[$index] = $parkzone->getAttributes();

            if ($parkzone->type == 'standard') {
                foreach ($categories as $categorie) {
                    $data[$index]["category"][$categorie->type]["total"] = count(CategoryWiseParkzoneSlot::where('parkzone_id', $parkzone->id)->where('category_id', $categorie->id)->get());
                    $data[$index]["category"][$categorie->type]["available"] = count(CategoryWiseParkzoneSlot::where('parkzone_id', $parkzone->id)->where('category_id', $categorie->id)->whereDoesntHave('active_parking')->get());
                }
            } elseif ($parkzone->type == 'floor') {
                foreach ($categories as $categorie) {
                    $data[$index]["category"][$categorie->type]["total"] = count(FloorSlot::whereHas('floor', function ($query) use ($parkzone) {
                        $query->where('parkzone_id', $parkzone->id);
                    })->where('categorie_id', $categorie->id)->get());
                    $data[$index]["category"][$categorie->type]["available"] = count(FloorSlot::whereHas('floor', function ($query) use ($parkzone) {
                        $query->where('parkzone_id', $parkzone->id);
                    })->where('categorie_id', $categorie->id)->whereDoesntHave('active_parking')->get());
                }
            } elseif ($parkzone->type == 'side') {
                foreach ($categories as $categorie) {
                    $data[$index]["category"][$categorie->type]["total"] = count(Side_slot::whereHas('side', function ($query) use ($parkzone) {
                        $query->where('parkzone_id', $parkzone->id);
                    })->where('category_id', $categorie->id)->get());
                    $data[$index]["category"][$categorie->type]["available"] = count(Side_slot::whereHas('side', function ($query) use ($parkzone) {
                        $query->where('parkzone_id', $parkzone->id);
                    })->where('category_id', $categorie->id)->whereDoesntHave('active_parking')->get());
                }
            }
        }
        foreach ($data as $index => $da) {
            foreach ($da["category"] as $inde => $d) {
                if ($data[$index]["category"][$inde]["available"] == 0) {
                    unset($data[$index]["category"][$inde]);
                }
            }
        }
        return response()->json($data);
    }

    public function searchParkzones($text = null)
    {
        if ($text == null) {
            $parkzone = Parkzone::take(10)
                ->with("Quartier")
                ->with("Quartier.city")
                ->get();
        } else {
            // $parkzone = Parkzone::join("quartiers", 'quartier_id', '=', 'quartiers.id')
            //     ->where(function ($query) use ($text) {
            //         $query->where("name", 'like', '%' . $text . '%')
            //             ->orWhere("quartiers.quartier_name", 'like', '%' . $text . '%');
            //     })
            //     ->with("Quartier")
            //     ->with("Quartier.city")
            //     ->get();
            $parkzone = Parkzone::with("Quartier")
                ->with("Quartier.city")
                ->where('name', 'like', '%' . $text . '%')
                ->orWhereHas('Quartier', function ($query) use ($text) {
                    $query->where('quartier_name', 'like', '%' . $text . '%');
                })
                ->get();
        }
        return response()->json($parkzone);
    }

    public function readApiById($id)
    {
        $parkzones = Parkzone::find($id);
        if ($parkzones->type == 'standard') {
            $data = CategoryWiseParkzoneSlot::where('parkzone_id', $id)->with('category')->get();
        } elseif ($parkzones->type == 'floor') {
            $data = FloorSlot::whereHas('floor', function ($query) use ($parkzones) {
                $query->where('parkzone_id', $parkzones->id);
            })->with('category')->get();
        } elseif ($parkzones->type == 'side') {
            $data = Side_slot::whereHas('side', function ($query) use ($parkzones) {
                $query->where('parkzone_id', $parkzones->id);
            })->with('category')->get();
        }
        // dd($data);
        return response()->json($data->groupBy('category.type'));
    }
    public function readApiByIdAndCat($id, $cat)
    {
        $parkzone = Parkzone::find($id);
        $categorie = Category::where('type', $cat)->first();
        if ($parkzone->type == 'standard') {
            $data = CategoryWiseParkzoneSlot::where('parkzone_id', $id)->where('category_id', $categorie->id)->get();
            return response()->json([
                "slots" => $data->groupBy('floor.level'),
                "type" => "standard"
            ]);
        } elseif ($parkzone->type == 'floor') {
            $data = FloorSlot::whereHas('floor', function ($query) use ($parkzone) {
                $query->where('parkzone_id', $parkzone->id);
            })->where('categorie_id', $categorie->id)->with('floor')->get();
            return response()->json([
                "slots" => $data->groupBy('floor.level'),
                "type" => "floor"
            ]);
        } elseif ($parkzone->type == 'side') {
            $data = Side_slot::whereHas('side', function ($query) use ($parkzone) {
                $query->where('parkzone_id', $parkzone->id);
            })->where('category_id', $categorie->id)
                ->with('side')
                ->get();
            return response()->json([
                "slots" => $data->groupBy('side.side'),
                "type" => "side"
            ]);
        }
    }
    public function readTariffByIdAndCat($id, $cat)
    {
        $parkzone = Parkzone::where("id", $id)->with("Quartier", "Quartier.city")->first();
        $categorie = Category::where('type', $cat)->first();
        return response()->json([
            "parkzone" => $parkzone,
            "tariff" => $parkzone->tariff_by_cat($categorie->id)->orderBy('number_hour')->get(),
        ]);
    }
}
