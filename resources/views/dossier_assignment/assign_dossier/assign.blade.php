@extends('layouts.app')

@section('content')
    <section class="content">
        <div class="container-fluid" >
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Dossier Assignment</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form class='form-horizontal' role='form' method='post' action="{{route('assign_dossier')}}">
                    @csrf
                    <div class="card-body ">


                        <div class="form-group">
                            <label for="product">Application type: </label>
                            @if($dossier->application_type==1)
                                <td>Standard Track (130 days)</td>
                            @else
                                <td>Fast Track (60 days)</td>
                            @endif

                        </div>


                        <div class="form-group">
                            <label for="product">Product: </label>
                            {{$dossier->product_name}}

                        </div>
                        <input type='hidden' name='dossier_id' value='{{$dossier->id}}'/>
                        <div class="form-group" >
                            <label for="assessor">Assessor</label>
                            <select class="form-control" id='assessor' name='assessor' style="width: 100%;" aria-hidden="true" onchange="retrieve_assessor_assignments(this)" required>

                                <option value=''>Select Assessor</option>
                                @foreach($assessors as $assessor)
                                    <option  value='{{$assessor->id}}'>{{$assessor->first_name}} {{$assessor->middle_name}}</option>
                                @endforeach
                            </select>
                        </div>

                    </div>
                    <!-- /.card-body -->

                    <div class="card-footer">
                        <a href="{{route('dossier_tab')}}"  class="btn btn-default">Cancel</a>
                        <button href="" type="submit" class="btn btn-success" style="float:right">Assign</button>
                    </div>
        </form>
            </div></div>
    </section>


    <section class="content" id='assessor_datatable' hidden>
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Assigned Tasks: <label id='assessor_name'></label> </h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div id="example2_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer ">
                                <table id="example2" class="table table-bordered table-hover dataTable dtr-inline" role="grid" aria-describedby="example2_info">
                                    <thead>
                                    <tr role="row">
                                        {{--<th class="sorting sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Supplier Name: activate to sort column descending" aria-sort="ascending" width="5%">S.N</th>--}}
                                        <th class="sorting sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Supplier Name: activate to sort column descending" aria-sort="ascending" width="15%">Reference Number</th>
                                        <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Country: activate to sort column ascending" width="15%">Type of Task</th>
                                        <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Country: activate to sort column ascending" width="15%">Generic Name</th>
                                        <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Address: activate to sort column ascending" width="15%">End Date</th>
                                        <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Address: activate to sort column ascending" width="15%">Progress</th>
                                        <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Address: activate to sort column ascending" width="15%">Status</th>
                                        <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Actions: activate to sort column ascending" width="5%">Actions</th></tr>
                                    </thead>
                                    <tbody id='assignment_detail' >


                                    </tbody>

                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-body -->
                </div>
            </div>
        </div>
        </div>
    </section>
@endsection
@section('scripts')

    <script>

        function show_alert(o){
            alert(o.value);
        }
        // ajax to retrive already assigned dossiers
        function retrieve_assessor_assignments(o){
            id=o.value;
            if(id!=''){


                $.ajax({

                    type:'GET',

                    url:"{{ route('retrieve_assessor_assignments') }}",

                    data:{id:id},

                    success:function(data){
                        (data.assignment)
                        if(data.assignment!=''){
                            document.getElementById('assignment_detail').innerHTML=data.assignment
                            document.getElementById('assessor_name').innerHTML=data.assessor_name
                            document.getElementById('assessor_datatable').hidden=false;
                        }
                        else{
                            document.getElementById('assignment_detail').innerHTML=data.assignment
                            document.getElementById('assessor_datatable').hidden=false;
                            document.getElementById('assessor_name').innerHTML=data.assessor_name
                        }
                    },
                    error:function (data) {

                        console.log(data);
                    }
                });
            }
            else{
                document.getElementById('assessor_datatable').hidden=true;
            }
        }

    </script>
@endsection
