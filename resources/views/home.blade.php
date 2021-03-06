@extends('layouts.layout')

@section('breadcrumb')
    <nav>
        <div class="nav-wrapper indigo darken-2">
            <a id="rootCrumb" class="breadcrumb" href="{{ url('/home') }}">Index</a>
        </div>
    </nav>
@endsection

@section('content')
    <div class="container">
        <div class="row">

            <div class="card grey lighten-4 dash-card">
                <div id="dashTitle" class="card-title indigo-text">Dashboard
                    <span id="adminCredentials" class="indigo-text right">Admin: {{ Auth::user()->name}} logged in</span>
                </div>

                <div class="card-body">

                    <div class="row">
                        <div class="mini-con col s12 m6 l3">
                            <a href="{{ url('/employees') }}" class="dash-link">
                                <div class="waves-effect mini-card">
                                    <i class="material-icons large indigo-text">people</i>
                                    <p class="indigo-text">Employees</p>
                                </div>
                            </a>
                        </div>

                        <div class="mini-con col s12 m6 l3">
                            <a href="{{ url('/payout') }}" class="dash-link">
                                <div class="waves-effect mini-card">
                                    <i class="material-icons large indigo-text">payment</i>
                                    <p class="indigo-text">Payout</p>
                                </div>
                            </a>
                        </div>

                        <div class="mini-con col s12 m6 l3">
                            <a href="{{ url('/timesheet') }}" class="dash-link">
                                <div class="waves-effect mini-card">
                                    <i class="material-icons large indigo-text">timer</i>
                                    <p class="indigo-text">Timesheet</p>
                                </div>
                            </a>
                        </div>

                        <div class="mini-con col s12 m6 l3">
                            <a href="{{ url('/jobs') }}" class="dash-link">
                                <div class="waves-effect mini-card">
                                    <i class="material-icons large indigo-text">build</i>
                                    <p class="indigo-text">Jobs</p>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="row">
            
            <div class="card grey lighten-4">
                <div class="card-title indigo-text">
                    <i class="material-icons">pie_chart</i> Reports
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col s12">
                            <div id="jobDonutDiv">
                            </div>
                            <?= Lava::render('DonutChart', 'JobDonut', 'jobDonutDiv'); ?>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@section('indiv_js')
    <script type="text/javascript">
    </script>
@endsection
