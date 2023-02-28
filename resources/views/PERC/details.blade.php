@extends('layouts.app')

<link rel="stylesheet" href="{{asset('plugins/select2/css/select2.min.css')}}">

<link rel="stylesheet" href="{{asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">


@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title"><strong> Meeting Decision Details</strong>
                        </h3>


                    </div>
                    <!-- /.card-header -->


                    <div class="card-body">

                        <div class="row">
                            <div class="col-12 col-sm-6">
                                <div class="form-group">
                                    <input type="hidden" name="meeting_id" value="{{$meeting->id}}" />
                                    <label> Meeting Date :</label>

                                    {{$meeting->meeting_date}}

                                </div>
                                <div class="form-group">
                                    <label> Venue:</label>
                                    {{$meeting->venue}}
                                </div>
                                <div class="form-group">
                                    <label> Time:</label>
                                    {{$meeting->time}}
                                </div>
                                <div class="form-group">
                                    <label> Description:</label>
                                    {{$meeting->description}}
                                </div>
                                <div class="form-group">
                                    <label> Invitation Document:</label>
                                    <a href="{{asset($meeting->invitation_document_path)}}" type="button" target="_blank"
                                        title="View the document" class="btn btn-info btn-sm"><i class="fas fa-eye "></i></a>
                                </div>
                            </div>




                            <div class="col-12 col-sm-6">
                                @if(isset($meeting->path))
                                <div class="form-group">
                                    <label for="query_response_cover_letter">Minutes</label>
                                    <div class="input-group">
                                        <a href="{{asset($meeting->path)}}" type="button" target="_blank" title="View the document"
                                            class="btn btn-info btn-sm"><i class="fas fa-eye "></i></a>

                                    </div>

                                    <span class="text text-danger" id="send_document_id"></span>
                                </div>

                                <div class="form-group">
                                    <label>Participants</label>
                                    <div class="select2-red">
                                        <select class="select2" name='participants[]' multiple="multiple"
                                            data-placeholder="Select a participants" data-dropdown-css-class="select2-red"
                                            style="width: 100%;" disabled>
                                            <option>All</option>
                                            @foreach($percs as $perc)
                                            <option selected value="{{$perc->id}}">{{$perc->first_name}}
                                                {{$perc->middle_name}} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            @endif
                            <!-- /.col -->

                        </div>
                        <div class="form-group">
                            <div class="modal-footer justify-content-between">
                                <a href="{{url()->previous()}}" class="btn btn-secondary"><i class="fas fa-arrow-circle-left"></i>
                                    Back </a>

                            </div>
                        </div>

                        @if($meeting->type=='Decision_Meeting')
                            <table class="table table-bordered table-responsive table-striped
                                    dataTable no-footer dtr-inline"
                                role="grid" aria-describedby="example1_info">

                                <thead>
                                    <tr role="row">
                                        <th class="sorting sorting_asc" tabindex="0" aria-controls="example1" rowspan="1"
                                            colspan="1" aria-label="Serial Number:
                                                activate to sort column
                                                descending"
                                            aria-sort="ascending" width="3%">S.N
                                        </th>

                                        <th class="sorting sorting_asc" tabindex="0" aria-controls="example1" rowspan="1"
                                            colspan="1" aria-label="Reference Number:
                                                activate to sort column
                                                descending"
                                            aria-sort="ascending" width="20%"> Application Number
                                        </th>

                                        <th class="sorting sorting_asc" tabindex="0" aria-controls="example1" rowspan="1"
                                            colspan="1" aria-label="Reference Number:
                                                activate to sort column
                                                descending"
                                            aria-sort="ascending" width="20%"> Application Type
                                        </th>
                                        <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1"
                                            aria-label="Title: activate to
                                                sort column ascending"
                                            width="15%" id="received">
                                            Product Name
                                        </th>
                                        <th class="sorting sorting_asc" tabindex="0" aria-controls="example1" rowspan="1"
                                            colspan="1" aria-label="Reference Number:
                                                activate to sort column
                                                descending"
                                            aria-sort="ascending" width="20%"> Applicant Name
                                        </th>
                                        <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1"
                                            aria-label="Title: activate to
                                                sort column ascending"
                                            width="20%" id="subject">
                                            Assessor Name
                                        </th>
                                        <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1"
                                            aria-label="Title: activate to
                                                sort column ascending"
                                            width="20%" id="subject">
                                            Decision
                                        </th>





                                        <th rowspan="1" colspan="1" width="20%">Assessment Report</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php($i=1)
                                    @foreach($decisions as $evaluation)
                                    <tr role="row" class="odd">
                                        <td>{{$i++}}</td>
                                        <td>{{$evaluation->application_number}}</td>
                                        <td>
                                            @if($evaluation->application_type==2)
                                            Fast Track

                                            @else
                                            Standard
                                            @endif
                                        </td>
                                        <td>{{$evaluation->product_trade_name}} </td>


                                        <td>{{$evaluation->trade_name}}</td>
                                        <td>{{$evaluation->first_name}}
                                            {{$evaluation->middle_name}}</td>

                                        <td> @if($evaluation->decision_status=='Accepted')
                                            <span class="badge badge-success">{{$evaluation->decision_status}}</span>
                                            @elseif($evaluation->decision_status=='Deferred')
                                            <span class="badge badge-warning">{{$evaluation->decision_status}}</span>
                                            @elseif($evaluation->decision_status=='Rejected')

                                            <span class="badge badge-danger">{{$evaluation->decision_status}}</span>
                                            @else
                                            <span class="badge badge-primary">Meeting Called</span>
                                            @endif</td>
                                        <td>
                                        <button type="button" class="btn btn-primary btn-sm"
                                            title="Assessment Reports" onclick="assesment_details(this,'Dossier Decision ')" value="{{$evaluation->dossier_assignment_id}}"
                                            data-toggle="modal"
                                            data-target="#modal_assessment_reports" >
                                        <i class="fas fa-book-open"></i>
                                    </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>

                            </table>
                        </div>
                    </div>
                    @elseif($meeting->type=='Other_Meeting')
                    <table class="table table-bordered table-responsive table-striped
                                    dataTable no-footer dtr-inline"
                                role="grid" aria-describedby="example1_info">

                                <thead>
                                    <tr role="row">
                                        <th class="sorting sorting_asc" tabindex="0" aria-controls="example1" rowspan="1"
                                            colspan="1" aria-label="Serial Number:
                                                activate to sort column
                                                descending"
                                            aria-sort="ascending" width="3%">S.N
                                        </th>

                                        <th class="sorting sorting_asc" tabindex="0" aria-controls="example1" rowspan="1"
                                            colspan="1" aria-label="Reference Number:
                                                activate to sort column
                                                descending"
                                            aria-sort="ascending" width="20%"> Registration Number
                                        </th>

                                        <th class="sorting sorting_asc" tabindex="0" aria-controls="example1" rowspan="1"
                                            colspan="1" aria-label="Reference Number:
                                                activate to sort column
                                                descending"
                                            aria-sort="ascending" width="20%"> Variation Reference Number
                                        </th>
                                        <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1"
                                            aria-label="Title: activate to
                                                sort column ascending"
                                            width="15%" id="received">
                                            Product Name
                                        </th>
                                        <th class="sorting sorting_asc" tabindex="0" aria-controls="example1" rowspan="1"
                                            colspan="1" aria-label="Reference Number:
                                                activate to sort column
                                                descending"
                                            aria-sort="ascending" width="20%"> Certificate
                                        </th>
                                        <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1"
                                            aria-label="Title: activate to
                                                sort column ascending"
                                            width="20%" id="subject">
                                            Assessor Name
                                        </th>
                                        <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1"
                                            aria-label="Title: activate to
                                                sort column ascending"
                                            width="20%" id="subject">
                                            Decision
                                        </th>





                                        <th rowspan="1" colspan="1" width="20%">Assessment Report</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php($i=1)
                                    @foreach($variation_decisions as $evaluation)
                                    <tr role="row" class="odd">
                                        <td>{{$i++}}</td>
                                        <td>{{$evaluation->registration_number}}</td>
                                        <td>{{$evaluation->variation_reference_number}}</td>
                                       
                                        <td>Product </td>


                                        <td>{{$evaluation->certificate_number}}</td>
                                        <td>{{$evaluation->first_name}}
                                            {{$evaluation->middle_name}}</td>

                                        <td> @if($evaluation->decision_status=='Accepted')
                                            <span class="badge badge-success">{{$evaluation->decision_status}}</span>
                                            @elseif($evaluation->decision_status=='Deferred')
                                            <span class="badge badge-warning">{{$evaluation->decision_status}}</span>
                                            @elseif($evaluation->decision_status=='Rejected')

                                            <span class="badge badge-danger">{{$evaluation->decision_status}}</span>
                                            @else
                                            <span class="badge badge-primary">Meeting Called</span>
                                            @endif</td>
                                        <td>
                                        <button type="button" class="btn btn-primary btn-sm"
                                            title="Assessment Reports" onclick="assesment_details(this,'variation')" value="{{$evaluation->var_id}}"
                                            data-toggle="modal"
                                            data-target="#modal_assessment_reports" >
                                        <i class="fas fa-book-open"></i>
                                    </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>

                            </table>
                        </div>
                    </div>
                    @endif


                </div>
            </div>
            <!-- /.card -->




            {{--    Send Assessment Reports modal--}}

