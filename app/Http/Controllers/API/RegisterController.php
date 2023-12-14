<?php
namespace App\Http\Controllers\API;

use App\Http\Resources\GetClassificationsResource;
use App\Http\Resources\GetMedicineResource;
use App\Http\Resources\MedicineResource;
use App\Models\Classification;
use App\Models\Medicine;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends BaseController
{
/**
* Register api
*
* @return \Illuminate\Http\Response
*/
public function register(Request $request): JsonResponse
{
$validator = Validator::make($request->all(), [
    'name' => 'required',
    'password' => 'required|string',
    'phone'=>'required|unique:users,phone,|min:10|max:10',
]);
if($validator->fails()){
return $this->sendError('Validation Error.', $validator->errors());
}
$input = $request->all();
$input['password'] = bcrypt($input['password']);
$user = User::create($input);
$success['token'] =  $user->createToken('MyApp')->plainTextToken;
$success['name'] =  $user->name;

return $this->sendResponse($success, 'Register successful.');
}

/**
* Login api
*
* @return \Illuminate\Http\Response
*/
public function login(Request $request): JsonResponse
{
    $validator = Validator::make($request->all(), [
        'password' => 'required|string',
        'phone'=>'required|min:10|max:10|exists:users',
    ]);
    if($validator->fails()){
        return $this->sendError('Validation Error.', $validator->errors());
    }
    if(Auth::attempt(['phone' => $request->phone, 'password' => $request->password])){
        $authUser = Auth::user();
        $success['token'] =  $authUser->createToken('MyApp')->plainTextToken;
        $success['name'] =  $authUser->name;

        return $this->sendResponse($success, 'Signed in');
    }
    else{
        return $this->sendError('unauthorized', ['error'=>'Phone & Password does not match with our record.']);
    }
}
    public function logout(Request $request)
    {
        auth()->user()->tokens()->delete();
        return response()->json([
            'message' => 'Logout Successful'
        ], 200);
    }
    public function getClassifications(): JsonResponse          //get all classifications
    {

            $classification = GetClassificationsResource::collection(Classification::all());
            return response()->json($classification);

    }
    public function getMedicinesForClass(Request $request): JsonResponse        //get the medicines in this class
    {
        $medicines=MedicineResource::collection(Medicine::where('Classification_id',$request->Classification_id)->get());
        return response()->json($medicines);
    }
    public function getMedicine(Request $request): JsonResponse         //details for one med
    {
        //$medicine=GetMedicineResource::collection(Medicine::where('id',$request->id)->get());
        $medicine=GetMedicineResource::make(Medicine::where('id',$request->id)->first());
        return response()->json($medicine);

    }

}
