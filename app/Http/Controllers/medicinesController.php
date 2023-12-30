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
use App\Models\User;
use App\Notifications\updateNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
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

        $orders = $request->all();

        foreach($orders as $order_medicine) {

            $medicine = Medicine::find($order_medicine['Medicines_id']);
            if ($medicine->Available_Quantity < $order_medicine['Required_quantity']) {
                $medicineC['medicine']=$medicine->Commercial_name;
                $medicineC['Available_Quantity']=$medicine->Available_Quantity;
                $medicineC['Required_quantity']= $order_medicine['Required_quantity'];
                return $this->sendError('the quantity of medicine exceeds',$medicineC);
            }
        }
        $order= Order::create([
            'User_id'=>auth()->user()->id,
        ]);

        foreach($orders as $order_medicine) {

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

        $user = User::find(1);
        $userOrder = auth()->user()->id;
        $message = " The user $userOrder requested an order!";
        Notification::send($user,new updateNotification($message));

        return response()->json([
           'message' => 'Ordered Successful'
        ], 200);
    }
    public function  updateOrderSt(Request $request){

        if(auth()->user()->Is_Admin) {
            $validator = Validator::make($request->all(), [
                'User_id' => 'required|exists:users,id',
                'id' => 'required',
                'Order_Status' => 'required|in:sent,received,pending',
            ]);
            if ($validator->fails()) {
                return $this->sendError('Validation Error.', $validator->errors());
            }

            $order = Order_Medicines::where('Orders_id', $request->id)->get();
            $ord = Order::where('id', $request->id)->first();

            Order::where('id', $request->id)->update([
                'Order_Status' => $request->Order_Status,
                //'Payment_Status'=>$request->Payment_Status
            ]);
            foreach ($order as $medicineOr) {
                $medicine = Medicine::find($medicineOr->Medicines_id);
                if ($medicine->Available_Quantity < $medicineOr->Required_quantity) {
                    $medicineC['medicine'] = $medicine->Commercial_name;
                    $medicineC['Available_Quantity'] = $medicine->Available_Quantity;
                    $medicineC['Required_quantity'] = $medicineOr->Required_quantity;

                    return $this->sendError('the quantity of medicine exceeds ', $medicineC);
                }
            }
            if ($ord->Order_Status != $request->Order_Status) {
                foreach ($order as $medicineOrder) {
                    $medicine = Medicine::find($medicineOrder->Medicines_id);
                    $medicine->update(['Available_Quantity' => $medicine->Available_Quantity - $medicineOrder->Required_quantity]);
                }
                $user = User::find($request->User_id);
                $message = "your order $request->Order_Status ";
                Notification::send($user, new updateNotification($message));
            }


            return response()->json([
                'message' => 'Update Successful'
            ], 200);
        }
        else {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
    }

    public function  updatePaymentSt(Request $request){
        if(auth()->user()->Is_Admin) {

            $validator = Validator::make($request->all(), [
            'id' => 'required',
            'Payment_Status'=>'required|in:paid,unpaid',
        ]);
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        Order::where('id',$request->id)->update([
            'Payment_Status'=>$request->Payment_Status
        ]);

        return response()->json([
            'message' => 'Update Successful'
        ], 200);
        }
        else {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
    }

    public function showOrdersPharma(Request $request) {
        $user = auth()->user()->id;
        $showOrds = showOrdsPhResource::collection(Order::where('User_id',$user)->get());
        return response()->json($showOrds);
    }

    public function showOrdersWeb(Request $request) {
        if(auth()->user()->Is_Admin) {

            $showOrds = showOrdersResource::collection(Order::all());
        return response()->json($showOrds);
        }
        else {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

    }

    public function showOneOrd(Request $request) {              //there are some problems here
        $showOnes = Order_Medicines::where('Orders_id', $request->Orders_id)->get();
        return response()->json($showOnes);
    }

    public function deleteOrder(Request $request) {
        Order::where('id',$request->id)->delete();
        return response()->json("Order Delete Successfully");
    }

}
