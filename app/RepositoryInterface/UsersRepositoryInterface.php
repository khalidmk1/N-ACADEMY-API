<?php

namespace App\RepositoryInterface;

use Illuminate\Http\Request;


interface UsersRepositoryInterface{

    //crud profile
    public function edit_profile(String $id);
    public function update_profile(Request $request , String $id);
    public function password_update(Request $request);
    public function delet_profile(Request $request ,String $id);

    //crud permission
    public function store_permission(Request $request);

    //crud role
    public function store_role(Request $request);
    public function search_role(Request $request);

    //crud role_permission
    public function store_role_permission(String $role , String $permission);

    //crud managers
    public function view_manager();
    public function create_manger();
    public function store_manager(Request $request);


    //crud speakers
    public function view_speaker();
    public function create_speakers();
    public function store_speaker(Request $request);
    public function delete_speaker(Request $request ,String $id);


    //crud admin
    public function view_admin();
    public function store_admin(Request $request);
   

    //crud categorie
    public function create_category();
    public function store_category(Request $request);
    public function update_category(Request $request , String $id);
    public function delete_categoty(Request $request , String $id);

    //crud SousCategory
    public function create_souscategory();
    public function store_souscategory(Request $request);
    public function update_souscategory(Request $request , String $id);
    public function delete_souscategoty(Request $request ,String $id);

    //crud Program
    public function create_program();
    public function store_program(Request $request);
    public function update_program(Request $request , String $id);
    public function delete_program(Request $request,String $id);


    //crud objectifs
    public function create_goals();
    public function store_goals(Request $request);
    public function update_goals(Request $request , String $id);
    public function delete_goals(Request $request , String $id);


    //crud cours
    public function create_cours();
    public function getGoalsBySousCategorie(String $id);
    public function store_cours(Request $request);

}