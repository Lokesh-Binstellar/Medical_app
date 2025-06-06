<?php

use Illuminate\Support\Facades\Broadcast;

// Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
//     return (int) $user->id === (int) $id;
// });
// Broadcast::channel('role.{role}', function ($user, $role) {
//     return $user->role === $role;
// });

Broadcast::channel('role.{role}.user.{id}', function ($user, $role, $id) {
    return $user->role === $role && (int) $user->id === (int) $id;
});

// Broadcast::channel('user.{token}', function ($user = null, $token) {
//     $user = \App\Models\Customers::where('token', $token)->first();

//     return $user !== null;
// });
Broadcast::channel('user.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});