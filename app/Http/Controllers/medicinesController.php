<?php

namespace App\Http\Controllers;

use App\Http\Resources\ClassSearchResource;
use App\Http\Resources\showOrdersResource;
use App\Http\Resources\showOrdsPhResource;
use App\Models\Classification;
use App\Models\Medicine;
use App\Models\Order;
use App\Models\Order_Medicines;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class medicinesController extends Controller
{
    public function classSearch(Request $request) {

        $classification_id = ClassSearchResource::collection(Medicine::where('Classification_id',$request->Classification_id)->get());
        if($classification_id) {
            return response()->json($classification_id);
        }
        else {
            return response()->json("classification not found",404);
        }

    }

    public function medSearch(Request $request)
    {

        $medicine = Medicine::where('Commercial_name', $request->Commercial_name)->first();
        if ($medicine) {
            return response()->json($medicine);
        } else {
            return response()->json("medicine not found", 404);
        }
    }

    public function storeOrder(Request $request)
    {

    }

    public function showOrdersPharma(Request $request) {
        $showOrds = showOrdsPhResource::collection(Order::all());
        return response()->json($showOrds);
    }

    public function showOrdersWeb(Request $request) {
        $showOrds = showOrdersResource::collection(Order::all());
        return response()->json($showOrds);
    }

    public function showOneOrd(Request $request) {
        $showOne = Order_Medicines::where('Orders_id',$request->Orders_id)->get();
        return response()->json($showOne);
    }

    public function changeStatus(Request $request) {


    }

    public function changePayment(Request $request) {

    }

}
