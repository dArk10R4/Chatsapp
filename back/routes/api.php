<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\UserController;
use App\Http\Middleware\HeaderChecker;
use BeyondCode\LaravelWebSockets\Facades\WebSocketsRouter;
use App\Http\Controllers\Webscokets\SocketHandler\UpdateWebSocketHandler;
use App\Events\MessageEvent;

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

Route::controller(AuthController::class)->group(function () {
    Route::post('/login', 'login');
    Route::post('/register', 'register');
});

Route::middleware(['auth:api'])->prefix('v1')->group(function(){
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::get('/salam',function (Request $request) {
        return "salam";
    })->middleware('checker');
    Route::get('/users',[UserController::class,'getUsers']);
    Route::get('/messages',[UserController::class,'getMessages']);
});

Route::get('/ws',function () {
    $message = App\Models\Message::find(1)->get()[0];
    // dump($message);
    // return $message;
    broadcast(new MessageEvent($message));
    return "salam";
})->withoutMiddleware(['auth:api']);


