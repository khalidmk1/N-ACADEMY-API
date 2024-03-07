@extends('Layouts.master')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Manger Objectifs</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    @include('Layouts.errorshandler')



    <!-- Modal Create -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Crée Catégorie</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ Route('dashboard.goals.store') }}" method="post">
                    @csrf

                    <div class="modal-body">


                        {{--          <div class="form-group">
                            <label>SousCategorie</label>
                            <select name="souscatgory" class="form-control select2 select2-danger"
                                data-dropdown-css-class="select2-danger" style="width: 100%;">
                                @foreach ($goals['souscategory'] as $goal)
                                    <option value="{{ $goal->id }}">{{ $goal->souscategory_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <!-- /.form-group --> --}}


                        <div class="form-group">
                            <label>SousCategorie</label>
                            <select class="select2" multiple="multiple" name="souscatgory[]"
                                data-placeholder="Select a State" style="width: 100%;">
                                @foreach ($goals['souscategory'] as $goal)
                                    <option value="{{ $goal->id }}">{{ $goal->souscategory_name }}
                                    </option>
                                @endforeach

                            </select>
                        </div>
                        <!-- /.form-group -->

                        <div class="form-group">
                            <label for="categorie"> Nom de Objectif</label>
                            <input value="{{ old('goals') }}" type="text" class="form-control" id="categorie"
                                placeholder="Enter Nom de Categorie ..." name="goals">
                        </div>




                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-default">Crée</button>
                    </div>

                </form>

            </div>
        </div>
    </div>


    <!-- Main content -->
    <section class="content m-auto">
        <div class="container-fluid">
            <div class="row justify-content-center">

                <div class="col-12 mb-3 d-flex justify-content-end">
                    <button type="button" data-toggle="modal" data-target="#exampleModal"
                        class="btn btn-block btn-primary w-25">
                        Crée des Categories
                    </button>
                </div>

                <!-- /.col -->
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Catégories</h3>

                            <div class="card-tools">
                                <ul class="pagination pagination-sm float-right">
                                    <li class="page-item">{{ $goals['goals']->links() }}</li>

                                </ul>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body p-0">
                            <table class="table">

                                <thead>
                                    <tr class="text-center">
                                        <th style="width: 10px">#id</th>
                                        <th>SouCatégorie</th>
                                        <th>Objetifs</th>
                                        <th>Modifier</th>
                                        <th>Suprimmer</th>
                                    </tr>
                                </thead>

                                <tbody class="text-center">
                                    @foreach ($goals['goals'] as $goal)
                                        @include('filtering.update.goal')

                                        @include('filtering.delete.goal')
                                        <tr>
                                            <td>{{ $goal->id }}</td>
                                            <td>{{ $goal->souscategory->souscategory_name }}</td>
                                            <td>{{ $goal->goals }}</td>
                                            <td>

                                                <a type="button" data-toggle="modal"
                                                    data-target="#update_goal_model_{{ $goal->id }}"
                                                    class="btn btn-sm bg-warning">
                                                    <img src="{{ asset('asset/update_icon.png') }}" style="height: 18px;"
                                                        alt="update_icon">
                                                </a>
                                            </td>
                                            <td>
                                                <button type="button" data-toggle="modal"
                                                    data-target="#delete_goals_{{ $goal->id }}"
                                                    class="btn btn-sm btn-danger">
                                                    <i class="fa fa-trash" aria-hidden="true"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach


                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->

                </div>
                <!-- /.col -->

            </div>

    </section>


    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
    <script>
        $(function() {
            //Initialize Select2 Elements
            $('.select2').select2()

            //Initialize Select2 Elements
            $('.select2bs4').select2({
                theme: 'bootstrap4'
            })
        })
    </script>
@endsection
