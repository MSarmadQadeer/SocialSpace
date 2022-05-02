<?php

use App\Http\Controllers\SignupController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PostsController;
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
    return redirect('/login');
});

Route::get('/login', function () {
    return view('screens.login');
})->name("login-screen");

Route::get('/signup', function () {
    return view('screens.signup');
})->name("signup-screen");


// ==================
// Login Controller
// ==================

Route::post('/login', [LoginController::class, 'login']);


// ==================
// SignUp Controller
// ==================

Route::post('/signup', 'App\Http\Controllers\SignupController@createAccount');

Route::post('/verify-email', 'App\Http\Controllers\SignupController@verifyEmail');


// ==================
// Profile Controller
// ==================

Route::post('/upload-profile-img', [ProfileController::class, 'uploadProfileImg'])->name('upload-profile-img');

Route::post('/edit-bio', [ProfileController::class, 'editBio']);

Route::get('/profile-{id}', [ProfileController::class, 'move_to_profile_through_ID'])->name("To-Profile-Through-ID");


// ==================
// Posts Controller
// ==================

Route::post('/delete-post', [PostsController::class, 'deletePost']);

Route::get('/post-{id}', [PostsController::class, 'get_post_data_through_ID'])->name("Get-Post-Data-Through-ID");

Route::post('/update-post', [PostsController::class, 'updatePost']);

Route::post('/create-post', [PostsController::class, 'createPost']);

Route::post('/comment', [PostsController::class, 'addComment']);

Route::post('/like', [PostsController::class, 'liked']);

Route::post('/unlike', [PostsController::class, 'unliked']);

/* Home Screen Route*/
Route::get('/home', [PostsController::class, 'getPosts'])->name("home-screen");
