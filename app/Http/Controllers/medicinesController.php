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
    public function classSearch(Request $request)
    {

        $classification_id = ClassSearchResource::collection(Medicine::where('Classification_id', $request->Classification_id)->get());
        if ($classification_id) {
            return response()->json($classification_id);
        } else {
            return response()->json("classification not found", 404);
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


//    public function storeOrder(Request $request)
//    {
//
//        foreach($request->input('Orders_Medicines') as $order_medicine) {
//            $medicine = Medicine::find($order_medicine['Medicines_id']);
//            if ($medicine->Available_Quantity < $order_medicine['Required_quantity']) {
//                return "the quantity of medicine {$medicine->Commercial_name} excedds the available quantity";
//            }
//        }
//        $order= Order::create([
//            'User_id'=>auth()->user()->id,
//        ]);
//
//        foreach($request->input('Orders_Medicines') as $order_medicine) {
//            $medicine = Medicine::find($order_medicine['Medicines_id']);
//            $price = Medicine::where('id',$order_medicine['Medicines_id'])->first()->Price;
//            Order_Medicines::create([
//                'Orders_id'=>$order->id,
//                'Medicines_id'=>$order_medicine['Medicines_id'],
//                'Required_quantity'=>$order_medicine['Required_quantity'],
//                'Price_Medicine'=>$price,
//                'quantity_price'=>($order_medicine['Required_quantity'])*$price,
//
//            ]);
//
//        }
//        return response()->json("order is done ", 200);
//    }
//    public function  update(Request $request){
//        $order=Order_Medicines::where('Orders_id',$request->id)->get();
//        $ordered=Order::where('id',$request->id)->get();
//        if($request->Order_Status=='sent'){
//             foreach($order as $medicineOrder){
//                 $medicine=Medicine::find($medicineOrder->Medicines_id);
//                 $medicine->update(['Available_Quantity'=> $medicine->Available_Quantity - $medicineOrder->Required_quantity]);
//             }
//        }
//    }
//
//

        public function storeOrder(Request $request)
    {
        $medsOrder = $request->meds;
        $userOrder = $request->User_id;
        $validator = validator::make($request->all(), [
            'User_id' => 'required|exists:users,id',
            //'meds.*.Required_quantity' => 'required|numeric|lte:meds.*.Available_Quantity',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $Us_id = Order::create([
            'User_id'=>$userOrder,
        ]);
        $Us_id->save();

        // Validate the request data
        // $request->validate([
        // 'User_id' => 'required|exists:users,id',
        //'item_count' => 'required|numeric|min:1',
        //'grand_total' => 'required|numeric|min:0',
        //'medicines' => 'required|array',
        //  'medicines.*.id' => 'required|exists:medicines,id',
        //  'medicines.*.quantity' => 'required|numeric|min:1',
        //  'medicines.*.price' => 'required|numeric|min:0',
        //]);



        // Loop through the medicines array and create order medicine objects
        foreach ($medsOrder as $medicine) {

            $medCreateOrder = Order_Medicines::create([
                'Orders_id'=>$Us_id->id,
                'Medicines_id'=>$medicine['Medicines_id'],
                'Required_quantity'=>$medicine['Required_quantity'],
                'Price_Medicine'=>$medicine['Price_Medicine'],
                'quantity_price'=>$medicine['Price_Medicine'] * $medicine['Required_quantity'],
            ]);
            $medCreateOrder->save();
        }

        return response()->json([
            'message' => 'Ordered Successful'
        ], 200);
    }

    public function showOrdersPharma(Request $request) {
        $user = auth()->user()->id;
        $showOrds = showOrdsPhResource::collection(Order::where('User_id',$user)->get());
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
        $orderSt = $request->orderSt;
        $upSt = Order::where('id',$request->id)->where('User_id',$request->User_id)->update([
            'Order_Status'=>$orderSt,
        ]);

        return response()->json([
            'message' => 'Status Change Successful'
        ], 200);
    }

    public function changePayment(Request $request) {
        $paySt = $request->paySt;
        $upSt = Order::where('id',$request->id)->where('User_id',$request->User_id)->update([
            'Payment_Status'=>$paySt,
        ]);
        return response()->json([
            'message' => 'Payment Status Change Successful'
        ], 200);
    }

}
