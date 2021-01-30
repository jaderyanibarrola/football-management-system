@extends('layouts.dashboard')

@section('content')
<!-- Begin Page Content -->
<div class="container-fluid mb-4">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Players</h1>
    </div>

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    @if(session()->has('status') && session()->get('status')==true)
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>{{ session()->get('response') }}</strong>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @elseif(session()->has('status') && session()->get('status')==false)
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>{{ session()->get('response') }}</strong>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    <!-- Content Row -->
    <div class="row">
        <div class="col-xl-6 col-md-6 mb-2">
            <a href="#" data-toggle="modal" data-target="#add" class="btn btn-success mb-3"><i class="fa fa-plus"></i> Add new</a>
        </div>
    </div>

    <div class="row">

        <!-- players listing -->
        <div class="col-xl-10 col-md-10">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <h3>All players</h3>
                    @if($players)
                    <table id="dt" class="table table-striped">
                        <thead>
                            <tr>
                                <th>Photo</th>
                                <th>First name</th>
                                <th>Last name</th>
                                <th>Age</th>
                                <th>Weight</th>
                                <th>Height</th>
                                <th>Status</th>
                                <th>Actions</th>
                                <th>Created at</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($players as $t)
                            <tr>
                                <td class="align-middle"><img src="{{asset('../uploads/'.$t['photo'])}}" class="thumb"></td>
                                <td class="align-middle">{{$t['first_name']}}</td>
                                <td class="align-middle">{{$t['last_name']}}</td>
                                <td class="align-middle">{{$t['age']}}</td>
                                <td class="align-middle">{{$t['weight']}}</td>
                                <td class="align-middle">{{$t['height']}}</td>
                                <td class="align-middle">
                                    @if($t['active']==1)
                                    <i class="fa fa-check text-success" title="Active"></i>
                                    @else
                                    <i class="fa fa-window-close text-danger" title="Inactive"></i>                                    
                                    @endif
                                </td>
                                <td class="align-middle">
                                    <a href="#" class="float-left pr-3 edit-action" title="Edit" data-toggle="modal" data-target="#edit" data-id="{{$t['id']}}" data-type="player"><i class="fa fa-pencil-alt"></i></a>
                                    <form action="{{ route('players_destroy', $t['id']) }}" method="POST" class="form-inline float-left" id="delete_{{$t['id']}}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-basic delete-action"><i class="fa fa-window-close text-danger"></i></button>
                                    </form>
                                </td>
                                <td class="align-middle">{{date('d F Y H:i', strtotime($t['created_at']))}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @else
                    <div class="alert alert-info my-4">No players to display.</div>
                    @endif
                </div>
            </div>
        </div>

    </div>
</div>

<div id="add" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('player_store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="photo" class="req">Photo</label>
                        <input type="file" name="photo" class="form-control show-preview" required>
                    </div>
                    <div class="form-group">
                        <label for="first_name" class="req">First Name</label>
                        <input type="text" class="form-control" id="first_name" name="first_name" placeholder="First name" required>
                    </div>
                    <div class="form-group">
                        <label for="last_name" class="req">Last Name</label>
                        <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Last name" required>
                    </div>
                    <div class="form-row my-3">
                        <div class="col-4">
                            <label for="age" class="req">Age</label>
                            <input type="text" class="form-control" id="age" name="age" placeholder="age" required>
                            <small></small>
                        </div>
                        <div class="col-4">
                            <label for="weight">Weight</label>
                            <input type="text" class="form-control" id="weight" name="weight" placeholder="Weight">
                            <small>in KG</small>
                        </div>
                        <div class="col-4">
                            <label for="height">Height</label>
                            <input type="text" class="form-control" id="height" name="height" placeholder="Height">
                            <small>in cm</small>
                        </div>
                    </div>
                    <div class="form-group">
                        <div id="image-preview" class="form-group d-none"><img src="" alt="preview"></div>
                    </div>
                    <div class="form-group mt-3">
                        <div class="form-control">
                            <label for="status">Active</label>
                            <div class="float-right">
                                <input type="radio" name="active" value="1" checked> Yes
                                <input type="radio" name="active" value="0"> No
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-plus"></i> Add</button>
                </form>

            </div>
            <div class="modal-footer">
                <a id="modalActionBtn" class="btn btn-secondary d-none"></a>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div id="edit" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('player_store') }}" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="">
                    @csrf
                    <div class="form-group">
                        <label for="photo" class="req">Photo</label>
                        <input type="file" name="photo" class="form-control show-preview">
                    </div>
                    <div class="form-group">
                        <label for="first_name" class="req">First Name</label>
                        <input type="text" class="form-control" id="first_name" name="first_name" placeholder="First name" required>
                    </div>
                    <div class="form-group">
                        <label for="last_name" class="req">Last Name</label>
                        <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Last name" required>
                    </div>
                    <div class="form-row my-3">
                        <div class="col-4">
                            <label for="age" class="req">Age</label>
                            <input type="text" class="form-control" id="age" name="age" placeholder="Age" required>
                        </div>
                        <div class="col-4">
                            <label for="weight" class="req">Weight</label>
                            <input type="text" class="form-control" id="weight" name="weight" placeholder="Weight" required>
                            <small>in KG</small>
                        </div>
                        <div class="col-4">
                            <label for="height" class="req">Height</label>
                            <input type="text" class="form-control" id="height" name="height" placeholder="Height" required>
                            <small>in cm</small>
                        </div>
                    </div>
                    <div class="form-group mt-3">
                        <div class="form-control">
                            <label for="status">Active</label>
                            <div class="float-right">
                                <input type="radio" name="active" value="1"> Yes
                                <input type="radio" name="active" value="0"> No
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div id="image-preview" class="form-group d-none"><img src="" alt="preview"></div>
                    </div>
                    <!--
                    <div class="form-row">
                        <div class="col-6">
                            <label for="wins">Wins</label>
                            <input type="number" class="form-control" id="wins" name="wins" placeholder="Enter number">
                        </div>
                        <div class="col-6">
                            <label for="losses">Losses</label>
                            <input type="number" class="form-control" id="losses" name="losses" placeholder="Enter number">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="status">Status</label>
                        <textarea class="form-control" id="status" name="status" rows="3"></textarea>
                    </div>-->
                    <button type="submit" class="btn btn-primary"><i class="fa fa-retweet"></i> Update</button>
                </form>

            </div>
            <div class="modal-footer">
                <a id="modalActionBtn" class="btn btn-secondary d-none"></a>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="delete" tabindex="-1" role="dialog" aria-labelledby="delete" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Are you sure to delete?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">Select "Delete" below to confirm</div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                <button class="btn btn-danger" type="button" data-dismiss="modal" id="deleteBtn">Delete</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('extra_css')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.23/b-1.6.5/b-html5-1.6.5/b-print-1.6.5/r-2.2.7/datatables.min.css" />
@endsection

@section('extra_js')
<!-- Custom scripts for all pages-->
<script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.23/b-1.6.5/b-html5-1.6.5/b-print-1.6.5/r-2.2.7/datatables.min.js"></script>

<!-- Page level plugins -->

<!-- Page level custom scripts -->
<!--<script src="{{ asset('theme1/js/demo/chart-area-demo.js') }}"></script>
<script src="{{ asset('theme1/js/demo/chart-pie-demo.js') }}"></script>-->
@endsection
