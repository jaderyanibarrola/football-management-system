@extends('layouts.dashboard')

@section('content')
<!-- Begin Page Content -->
<div class="container-fluid mb-4">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Lineups</h1>
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
        
        <!-- lineups listing -->
        <div class="col-xl-10 col-md-10">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <h3>All lineups</h3>
                    @if($lineups)
                    <table id="dt" class="table table-striped">
                        <thead>
                            <tr>
                                <th>Logo</th>
                                <th>Team name</th>
                                <th>Player name</th>
                                <th>Actions</th>
                                <th>Created at</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($lineups as $l)
                            <tr>
                                <td class="align-middle">
                                    @if($l->logo!==NULL)
                                    <img src="{{asset('../uploads/'.$l->logo)}}" class="thumb">
                                    @else
                                    <div class="text-secondary">[no logo]</div>
                                    @endif                                    
                                </td>
                                <td class="align-middle">{{$l->name}}</td>
                                <td class="align-middle">{{$l->first_name.' '.$l->last_name}}</td>
                                <td class="align-middle">
                                    <a href="#" class="float-left pr-3 edit-action" title="Edit" data-toggle="modal" data-target="#edit" data-id="{{$l->lid}}" data-name="{{$l->name}}" data-type="lineup"><i class="fa fa-pencil-alt"></i></a>
                                    <form action="{{ route('lineups_destroy', $l->lid) }}" method="POST" class="form-inline float-left" id="delete_{{$l->lid}}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-basic delete-action"><i class="fa fa-window-close text-danger"></i></button>
                                    </form>
                                </td>
                                <td class="align-middle">{{date('d F Y H:i', strtotime($l->created_at))}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @else
                    <div class="alert alert-info my-4">No lineups to display.</div>
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
                <p>Select Team name and its respective players</p>
                <form action="{{ route('lineup_store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="team_id" class="req">Team</label>
                        <select name="team_id" class="form-control">
                            @if($teams)
                            @foreach($teams as $t)
                            <option value="{{$t['id']}}">{{$t['name']}}</option>
                            @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="player_id" class="req">Players</label>
                        <select name="player_id" class="form-control">
                            @if($players)
                            @foreach($players as $p)
                            <option value="{{$p['id']}}">{{$p['first_name'].' '.$p['last_name']}}</option>
                            @endforeach
                            @endif
                        </select>
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
                <h5 class="modal-title">Substitute</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                @if($players)
                <form action="{{ route('lineup_store') }}" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="">
                    @csrf
                    <div class="form-group">
                        <label for="team_id">Team:</label>
                        <label id="teamName" class="font-weight-bold"></label>
                    </div>
                    <div class="form-group">
                        <label for="player_id" class="req">Substitute Player with:</label>
                        <select name="player_id" class="form-control">
                            @if($players)
                            @foreach($players as $p)
                            <option value="{{$p['id']}}">{{$p['first_name'].' '.$p['last_name']}}</option>
                            @endforeach
                            @endif
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-retweet"></i> Update</button>
                </form>
                @else
                <p>No available players</p>
                @endif
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
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.23/b-1.6.5/b-html5-1.6.5/b-print-1.6.5/r-2.2.7/datatables.min.css"/>
@endsection

@section('extra_js')
<!-- Custom scripts for all pages-->
<script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.23/b-1.6.5/b-html5-1.6.5/b-print-1.6.5/r-2.2.7/datatables.min.js"></script>

<!-- Page level plugins -->

<!-- Page level custom scripts -->
<!--<script src="{{ asset('theme1/js/demo/chart-area-demo.js') }}"></script>
<script src="{{ asset('theme1/js/demo/chart-pie-demo.js') }}"></script>-->
@endsection
