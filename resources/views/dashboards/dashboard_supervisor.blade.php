<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box alert-info">
                    <div class="inner">
                        <h3>{{$unassigened_preliminary}}</h3>

                        <p>Unassigned Preliminary Screening</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-bag"></i>
                    </div>
                    <a href="{{ url('un-assigned') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box alert-secondary">
                    <div class="inner">
                        <h3>{{$unassigned_dossiers}}</h3>

                        <p>Unassigned Dossiers</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-stats-bars"></i>
                    </div>
                    <a href="{{route('dossier_tab')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box alert-danger">
                    <div class="inner">
                        <h3>{{$deadline}}</h3>

                        <p>Deadline Requests</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-person-add"></i>
                    </div>
                    <a href="{{route('deadline_index')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box alert-warning">
                    <div class="inner">
                        <h3>{{$ongoing_dossier_evalutions}}</h3>

                        <p>Ongoing Dossier Evaluations</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-pie-graph"></i>
                    </div>
                    <a href="{{route('supvervisor_ongoing_dossier_tasks')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
        </div>
        <div class="row">
            <div class="col-lg-3 col-6">
                <!-- small box -->
                <a href="{{route('variation_index')}}" class="btn  btn-success btn-md" style="width: 100%">
                    <i class="fa fa-plus-circle nav-icon"></i>
                    Manage Variations

                </a>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-6">
                <a href="{{ url('un_assigned_psur') }}" class="btn  btn-success btn-md" style="width: 100%">
                    <i class="fa fa-plus-circle nav-icon"></i>
                    Manage PSURs
                </a>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-6">
                <a href="{{route('users.index')}}" class="btn  btn-success btn-md" style="width: 100%">
                    <i class="fa fa-plus-circle nav-icon"></i>
                    Manage Users
                </a>
            </div>
            <!-- ./col -->
        </div>
        <!-- /.row -->
        <!-- Main row -->

    </div><!-- /.container-fluid -->
</section>  
<!-- /.content -->


