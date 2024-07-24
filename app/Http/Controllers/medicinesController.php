<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Http\Resources\ClassSearchResource;
use App\Http\Resources\favoriteResource;
use App\Http\Resources\notificationResource;
use App\Http\Resources\showOrdersResource;
use App\Http\Resources\showOrdsPhResource;
use App\Models\Classification;
use App\Models\Favorite_List;
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
            $user_id = $request->user_id;
            $validator = Validator::make($request->all(), [
                'user_id' =>'required',
            'id' => 'required',
            'Payment_Status'=>'required|in:paid,unpaid',
        ]);
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }
            $order=Order_Medicines::where('Orders_id',$request->id)->get();
            $p=0;
            foreach($order as $result){
                $p+=$result['quantity_price'];
            }
        Order::where('id',$request->id)->where('user_id',$user_id)->update([
            'Payment_Status'=>$request->Payment_Status,
            'final_price'=>$p,
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

    public function showOneOrd(Request $request) {              //there are some problems here/Iam the Problem
        $showOnes = Order_Medicines::where('Orders_id', $request->Orders_id)->get();
        return response()->json($showOnes);
    }

    public function deleteOrder(Request $request) {
        Order::where('id',$request->id)->delete();
        return response()->json("Order Delete Successfully");
    }


    public function favoritmed(Request $request){
        $user = auth()->user()->id;

        if(Favorite_List::where('User_id',$user)->where('Medicines_id',$request->Medicines_id)->first()){
            return response()->json([
                'message' => 'Previously saved to Favorites'
            ], 200);
        }
        else{
            Favorite_List::create(['User_id'=>$user,'Medicines_id'=>$request->Medicines_id]);
            return response()->json([
                'message' => 'Saved to favorites'
            ], 200);
        }
    }
    public function getfavoritemed(){
        $user = auth()->user()->id;
        $favorite= favoriteResource::collection(Favorite_List::where('User_id',$user)->get());
        return response()->json($favorite, 200);

    }
    //هاد التقرير بالمستودع

    public function orderReport(Request $request){
        $sixMonthsAgo = now()->subMonths(6);
        $orders=Order_Medicines::where('created_at', '>=',$sixMonthsAgo)->get();
        return response()->json($orders);
    }



    public function medicineReport(Request $request){
        // get the date of one month ago
        $oneMonthAgo = now()->subMonth();
        $orders=Order_Medicines::select('Medicines_id')
            ->selectRaw('count(Medicines_id) as Duplicate')
            ->groupBy('Medicines_id')
            ->orderBy('Duplicate','desc')
            ->having('Duplicate', '>=', 1)
            ->whereDate('created_at', '>', $oneMonthAgo) // filter by date
            ->first();
            $max_id = $orders->Medicines_id;
            $name_med = Medicine::where('id',$max_id)->first();
            $result =  $name_med->Commercial_name;
            return response()->json($result);
    }

    public function showNotWeb(Request $request) {
        $notifications = notificationResource::collection(\App\Models\Notification::where('notifiable_id',1)->get());
        return response()->json($notifications,200);
    }

    public function showNotPharma(Request $request) {
        $user = auth()->user()->id;
        $notifications = notificationResource::collection(\App\Models\Notification::where('notifiable_id',$user)->get());
        return response()->json($notifications,200);
    }

}
