<?php

namespace App\Services;

use App\Models\Goal;
use App\Models\Role;
use App\Models\User;
use App\Models\Domain;
use App\Models\Profile;
use App\Models\Program;
use App\Models\Category;
use App\Models\UserRole;
use App\Mail\SendInfoUser;
use App\Models\Permission;
use Illuminate\Support\Str;
use App\Models\SousCategory;
use App\Models\UserSpeakers;
use Illuminate\Http\Request;
use App\Models\RolePermission;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Auth\Events\Registered;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use App\RepositoryInterface\UsersRepositoryInterface;
use App\Models\Cour;

class UsersServicesRepository  implements UsersRepositoryInterface 
{


    //crud profile


    public function edit_profile(String $id){
        return User::findOrFail(Crypt::decrypt($id));
    }


    public function update_profile(Request $request , $id){


        $profile = User::findOrFail(Crypt::decrypt($id));
            $request->validate([
                'firstName' => ['required', 'string', 'max:255'],
                'lastName' => ['required', 'string', 'max:255'],
                'email' => 'required|email|unique:users,email,' . $profile->id,
            ]);

            /* dd($profile); */
            
            $profile->firstName = $request->firstName;
            $profile->lastName = $request->lastName;
            $profile->email = $request->email;
            
            $profile->save();

        

    }


    public function delet_profile(Request $request ,String $id)
    {
        $user = User::findOrFail(Crypt::decrypt($id));

        $request->validate([
            'password' => ['required']
        ]);
       

        if(Hash::check( $request->password, Auth::user()->password ))
        {
            $user->userRole()->delete();
    
            $user->delete();
           
        }

    }




    public function password_update(Request $request )
    {

        $validate = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $request->user()->update([
            'password_change' => 1,
            'password' => Hash::make($validate['password']),
        ]);

        

    }


    //crud permission

    public function store_permission(Request $request )
    {

        $domain = Domain::create([
            'domain' => $request->domain,
        ]);

      /*   $permission =  Permission::create([
            'permission_name' => $request->permission_name,
        ]); */
        

    }


    //crud role

    public function create_role(){
        $permissions = Permission::all();

        $permission = Permission::findOrFail(7);
        $user_role = auth()->user()->userRole->role_id;

        $rolePermissionExists = RolePermission::where([
            'role_id' => $user_role,
            'permission_id' => $permission->id,
            'confirmed' => '1',
        ])->exists();

        $roleId = [3 , 4];
        $roles = Role::whereNotIn('id' , $roleId)->get();
        
        $RolePermissioncheck = RolePermission::whereIn('permission_id', $permissions->pluck('id'))
        ->where('confirmed', 1)
        ->whereIn('role_id', $roles->pluck('id'))
        ->get()
        ->groupBy('role_id');

        return ['rolePermissionExists' => $rolePermissionExists, 'permissions'=> $permissions ,'roles' => $roles ,'RolePermissioncheck' => $RolePermissioncheck]; 
    }

