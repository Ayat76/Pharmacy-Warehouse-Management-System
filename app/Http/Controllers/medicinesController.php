<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Http\Resources\ClassSearchResource;
use App\Http\Resources\showOrdersResource;
use App\Http\Resources\showOrdsPhResource;
use App\Models\Classification;
use App\Models\Medicine;
use App\Models\Order;
use App\Models\Order_Medicines;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class medicinesController extends BaseController
{
    public function classSearch(Request $request)
    {
        $class=Classification::where('id',$request->Classification_id)->first();
        if($class){
        $classification_id = ClassSearchResource::collection(Medicine::where('Classification_id', $request->Classification_id)->get());
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


    public function storeOrder(Request $request)
    {

        foreach($request->input('Orders_Medicines') as $order_medicine) {
            $medicine = Medicine::find($order_medicine['Medicines_id']);
            if ($medicine->Available_Quantity < $order_medicine['Required_quantity']) {
                $medicineC['medicine']=$medicine->Commercial_name;
                $medicineC['Available_Quantity']=$medicine->Available_Quantity;
                $medicineC['Required_quantity']= $order_medicine['Required_quantity'];
                return $this->sendError('the quantity of medicine excedds',$medicineC);
            }
        }
        $order= Order::create([
            'User_id'=>auth()->user()->id,
        ]);

        foreach($request->input('Orders_Medicines') as $order_medicine) {
            $medicine = Medicine::find($order_medicine['Medicines_id']);
            $price = Medicine::where('id',$order_medicine['Medicines_id'])->first()->Price;
            Order_Medicines::create([
                'Orders_id'=>$order->id,
                'Medicines_id'=>$order_medicine['Medicines_id'],
                'Required_quantity'=>$order_medicine['Required_quantity'],
                'Price_Medicine'=>$price,
                'quantity_price'=>($order_medicine['Required_quantity'])*$price,

            ]);

        }
        return response()->json([
           'message' => 'Ordered Successful'
        ], 200);
    }
    public function  update(Request $request){
        $order=Order_Medicines::where('Orders_id',$request->id)->get();
        $ord=Order::where('id',$request->id)->first();
        Order::where('id',$request->id)->update([
            'Order_Status'=>$request->Order_Status,
            'Payment_Status'=>$request->Payment_Status
        ]);
        foreach($order as $medicineOr){
            $medicine=Medicine::find($medicineOr->Medicines_id);
            if ($medicine->Available_Quantity < $medicineOr->Required_quantity) {
                $medicineC['medicine']=$medicine->Commercial_name;
                $medicineC['Available_Quantity']=$medicine->Available_Quantity;
                $medicineC['Required_quantity']= $medicineOr->Required_quantity;
            return $this->sendError('the quantity of medicine excedds ',$medicineC);
            }
        }
        if($request->Order_Status=='sent'  && $ord->Order_Status!='sent' ){
             foreach($order as $medicineOrder){
                 $medicine=Medicine::find($medicineOrder->Medicines_id);
                 $medicine->update(['Available_Quantity'=> $medicine->Available_Quantity - $medicineOrder->Required_quantity]);
             }
        }
        return response()->json([
            'message' => 'Update  Successful'
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

}
