<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Profile\AvatarController;
use App\Http\Controllers\TicketController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use OpenAI\Laravel\Facades\OpenAI;
use Laravel\Socialite\Facades\Socialite;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    $users = DB::select('select * from users');
    // dd($users);
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/avatar', [AvatarController::class, 'update'])->name('profile.avatar');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::get('/openai', function () {
    $result = OpenAI::completions()->create([
        'model' => 'text-davinci-003',
        'prompt' => 'PHP is',
    ]);

    echo $result['choices'][0]['text']; // an open-source, widely-used, server-side scripting language.
});

require __DIR__ . '/auth.php';


Route::get('/auth/redirect', [LoginController::class, 'redirectToProvider'])->name('github');
Route::get('/github/auth/callback', [LoginController::class, 'handleProviderCallback']);

// github 
// Route::get('/auth/redirect', function () {
//     // dd("Hello");
//     return Socialite::driver('github')->redirect();
// });

// Route::get('/auth/callback', function () {
//     // dd('redirect user login');
//     $user = Socialite::driver('github')->user();
//     $user = User::updateOrCreate(['email' => $user->email], [
//         'name' => $user->name,
//         'password' => 'password',
//     ]);


//     Auth::login($user);

//     return redirect('/dashboard');


//     // $user = User::updateOrCreate([
//     //     'github_id' => $githubUser->id,
//     // ], [
//     //     'name' => $githubUser->name,
//     //     'email' => $githubUser->email,
//     //     'github_token' => $githubUser->token,
//     //     'github_refresh_token' => $githubUser->refreshToken,
//     // ]);

//     // Auth::login($user);

//     // return redirect('/dashboard');
// });


Route::middleware('auth')->group(function () {
// Route::middleware('auth')->prefix('ticket')->group(function () {
    // resource route
    Route::resource('/ticket', TicketController::class);
    // Route::resource('random', TicketController::class);
    // menual resource route
    // Route::get('/ticket/create', [TicketController::class, 'create'])->name('ticket.create');
    // Route::post('/ticket/create', [TicketController::class, 'store'])->name('ticket.store');
});
