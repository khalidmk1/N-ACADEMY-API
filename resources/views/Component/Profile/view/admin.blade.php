{{-- <style>
    .img-thumbnail {
        padding: .25rem;
        background-color: #fff;
        border: 1px solid #dee2e6;
        border-radius: .25rem;
        max-width: 100%;
        height: auto;
    }

    .social-link {
        width: 30px;
        height: 30px;
        border: 1px solid #ddd;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #666;
        border-radius: 50%;
        transition: all 0.3s;
        font-size: 0.9rem;
    }
</style>

<!-- Modal -->
<div class="modal fade" id="exampleModalLong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Modal title</h5>
                <div class="input-group input-group-sm m-auto" style="width: 150px;">
                    <input type="text" name="table_search" class="form-control float-right" placeholder="Search">

                    <div class="input-group-append">
                        <button type="submit" class="btn btn-default">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container py-5">

                    <div class="row text-center admins_containe">
                        <div class="modal-body">
                            <div class="row text-center">
                                @foreach ($admins as $admin)
                                    <div class="col-xl-6 col-sm-6 mb-3">
                                        <div class="bg-white rounded shadow-sm py-5 px-4">
                                            <a href="{{ Route('dashboard.admin.edit', Crypt::encrypt($admin->id)) }}"
                                                style=" position: absolute; top: 0; right: 20px;">
                                                <img src="{{ asset('asset/update_icon.png') }}" style="height: 20px;"
                                                    alt="update_icon">
                                            </a>
                                            <img src="https://bootdey.com/img/Content/avatar/avatar7.png" alt=""
                                                width="100"
                                                class="img-fluid rounded-circle mb-3 img-thumbnail shadow-sm">
                                            <h5 class="mb-0">{{ $admin->firstName . ' ' . $admin->lastName }}</h5>
                                            <span class="small text-uppercase text-muted">
                                                {{ $admin->email }}
                                            </span>

                                        </div>

                                    </div>
                                @endforeach
                            </div>

                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <ul class="pagination pagination-sm m-0 ">
                        <li class="page-item">{{ $admins->links() }}</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- jQuery -->
<script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script> --}}


@extends('Layouts.master')



@section('content')


    
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Contacts</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Contacts</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    @include('Layouts.errorshandler')

    <!-- Main content -->
    <section class="content">

        <!-- Default box -->
        <div class="card card-solid">

            <div class="card-body pb-0">
                <div class="row">
                    @foreach ($admins as $admin)
                    @include('Component.Profile.delete.admin')
                        <div class="col-12 col-sm-6 col-md-4 d-flex align-items-stretch flex-column">
                            <div class="card bg-light d-flex flex-fill">
                                <div class="card-header text-muted border-bottom-0">
                                    Digital Strategist
                                </div>
                                <div class="card-body pt-0">
                                    <div class="row">
                                        <div class="col-7">
                                            <h2 class="lead">
                                                <b>{{ $admin->user->firstName . ' ' . $admin->user->lastName }}</b></h2>
                                            <p class="text-muted text-sm"><b>About: </b> Web Designer / UX / Graphic Artist
                                                / Coffee Lover </p>
                                            <ul class="ml-4 mb-0 fa-ul text-muted">
                                                <li class="small"><span class="fa-li"><i
                                                            class="fas fa-lg fa-building"></i></span> Address: Demo Street
                                                    123, Demo City 04312, NJ</li>
                                                <li class="small"><span class="fa-li"><i
                                                            class="fas fa-lg fa-phone"></i></span> Phone #: + 800 - 12 12 23
                                                    52</li>
                                            </ul>
                                        </div>
                                        <div class="col-5 text-center">
                                            <img src="../../dist/img/user1-128x128.jpg" alt="user-avatar"
                                                class="img-circle img-fluid">
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <div class="text-right">
                                        <a href="{{ Route('dashboard.profile.edit', Crypt::encrypt($admin->user->id)) }}"
                                            class="btn btn-sm bg-warning">
                                            <img src="{{ asset('asset/update_icon.png') }}" style="height: 18px;"
                                                alt="update_icon">
                                        </a>
                                        <button type="button" data-toggle="modal" data-target="#delete_admin_{{$admin->id}}" class="btn btn-sm btn-danger">
                                            <i class="fa fa-trash" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                </div>
            </div>


            <!-- /.card-body -->
            <div class="card-footer">
                <nav aria-label="Contacts Page Navigation">
                    <ul class="pagination justify-content-center m-0">
                        <li class="page-item ">{{ $admins->links() }}</li>

                    </ul>
                </nav>
            </div>
            <!-- /.card-footer -->
        </div>
    </section>
@endsection
