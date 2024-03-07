<?php

namespace App\Http\Controllers\Cours;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\RepositoryInterface\UsersRepositoryInterface;

class CoursController extends Controller
{

    private $userRepository;

    public function __construct(UsersRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }


    public function create_cours()
    {

        $souscategory =  $this->userRepository->create_cours();

        return view('Cours.create.cours')->with('souscategory' , $souscategory);
    }


    public function getGoalsBySousCategorie(String $id)
    {
        return  $this->userRepository->getGoalsBySousCategorie($id);
    }


    public function store_cours(Request $request)
    {
        return $this->userRepository->store_cours($request);

    }



}
