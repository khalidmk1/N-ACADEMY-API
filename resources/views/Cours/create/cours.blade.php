@extends('Layouts.master')

@section('content')
    <style>
        .select2-container--default .select2-selection--single {
            height: 40px !important;
        }

        .bootstrap-tagsinput {
            border: #00000029 solid 2px;
            padding: 4px;
            border-radius: 3px;

        }

        .bootstrap-tagsinput:first-child {
            border: none,

        }

        .bootstrap-tagsinput .tag {
            background: rgb(163, 159, 154);
            padding: 4px;
            font-size: 14px;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__rendered li {
            color: black
        }
    </style>

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Mange Contenu Page</h1>
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

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="card card-default col-12">
                    <div class="card-header row">
                        <div class="col-6">
                            <h3 class="card-title">Crée Cotenu</h3>
                        </div>
                        <div class="col-6 d-flex justify-content-end">
                            <a href="{{ Route('dashboard.view.admin') }}" class="btn btn-block btn-primary w-25">
                                List Contenu
                            </a>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <form id="create_cours" action="{{ Route('dashboard.cours.store') }}" method="post">
                        @csrf
                        <div class="card-body">
                            <div class="form-group">
                                <label for="title">Titre</label>
                                <input type="text" value="{{ old('title') }}" class="form-control" name="title"
                                    id="title" placeholder="Entrez Titre ...">
                            </div>

                            <div class="row">
                                <div class="form-group clearfix col-6">
                                    <div class="icheck-primary d-inline">
                                        <input type="checkbox" name="iscoming" id="iscoming">
                                        <label for="iscoming">
                                            Coming Soon
                                        </label>
                                    </div>

                                </div>

                                <div class="col-6">
                                    <!-- Bootstrap Switch -->
                                    <label for="boostrap-switch" class="mr-5">
                                        Affichage
                                    </label>
                                    <input type="checkbox" name="isActive" id="boostrap-switch" checked data-value=""
                                        data-bootstrap-switch data-off-color="danger" data-on-color="success">
                                </div>
                                <!-- /.card -->
                            </div>


                            <!-- text input -->
                            <div class="form-group">
                                <label>Description de Contenu</label>
                                <input type="text" class="form-control" name="description" placeholder="Enter ...">
                            </div>


                            <div class="form-group">
                                <label for="tags">Mots Clé</label>
                                <input type="text" class="form-control" name="tags[]" id="tags-input" />
                            </div>

                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label>SousCategorie</label>
                                        <select class="form-control select2" id="souscategory_goals" name="cotegoryId" style="width: 100%;">
                                            <option>Choise Votre SousCategorie</option>
                                            @foreach ($souscategory as $souscategory)
                
                                                <option value="{{$souscategory->category->id}}">{{ $souscategory->category->category_name }}</option>
                                            @endforeach

                                        </select>
                                    </div>
                                    <!-- /.form-group -->
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="goals_option">Objectifs</label>
                                        <select class="select2" name="gaols_id[]"  multiple="multiple" id="goals_option"
                                            data-placeholder="Select a State" style="width: 100%;">

                                        </select>
                                    </div>
                                    <!-- /.form-group -->

                                </div>


                            </div>

                            <!-- select -->
                            <div class="form-group">
                                <label>Type De Cour</label>
                                <select class="form-control"  name="coursType" id="cours_type">
                                    <option>Choisez Votre Type de Cour</option>
                                    <option value="conference">Conférance Contenu</option>
                                    <option value="podcast">Podcast Contenu</option>
                                    <option value="formation">Formation Contenu</option>
                                </select>
                            </div>


                            <section id="conference">
                                <div class="form-group">
                                    <label>Conférencier</label>
                                    <select class="select2" multiple="multiple" data-placeholder="Select a State"
                                        style="width: 100%;">
                                        <option>Alabama</option>
                                        <option>Alaska</option>
                                        <option>California</option>
                                        <option>Delaware</option>
                                        <option>Tennessee</option>
                                        <option>Texas</option>
                                        <option>Washington</option>
                                    </select>
                                </div>
                                <!-- /.form-group -->
                            </section>

                            <section id="podcast">
                                fffffffffffffffff
                            </section>

                            <section id="formation">
                                vvvvvvvvvvvvvvvvvv
                            </section>

                        </div>
                        <button type="submit" class="btn btn-block btn-dark w-50 mb-3 ml-3">Crée Contnu</button>
                    </form>









                </div>





            </div>
        </div>
        </div>
    </section>

    <!-- jQuery -->
    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
    <script>
        $(function() {
            $("input[data-bootstrap-switch]").each(function() {
                $(this).bootstrapSwitch('state', $(this).prop('checked'));
            })

            //Initialize Select2 Elements
            $('.select2').select2()

            //Initialize Select2 Elements
            $('.select2bs4').select2({
                theme: 'bootstrap4'
            })

        })

        $(document).ready(function() {

            //search live for goals

            $('#souscategory_goals').on('change', function() {
                var sousCategorieId = $(this).val();
                
                $.ajax({
                    url: '/backoffice/goals-bySouscategory/' +
                    sousCategorieId,
                    method: 'GET',
                    success: function(response) {
                        var goalsSelect = $('#goals_option');
                        goalsSelect.empty(); 

                        console.log(response);

                        $.each(response.goals, function(index, goal) {
                            goalsSelect.append($('<option>', {
                                value: goal.id,
                                text: goal.goals 
                            }));

                            console.log(goal.id);
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching goals:', error);
                    }
                });
            });






            //create cours 

            var switchVal = 1;
            var iscoming = 0;

            $('#boostrap-switch').on('switchChange.bootstrapSwitch', function(event, state) {
                if (state) {
                    switchVal = 1;
                } else {
                    switchVal = 0;
                }

            });

            $('#iscoming').on('change', function(event) {
                var isChecked = $(this).prop('checked');

                var iscoming = isChecked ? 1 : 0;

            });


            $('#create_cours').submit(function(e) {
                e.preventDefault();
                var formData = new FormData(this);
                var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                console.log(switchVal);
                console.log(iscoming);

                $.ajax({
                    url: $(this).attr('action'),
                    method: $(this).attr('method'),
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': CSRF_TOKEN
                    },
                    success: function(response) {
                        console.log(response);

                    },
                    error: function(request, error) {
                        console.log(arguments);
                        console.log(error);
                        /*   alert(" Can't do because: " + error); */
                    },
                })


            })



            //tags
            var tagInputEle = $('#tags-input');
            tagInputEle.tagsinput();


            //cours type section 
            var conference = $('#conference')
            var podcast = $('#podcast')
            var formation = $('#formation')

            conference.hide();
            podcast.hide();
            formation.hide();

            $('#cours_type').on('change', function() {


                console.log($(this).val());

                type_cours = $(this).val()

                if (type_cours == 'conference') {
                    conference.slideDown()
                    podcast.slideUp()
                    formation.slideUp()
                } else if (type_cours == 'podcast') {
                    conference.slideUp()
                    podcast.slideDown()
                    formation.slideUp()
                } else if (type_cours == 'formation') {
                    conference.slideUp()
                    podcast.slideUp()
                    formation.slideDown()
                }

            })


        });
    </script>
@endsection
