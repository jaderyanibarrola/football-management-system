@extends('layouts.dashboard')

@section('content')
<!-- Begin Page Content -->
<div class="container-fluid mb-4">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Matches</h1>
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
        
        <!-- matches listing -->
        <div class="col-xl-10 col-md-10">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <h3>All matches</h3>
                    @if($matches)
                    <table id="dt" class="table table-striped">
                        <thead>
                            <tr>
                                <th>Home team</th>
                                <th>Visitor team</th>
                                <th>Schedule date</th>
                                <th>Actions</th>
                                <th>Created at</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($matches as $m)
                            <tr>
                                <td class="align-middle">
                                    @if($m->home_logo!==NULL)
                                    <img src="{{asset('../uploads/'.$m->home_logo)}}" class="thumb">
                                    @else
                                    <div class="text-secondary">[no logo]</div>
                                    @endif
                                    <p>{{$m->home_team}}</p>
                                </td>
                                <td class="align-middle">
                                    @if($m->visitor_logo!==NULL)
                                    <img src="{{asset('../uploads/'.$m->visitor_logo)}}" class="thumb">
                                    @else
                                    <div class="text-secondary">[no logo]</div>
                                    @endif
                                    <p>{{$m->visitor_team}}</p>
                                </td>
                                <td class="align-middle">
                                    {{$m->schedule_date}}
                                </td>
                                <td class="align-middle">
                                    <form action="{{ route('matches_destroy', $m->id) }}" method="POST" class="form-inline float-left" id="delete_{{$m->id}}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-basic delete-action"><i class="fa fa-window-close text-danger"></i></button>
                                    </form>
                                </td>
                                <td class="align-middle">{{date('d F Y H:i', strtotime($m->created_at))}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @else
                    <div class="alert alert-info my-4">No matches to display.</div>
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
                <form action="{{ route('matches_store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="team_id" class="req">Home Team</label>
                        <select name="home" class="form-control">
                            @if($lineups)
                            @foreach($lineups as $l)
                            <option value="{{$l['tid']}}">{{$l['name']}}</option>
                            @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="player_id" class="req">Visitor Team</label>
                        <select name="visitor" class="form-control">
                            @if($lineups)
                            @foreach($lineups as $l)
                            <option value="{{$l['tid']}}">{{$l['name']}}</option>
                            @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="schedule_date" class="req">Schedule date</label>
                        <input type="datetime-local" name="schedule_date" class="form-control" min="{{date('Y-m-d')}}" required>
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
                <form action="{{ route('matches_store') }}" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="">
                    @csrf
                    <div class="form-group">
                        <label for="team_id" class="req">Home Team</label>
                        <select name="home" class="form-control">
                            @if($lineups)
                            @foreach($lineups as $l)
                            <option value="{{$l['id']}}">{{$l['name']}}</option>
                            @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="player_id" class="req">Visitor Team</label>
                        <select name="visitor" class="form-control">
                            @if($lineups)
                            @foreach($lineups as $l)
                            <option value="{{$l['id']}}">{{$l['name']}}</option>
                            @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="player_id" class="req">Schedule date</label>
                        <input type="datetime-local" name="schedule_date" class="form-control" min="{{date('Y-m-d')}}" required>
                    </div>
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
