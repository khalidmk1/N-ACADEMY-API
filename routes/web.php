<?php


use Illuminate\Support\Facades\Route;

/* use App\Http\Controllers\Auth\EmailVerificationPromptController; */
use App\Http\Controllers\ReportController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\PorfileEditeController;
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\Auth\NewPasswordController;

use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

use App\Http\Controllers\Users\AdminController;
use App\Http\Controllers\Users\SpeakersController;
use App\Http\Controllers\Users\ManagerController;

use App\Http\Controllers\Fileter\SousCategoryController;
use App\Http\Controllers\Fileter\ProgramController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\Fileter\CategoriesController;
use App\Http\Controllers\Fileter\GoalsController;

use App\Http\Controllers\Cours\CoursController;

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

/* Route::get('/', function () {
    return view('Component.Auth.login');
}); */

Route::post('roles/create', [RolesController::class, 'store'])->name('roles.store');

Route::middleware(['auth' ])->group(function () {
    //change password
    Route::get('/password-change', [PorfileEditeController::class, 'edit_password'])->name('password.change');
    Route::patch('password/change', [PorfileEditeController::class, 'password_change'])->name('password-change.update');
});

Route::get('verify/email', [AuthenticatedSessionController::class, 'email_verify'])
->middleware('isSpeaker')
->name('email.verify');

Route::middleware('auth' , 'verified' , 'passwordChange' , 'isSpeaker' )->name('dashboard.')->prefix('/backoffice')->group(function () {
   Route::get('/', [ReportController::class, 'index'])->name('index');
   //crud of profile of admin
   Route::get('edit/{id}', [PorfileEditeController::class, 'edit_profile'])->name('profile.edit');
   Route::patch('update/{id}', [PorfileEditeController::class, 'update_profile'])->name('profile.update');
   Route::patch('password/update', [PorfileEditeController::class, 'password_update'])->name('password.update');
   /* Route::post('profile/delete/{id}', [PorfileEditeController::class, 'delet_profile'])->name('profile.delete'); */

   //crud of roles
   Route::get('roles', [RolesController::class, 'index'])->name('roles.index');
   Route::post('roles/create', [RolesController::class, 'store'])->name('roles.store');
   Route::get('search/role', [RolesController::class, 'search_role'])->name('search.role');
   
   //crud permission
   Route::get('permission', [PermissionController::class, 'create_permission'])->name('premission.create');
   Route::post('permission/store', [PermissionController::class, 'store_permission'])->name('premission.store');
   
   //crud manager
   Route::get('manager', [ManagerController::class, 'create_manager'])->name('manager.create'); 
   Route::post('manager/store', [ManagerController::class, 'store_manager'])->name('manager.store'); 
   Route::get('view/managers', [ManagerController::class, 'view_manager'])->name('manager.view');
   Route::delete('delete/manager/{id}', [ManagerController::class, 'delete_manager'])->name('manager.delete');

   //crud spearkers
   Route::get('speaker', [SpeakersController::class, 'create_speakers'])->name('speaker.create');
   Route::post('speaker/store', [SpeakersController::class, 'store_speaker'])->name('speaker.store');
   Route::get('view/speaker', [SpeakersController::class, 'view_speakers'])->name('speaker.view');
   Route::post('speaker/delete/{id}', [SpeakersController::class, 'delete_speaker'])->name('delete.speaker');

   //crud admin
   Route::get('admin', [AdminController::class, 'create_admin'])->name('admin.create');
   Route::post('admin/store', [AdminController::class, 'store_admin'])->name('admin.store');
   Route::get('admin/view', [AdminController::class, 'view_admin'])->name('view.admin');
   Route::delete('delete/admin/{id}', [AdminController::class, 'delete_admin'])->name('delete.admin');

   // store and update role permission
   Route::post('role-permission/{role}/{permission}', [RolePermissionController::class, 'store_role_permission'])->name('manage.role_permission');
   
   //crud category
   Route::get('category', [CategoriesController::class, 'create_category'])->name('category.create');
   Route::post('category/store', [CategoriesController::class, 'store_category'])->name('category.store');
   Route::patch('category/update/{id}', [CategoriesController::class, 'update_category'])->name('category.update');
   Route::post('category/delete/{id}', [CategoriesController::class, 'delete_category'])->name('category.delete');

   //crud souscategorie
   Route::get('souscategory', [SousCategoryController::class, 'create_souscategory'])->name('souscategorie.create');
   Route::post('souscategory/store', [SousCategoryController::class, 'store_souscategorie'])->name('souscategorie.store');
   Route::patch('souscategory/update/{id}', [SousCategoryController::class, 'update_souscategory'])->name('souscategory.update');
   Route::post('souscategorie/delete/{id}', [SousCategoryController::class, 'delete_souscategoty'])->name('souscategorie.delete');


   //crud Program
   Route::get('program', [ProgramController::class, 'create_program'])->name('program.create');
   Route::post('program/store', [ProgramController::class, 'store_program'])->name('program.store');
   Route::patch('program/update/{id}', [ProgramController::class, 'update_program'])->name('program.update');

   //crud goals
   Route::get('goals', [GoalsController::class, 'create_goals'])->name('goals.create');
   Route::post('goals/create', [GoalsController::class, 'store_goals'])->name('goals.store');
   Route::patch('goals/update/{id}', [GoalsController::class, 'update_goals'])->name('goals.update');
   Route::post('goals/delete/{id}', [GoalsController::class, 'delete_goals'])->name('goals.delete');

   //crud cours
   Route::get('cours/create', [CoursController::class, 'create_cours'])->name('cours.create');
   Route::get('goals-bySouscategory/{id}', [CoursController::class, 'getGoalsBySousCategorie'])->name('goals.Souscategory');
   Route::post('cours/store', [CoursController::class, 'store_cours'])->name('cours.store');


});





Route::middleware('guest')->group(function () {
    Route::get('/', [AuthenticatedSessionController::class, 'create'])->name('login.create');
    Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('user.create');
});







require __DIR__.'/auth.php';