    public function store_role(Request $request){
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
        ]);

        $role = Role::create([
            'role_name' => $request->title,
            'description' => $request->description
        ]);


    }

    public function search_role(Request $request)
    {
        $query = $request->input('role');

        $permissions = Permission::all();

        $roleId = [3 , 4];
        $roles = Role::whereNotIn('id' , $roleId)->get();
        
        $RolePermissioncheck = RolePermission::whereIn('permission_id', $permissions->pluck('id'))
        ->where('confirmed', 1)
        ->whereIn('role_id', $roles->pluck('id'))
        ->get()
        ->groupBy('role_id');

        $filteredRoles = Role::where('role_name', 'like', '%'.$query.'%')->get();
    
        return ['filteredRoles' =>$filteredRoles , 'permissions'=> $permissions ,'roles' => $roles ,'RolePermissioncheck' => $RolePermissioncheck];
    }



    //crud role_permission

    public function store_role_permission(String $role , String $permission)
    {
        $RolePermissionNotExiste = RolePermission::where(['role_id' => $role , 'permission_id' => $permission])->exists();
        $HasNotPermission = RolePermission::where(['role_id' => $role , 'permission_id' => $permission , 'confirmed' => 0])->exists();
        $changepermission = RolePermission::where(['role_id' => $role , 'permission_id' => $permission])->first();
        

        if(!$RolePermissionNotExiste){
            $RolePermission = RolePermission::create([
                'role_id' => $role,
                'permission_id' => $permission,
                'confirmed' => true
            ]);
        }else if($HasNotPermission){
            $changepermission->update([
                'confirmed' => true
            ]);
        } else{
            $changepermission->update([
                'confirmed' => false
            ]);
        }

        return ['RolePermissionExiste'=>$RolePermissionNotExiste ];

    }


    //crud manger

    public function view_manager()
    {

        $excludedRoleIds = [1, 2, 3 , 4];

        $managers = UserRole::whereNotIn('role_id', $excludedRoleIds)->paginate(10);
        
        return $managers;

    }

    public function create_manger(){
        $roles = Role::whereNotIn('id', [1, 2 , 3 , 4])->get();

        return ['roles'=>$roles] ;
    }


    public function store_manager(Request $request){

        $password = $request->password;

        if($password == null){

            $request->validate([
                'firstName' => ['required', 'string', 'max:255'],
                'lastName' => ['required', 'string', 'max:255'],
                'role_id' => ['required'],
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            ]);

            $password  = Str::random(10);

            $request->password_confirmation = $password;
           
            $user = User::create([
                'firstName' => $request->firstName,
                'lastName' => $request->lastName,
                'email' => $request->email,
                'password' => Hash::make($password),
            ]);

            $user_role = UserRole::create([
                'user_id' => $user->id,
                'role_id' => $request->role_id
            ]) ;

        }else{

            $request->validate([
                'firstName' => ['required', 'string', 'max:255'],
                'lastName' => ['required', 'string', 'max:255'],
                'role_id' => ['required'],
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
                'password' => ['confirmed', Rules\Password::defaults()],
            ]);


            $user = User::create([
                'firstName' => $request->firstName,
                'lastName' => $request->lastName,
                'email' => $request->email,
                'password' => Hash::make($password),
            ]);

            $user_role = UserRole::create([
                'user_id' => $user->id,
                'role_id' => $request->role_id
            ]) ;


        }

        Mail::to($user->email)->send(new SendInfoUser($user , $password));
        
    }


    //crud speakers

    public function view_speaker()
    {
        $role = Role::where('role_name' , 'Speaker')->first();

        $speakers = UserRole::where('role_id' , $role->id)->paginate(10);

        return $speakers;
    }


    public function create_speakers()
    {
        $permission = Permission::findOrFail(6);
        $user_role = auth()->user()->userRole->role_id;

        $rolePermissionExists = RolePermission::where([
            'role_id' => $user_role,
            'permission_id' => $permission->id,
            'confirmed' => '1',
        ])->exists();


        return ['rolePermissionExists' => $rolePermissionExists];
    }


    public function store_speaker(Request $request){

        
        $role_admin = Role::where('role_name' , 'Speaker')->first();

        $request->validate([
            'firstName' => ['required', 'string', 'max:255'],
            'lastName' => ['required', 'string', 'max:255'],
            'type_speaker' => ['required' , 'string' , 'max:255'],
            'biographie' => ['required' , 'string' , 'max:300'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
        ]);

        $password  = Str::random(10);

        $request->password_confirmation = $password;

        $user = User::create([
            'firstName' => $request->firstName,
            'lastName' => $request->lastName,
            'password_change' => 1,
            'email' => $request->email,
            'password' => Hash::make($password),
        ]);

        $user_role = UserRole::create([
            'user_id' => $user->id,
            'role_id' => $role_admin->id
        ]);

        

        $speaker = UserSpeakers::create([
            'user_id' => $user->id,
            'type_speaker' => $request->type_speaker,
            'biographie' => $request->biographie
        ]);      

    }


    public function delete_speaker(Request $request ,String $id)
    {
        $user = User::findOrFail(Crypt::decrypt($id));

        $request->validate([
            'password' => ['required']
        ]);
       

        if(Hash::check( $request->password, Auth::user()->password ))
        {
            $user->userRole()->delete();

            $user->userspeaker->delete();
    
            $user->delete();
           
        }
    }


    //crud admin

    

    public function view_admin()
    {
        $role = Role::where('role_name' , 'Admin')->first();

        $admins = UserRole::where('role_id' , $role->id)->paginate(10);

        return $admins;
    }

    public function store_admin(Request $request){

         $password = $request->password;

         $role_admin = Role::where('role_name' , 'Admin')->first();

        if($password == null){

            $request->validate([
                'firstName' => ['required', 'string', 'max:255'],
                'lastName' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            ]);

            $password  = Str::random(10);

            $request->password_confirmation = $password;
           
            $user = User::create([
                'firstName' => $request->firstName,
                'lastName' => $request->lastName,
                'email' => $request->email,
                'password' => Hash::make($password),
            ]);

            $user_role = UserRole::create([
                'user_id' => $user->id,
                'role_id' => $role_admin->id
            ]);

        }else{

            $request->validate([
                'firstName' => ['required', 'string', 'max:255'],
                'lastName' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
                'password' => ['required','confirmed', Rules\Password::defaults()],
            ]);


            $user = User::create([
                'firstName' => $request->firstName,
                'lastName' => $request->lastName,
                'email' => $request->email,
                'password' => Hash::make($password),
            ]);

            $user_role = UserRole::create([
                'user_id' => $user->id,
                'role_id' => $role_admin->id
            ]);
        }

       

        Mail::to($user->email)->send(new SendInfoUser($user , $password));
        

    }


    


    //crud category

    public function create_category()
    {
        $categories = Category::paginate(10);

        $domains = Domain::all();

    
        return ['categories' => $categories , 'domains' => $domains ];
    }

    public function store_category(Request $request)
    {
        $request->validate([
            'domains' =>  ['required', 'string', 'max:255'],
            'category_name' => ['required', 'string', 'max:255'],
        ]);

        $categorie = Category::create([
            'domain_id' => $request->domains,
            'category_name' => $request->category_name,
        ]);

    }

    public function update_category(Request $request , String $id)
    {
        $category = Category::findOrFail(Crypt::decrypt($id));

        $request->validate([
            'category_name' => ['required', 'string', 'max:255'],
        ]);

        /* dd($profile); */
        
        $category->category_name = $request->category_name;
       
        
        $category->save();
    }


    public function delete_categoty(Request $request ,String $id)
    {
        $category =  Category::findOrFail(Crypt::decrypt($id));

        $request->validate([
            'password' => ['required'],
        ]);

        if(Hash::check( $request->password, Auth::user()->password )){
            foreach ($category->souscategories as $souscategory) {
                $souscategory->delete();
            }
    
            $category->delete();
        }

      

        
    }


    //crud souscategorie

    public function create_souscategory()
    {
        $souscategories = SousCategory::paginate(10);
        $categories = Category::all();

        return ['souscategories'=>$souscategories , 'categories' => $categories];
    }

    public function store_souscategory(Request $request)
    {
        $request->validate([
            'souscategory_name' => ['required', 'string', 'max:255'],
        ]);

        $categorie = SousCategory::create([
            'category_id' => $request->category,
            'souscategory_name' => $request->souscategory_name,
        ]);
    }

    public function update_souscategory(Request $request , String $id)
    {
        $souscategorie = SousCategory::findOrFail(Crypt::decrypt($id));

        $request->validate([
            'souscategory_name' => ['required', 'string', 'max:255'],
        ]);
        

        $souscategorie->category_id = $request->category;
        $souscategorie->souscategory_name = $request->souscategory_name;
       
        $souscategorie->save();
    }


    public function delete_souscategoty(Request $request ,String $id)
    {
        $souscategory =  SousCategory::findOrFail(Crypt::decrypt($id));

        $request->validate([
            'password' => ['required']
        ]);
       

        if(Hash::check( $request->password, Auth::user()->password ))
        {
            $souscategory->delete();
        }

       

        
    }





    //crud Program

    public function create_program(){
        $categories = Category::all();

        $programs = Program::all();

      
        return [ 'categories' => $categories , 'programs' => $programs];
    }

    public function store_program(Request $request)
    {

     
        $request->validate([
            'title'=> ['required', 'string', 'max:255'],
            'Description'=> ['required', 'string', 'max:600'],
            'tags'=> ['required', 'array'],
            'categories'=> ['required', 'array']
        ]);

        foreach ($request->tags as $key => $tag) {
            $tags[] = $tag; 
        }

        foreach ($request->categories as $key => $category) {
            $categories[] = $category ;
        }

        $program = Program::create([
            'title' => $request->title,
            'Description' => $request->Description,
            'tags' => $tags,
            'categories' => $categories
        ]);
       

    }

    public function update_program(Request $request , String $id)
    {
        $program = Program::findOrFail(Crypt::decrypt($id));

        $request->validate([
            'title'=> ['required', 'string', 'max:255'],
            'Description'=> ['required', 'string', 'max:600'],
            'tags'=> ['required', 'array'],
            'categories'=> ['required', 'array']
        ]);

        foreach ($request->tags as $key => $tag) {
            $tags[] = $tag; 
        }

        foreach ($request->categories as $key => $category) {
            $categories[] = $category ;
        }

        $program->title = $request->title;
        $program->Description = $request->Description;
        $program->tags = $tags;
        $program->categories = $categories;

        $program->save();



    }

    public function delete_program(Request $request,String $ig)
    {
        $program = Program::findOrFail(Crypt::decrypt($id));

        
        $request->validate([
            'password' => ['required']
        ]);
       

        if(Hash::check( $request->password, Auth::user()->password ))
        {
            $program->delete();
        }
      
    }


    //crud goals

    public function create_goals()
    {
        $goals = Goal::paginate(10);

        $souscategory = SousCategory::all();

        return ['goals' =>  $goals , 'souscategory' => $souscategory];
    }


    public function store_goals(Request $request)
    {
        $request->validate([
            'goals' =>  ['required', 'string', 'max:255'],
        ]);

        foreach ($request->souscatgory as $key => $souscatgory) {
            $goal = Goal::create([
                'souscategory_id' => $souscatgory,
                'goals' => $request->goals,
    
            ]);
    
        }

        

       
    }


    public function delete_goals(Request $request , String $id)
    {
        $goal = Goal::findOrFail(Crypt::decrypt($id));

        $goalsDelete = Goal::where('goals' , $goal->goals)->get();

        
        $request->validate([
            'password' => ['required']
        ]);
       

        if(Hash::check( $request->password, Auth::user()->password ))
        {

            foreach ($goalsDelete as $key => $goals) {
                $goals->delete();
            } 
           
        }
    }


    public function update_goals(Request $request , String $id)
    {
        $goal = Goal::findOrFail(Crypt::decrypt($id));

        $request->validate([
            'goals' => ['required', 'string', 'max:255'],
        ]);


        $goal->souscategory_id = $request->souscatgory;
        $goal->goals = $request->goals;

        $goal->save();

    }


    //crud cours 

    public function create_cours()
    {
        $souscategory = SousCategory::distinct()->get(['category_id']);

        

       /*  dd($souscategory); */

        return $souscategory ;
    }

    public function getGoalsBySousCategorie(String $id)
    {
        $goals = Goal::where('souscategory_id', $id)->get();

        return response()->json(['goals' => $goals]);
    }

    public function store_cours(Request $request)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:600'],
            'cotegoryId' =>  ['required', 'string', 'max:255'],
            'gaols_id' => ['required', 'array'],
            'tags' => ['required', 'array'],
            'coursType' => ['required', 'string', 'max:255']
        ]);

        if ($request->iscoming == 'on') {
           $iscoming = 1;
        }else{
            $iscoming = 0;
        }


        if ($request->isActive == 'on') {
            $isActive = true;
        }else{
            $isActive = false;
        }

        foreach ($request->gaols_id as $key => $goal) {
            $goals[] = $goal;
        }

        foreach ($request->tags as $key => $tag) {
            $tags[] = $tag; 
        }

        $cours = Cour::create([
            'title' => $request->title,
            'iscoming' => $iscoming,
            'isActive' => $isActive,
            'description' => $request->description,
            'category_id' => $request->cotegoryId,
            'gaols_id' => $goals,
            'tags' => $tags,
            'cours_type' => $request->coursType
        ]);



        return response()->json($cours);

    }

}