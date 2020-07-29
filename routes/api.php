<?php

use App\Lote;
use App\User;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});




Route::post('lotes', function (Request $request) {
  return Lote::where('saldo', '>', 0)->where('active',true)->where('banco_id', $request->banco)->orderBy('id', 'asc')->get();

});

Route::post('users', function (Request $request) {

    $search = $request->search;

    if($search == ''){
       $employees = User::orderby('email','asc')->select('id','email')->limit(5)->get();
    }else{
       $employees = User::orderby('email','asc')->select('id','email')->where('email', 'like', '%' .$search . '%')->limit(5)->get();
    }

    $response = array();
    foreach($employees as $employee){
       $response[] = array(
            "id"=>$employee->id,
            "text"=>$employee->email
       );
    }

    echo json_encode($response);
    exit;


});