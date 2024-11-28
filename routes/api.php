<?php
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\CRUDManager;


Route::any("user/register", function(){
    $faker = Faker\Factory::create();

    $user = new User();
    $user->name = $faker->name;
    $user->email = $faker->unique()->safeEmail;
    $user->password = Hash::make('password');

if($user->save()){
    $token = $user->createToken("auth_token")->plainTextToken;
    return response()->json(["success" => "success", "data" => $user,
    "token" => $token,
    "message" => "User created successfully"]);
}
return response()->json(["success" => "failed", "message" => "Failed to create user"]);
});

Route::prefix("product")->middleware("auth:sanctum")->group(function(){
    Route::post("create",[CRUDManager::class, 'create']);
    Route::post("read", [CRUDManager::class, 'read']);
    Route::post("update/{id}",[CRUDManager::class, 'update']);
    Route::post("delete/{id}",[CRUDManager::class, 'delete']);
    
});