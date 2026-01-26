<?php

use Illuminate\Support\Facades\Route;

Route::get('/ping', fn () => 'Pong');

//
// Route::get('/comments', function (Request $request) {
//    if (!Gate::allows('viewAny', \App\Models\Comment::class)) {
//        abort(403);
//    }
//    return \App\Models\Comment::all();
// })->middleware('auth:sanctum');
//
// Route::get('/comments/{comment}', function (Request $request, \App\Models\Comment $comment) {
//    return $comment->delete();
// })->middleware('auth:sanctum');
//
// Route::post('/login', function (Request $request) {
//    $credentials = $request->validate([
//        'email' => 'required|email',
//        'password' => 'required',
//    ]);
//
//    if (Auth::guard('web')->attempt([
//        'email' => $credentials['email'],
//        'password' => $credentials['password'],
//    ])) {
//        $request->session()->regenerate();
//
//        $user = Auth::guard('web')->user();
//
//        return response()->json([
//            'message' => 'Успешный вход',
//            'user' => $user,
//        ]);
//    }
//
//    return response()->json(['message' => 'Неверные учётные данные'], 401);
// });
//
// // Маршрут для выхода
// Route::post('/logout', function (Request $request) {
//    Auth::guard('web')->logout();
//    $request->session()->invalidate();
//    $request->session()->regenerateToken();
//
//    return response()->json(['message' => 'Вышел']);
// })->middleware('auth:sanctum');