<div class="modal fade" id="modal_assessment_reports" data-backdrop="static" tabindex="-1" role="dialog"
     aria-labelledby="modal_assessment_reports" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"> Assessment Report/s</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <table width="100%" id="assessment_report_table">
            
                        </table>






                    
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn bg-white" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn bg-success" data-dismiss="modal">Ok</button>
                    </div>
            </div> {{--modal-body--}}
        </div>
    </div>
</div>

{{--  End of Assessment Reports Modal--}}
        </div>
    </div>
    </div>
    </div>
    @endsection
    @section('scripts')

    <script src="{{asset('plugins/select2/js/select2.full.min.js')}}"></script>
    <script>

        $(function () {
            bsCustomFileInput.init();
        });

        function test(o) {
            console.log(o);
        }
        $(function () {
            //Initialize Select2 Elements
            $('.select2').select2()

            //Initialize Select2 Elements
            $('.select2bs4').select2({
                theme: 'bootstrap4'
            })



        });




        function assesment_details(o,type) {
        let id = o.value;
        var server_ip=document.getElementById('server_ip').value;
       


        $.ajax({

            type: 'GET',

            url: "{{ route('assesment_details') }}",

            data: {id: id , type:type},

            success: function (data) {

                //for sending part
                console.log(data);

                var assessment_report=data.data;
                console.log(assessment_report);
                var raw = ' ';

                for(var i=0;i<assessment_report.length;i++)
                {
                    raw = raw + '<tr><td><label >'+ assessment_report[i].name +'</labe></td><td><div class="input-group"><div class="custom-file"><a  href="'+ server_ip + assessment_report[i].path +'" target="_blank" data-toggle="tooltip" class="btn btn-info btn-sm" data-placement="top" title="View the file"><i class="fas fa-download"></i> Download </a></div> </div></td></tr>';

                }

                document.getElementById('assessment_report_table').innerHTML=raw;
          
            },
            error: function (data) {
                console.log(data)

            }
        });

    }
    </script>
    @endsection