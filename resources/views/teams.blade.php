@extends('layouts.dashboard')

@section('content')
<!-- Begin Page Content -->
<div class="container-fluid mb-4">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Teams</h1>
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
            <div class="btn-group" role="group">
                <a href="#" data-toggle="modal" data-target="#add" class="btn btn-success mb-3"><i class="fa fa-plus"></i> Add new</a>
                <a href="#" data-toggle="modal" data-target="#tutorial" class="btn btn-primary mb-3"><i class="fa fa-info"></i> Tutorial</a>
                <a href="{{route('download')}}" class="btn btn-warning mb-3"><i class="fa fa-file-import"></i> CSV Template</a>
                <a href="#" data-toggle="modal" data-target="#import" class="btn btn-info mb-3"><i class="fa fa-file-import"></i> CSV Import</a>
            </div>
        </div>
    </div>

    <div class="row">

        <!-- teams listing -->
        <div class="col-xl-10 col-md-10">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <h3>All teams</h3>
                    @if($teams)
                    <table id="dt" class="table table-striped">
                        <thead>
                            <tr>
                                <th>Logo</th>
                                <th>Team name</th>
                                <th>Wins</th>
                                <th>Losses</th>
                                <th>Status</th>
                                <th>Actions</th>
                                <th>Created at</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($teams as $t)
                            <tr>
                                <td class="align-middle">
                                    @if($t['logo']!==NULL)
                                    <img src="{{asset('../uploads/'.$t['logo'])}}" class="thumb">
                                    @else
                                    <div class="text-secondary">[no logo]</div>
                                    @endif
                                </td>
                                <td class="align-middle">{{$t['name']}}</td>
                                <td class="align-middle">{{$t['wins']}}</td>
                                <td class="align-middle">{{$t['losses']}}</td>
                                <td class="align-middle">
                                    @if($t['active']==1)
                                    <i class="fa fa-check text-success" title="Active"></i>
                                    @else
                                    <i class="fa fa-window-close text-danger" title="Inactive"></i>
                                    @endif
                                </td>
                                <td class="align-middle">
                                    <a href="#" class="float-left pr-3 edit-action" title="Edit" data-toggle="modal" data-target="#edit" data-id="{{$t['id']}}" data-type="team"><i class="fa fa-pencil-alt"></i></a>
                                    <form action="{{ route('teams_destroy', $t['id']) }}" method="POST" class="form-inline float-left" id="delete_{{$t['id']}}">
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
                    <div class="alert alert-info my-4">No teams to display.</div>
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
                <form action="{{ route('team_store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="name" class="req">Name</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Team name" required>
                    </div>
                    <div class="form-group">
                        <label for="logo">Logo</label>
                        <input type="file" name="logo" class="form-control show-preview">
                    </div>
                    <div id="image-preview" class="form-group d-none"><img src="" alt="preview"></div>
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
                <form action="{{ route('team_store') }}" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="">
                    @csrf
                    <div class="form-group">
                        <label for="name" class="req">Name</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Team name">
                    </div>
                    <div class="form-group">
                        <label for="logo">Logo</label>
                        <input type="file" name="logo" class="form-control show-preview">
                    </div>
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

<div class="modal fade" id="import" tabindex="-1" role="dialog" aria-labelledby="delete" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Import</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('teams_import') }}" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="">
                    @csrf
                    <div class="form-group">
                        <label for="name" class="req">File</label>
                        <input type="file" class="form-control" id="file" name="file" required>
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-file-import"></i> Import</button>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="tutorial" tabindex="-1" role="dialog" aria-labelledby="delete" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tutorial</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <p>You can import teams using a CSV file. Here are fields that are accepted:</p>
                <ul>
                    <li>Team name (required)
                    </li>
                    <li>Wins (optional)
                    </li>
                    <li>Losses (optional)
                    </li>
                    <li>Active (optional)
                    </li>
                </ul>
                <p>Kindly download the <a href="{{route('download')}}" class="text-primary">template</a> for easier import. New lines are separated by a new line.</p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
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
