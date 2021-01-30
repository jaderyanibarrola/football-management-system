@extends('layouts.dashboard')

@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
        <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>
    </div>

    <!-- Content Row -->
    <div class="row">

        <!-- Earnings (Monthly) Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Teams</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{$teams_count}}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users-cog fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Earnings (Monthly) Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Players</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{$players_count}}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Earnings (Monthly) Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Lineups</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{$lineups_count}}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Requests Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Matches</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{$matches_count}}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-fire fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Row -->
    <div class="row">

        <!-- Content Column -->
        <div class="col-lg-6 mb-4">

            <!-- Project Card Example -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Team win stats</h6>
                </div>
                <div class="card-body">
                    @if($team_wins)
                    @foreach($team_wins as $w)
                    <h4 class="small font-weight-bold">{{$w->name}} <span class="float-right">{{$w->wins}}</span></h4>
                    <div class="progress mb-4">
                        <div class="progress-bar bg-{{$colors[$loop->index]}}" role="progressbar" style="width: {{$w->wins}}%" aria-valuenow="{{$w->wins}}" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    @endforeach
                    @else
                    <p>No team wins yet</p>
                    @endif
                </div>
            </div>

        </div>

        <!-- Content Column -->
        <div class="col-lg-6 mb-4">

            <!-- Project Card Example -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Upcoming matches</h6>
                </div>
                <div class="card-body">
                    @if($matches)
                    <div class="container">
                        @foreach($matches as $m)
                        <div class="row">
                            <div class="col-12 text-center">
                                <h4>{{$m->schedule_date}}</h4>
                            </div>
                        </div>
                        <div class="row justify-content-md-center">
                            <div class="col-3 text-center align-self-center">
                                @if($m->home_logo!==NULL)
                                <img src="{{asset('../uploads/'.$m->home_logo)}}" class="thumb">
                                @else
                                <div class="text-secondary">[no logo]</div>
                                @endif
                                <p>{{$m->home_team}}</p>
                            </div>
                            <div class="col-1 text-center align-self-center">
                                <span class="text-danger">VS</span>
                            </div>
                            <div class="col-3 text-center align-self-center">
                                @if($m->visitor_logo!==NULL)
                                <img src="{{asset('../uploads/'.$m->visitor_logo)}}" class="thumb">
                                @else
                                <div class="text-secondary">[no logo]</div>
                                @endif
                                <p>{{$m->visitor_team}}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <p>No upcoming matches yet</p>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>

@endsection

@section('extra_js')
<!-- Page level plugins -->
<script src="{{ asset('theme1/vendor/chart.js/Chart.min.js') }}"></script>

<!-- Page level custom scripts -->
<script src="{{ asset('theme1/js/demo/chart-area-demo.js') }}"></script>
<script src="{{ asset('theme1/js/demo/chart-pie-demo.js') }}"></script>
@endsection
