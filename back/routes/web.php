<?php

use App\Events\MessageEvent;
use BeyondCode\LaravelWebSockets\Facades\WebSocketsRouter;
use App\Http\Controllers\Webscokets\SocketHandler\UpdateWebSocketHandler;
use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// WebSocketsRouter::webSocket('/socket/update-post',UpdateWebSocketHandler::class);