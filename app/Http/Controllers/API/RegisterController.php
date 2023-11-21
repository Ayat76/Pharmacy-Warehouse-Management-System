<?php
namespace App\Http\Controllers\API;

use App\Http\Resources\MedicineResource;
use App\Models\Classification;
use App\Models\Medicine;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
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
'password' => 'required',
    'phone'=>'required',


]);

if($validator->fails()){
return $this->sendError('Validation Error.', $validator->errors());
}

$input = $request->all();
$input['password'] = bcrypt($input['password']);
$user = User::create($input);
$success['token'] =  $user->createToken('MyApp')->plainTextToken;
$success['name'] =  $user->name;

return $this->sendResponse($success, 'User register successfully.');
}

/**
* Login api
*
* @return \Illuminate\Http\Response
*/
public function login(Request $request): JsonResponse
{
    if(Auth::attempt(['phone' => $request->phone, 'password' => $request->password])){
        $authUser = Auth::user();
        $success['token'] =  $authUser->createToken('MyApp')->plainTextToken;
        $success['name'] =  $authUser->name;

        return $this->sendResponse($success, 'User signed in');
    }
    else{
        return $this->sendError('Unauthorised.', ['error'=>'Unauthorised']);
    }
}
    public function logout(Request $request)
    {
        auth()->user()->tokens()->delete();
        return response()->json([
            'message' => 'Logout Successful'
        ], 200);
    }
    public function getClassifications(){
    $classification=Classification::all();
    return response()->json($classification);
    }
    public function getClass(Request $request){
        $medicines=MedicineResource::collection(Medicine::where('Classification_id',$request->Classification_id)->get());
        return response()->json($medicines);
    }
    public function getMedicine(Request $request){
        $medicine=Medicine::where('id',$request->id)->first();
        return response()->json($medicine);
    }
}
