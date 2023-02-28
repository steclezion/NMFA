@extends('layouts.app')
@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card card-primary ">
                        <div class="card-header">
                            <h3 class="card-title"><strong> Market Authorization Renewal Requests</strong>
                            </h3>

                        </div>
                        <!-- /.card-header -->
                        <div class="card-body" >
                            <div id="example1_wrapper"
                                 class="dataTables_wrapper dt-bootstrap4 no-footer ">
                                <table id="example1"
                                       class="table table-bordered table-striped dataTable no-footer dtr-inline"
                                       role="grid" aria-describedby="example1_info">

                                    <thead>
                                    <tr role="row">
                                        <th class="sorting sorting_asc" tabindex="0"
                                            aria-controls="example1" rowspan="1" colspan="1"
                                            aria-label="Serial Number: activate to sort column descending"
                                            aria-sort="ascending" width="3%">S.N
                                        </th>
                                        <th class="sorting" tabindex="0"
                                            aria-controls="example1" rowspan="1" colspan="1"
                                            aria-label="Title: activate to sort column ascending"
                                            width="15%" id="received"> Applicant Name
                                        </th>
                                        <th class="sorting" tabindex="0"
                                            aria-controls="example1" rowspan="1" colspan="1"
                                            aria-label="Title: activate to sort column ascending"
                                            width="15%" id="received"> Product Name
                                        </th>
                                        <th class="sorting sorting_asc" tabindex="0"
                                            aria-controls="example1" rowspan="1" colspan="1"
                                            aria-label="Reference Number: activate to sort column descending"
                                            aria-sort="ascending" width="21%"> Registration Number
                                        </th>
                                        <th class="sorting" tabindex="0"
                                            aria-controls="example1" rowspan="1" colspan="1"
                                            aria-label="Title: activate to sort column ascending"
                                            width="20%" id="subject"> Certificate Number
                                        </th>
                                        <th rowspan="1" colspan="1" width="20%">Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php($i=1)
                                    @foreach($reregister_requested_certifications as $certification)
                                        <tr role="row" class="odd">
                                            <td>{{$i++}}</td>
                                            <td>{{$certification->company_name}}</td>
                                            <td>{{$certification->product_trade_name}} </td>
                                            <td>{{$certification->registration_number}}</td>
                                            <td>{{$certification->certificate_number}}</td>

                                            <td>
                                                <a class="btn btn-info btn-sm"
                                                        title="Show details"
                                                        href=" {{ route('reregister_request_index', $certification->id)}}">
                                                    <i class="fas fa-list"></i>
                                                 </a>

                                                @if ($certification->reregister_extended_deadline==null)

                                                    <button type="button" class="btn btn-primary btn-sm"
                                                            title="Extend Deadline of Registration Renewal"
                                                            data-toggle="modal"
                                                            data-target="#modal_extend_renewal_deadline"
                                                            onclick="modal_extend_renewal(this)"
                                                            value="{{$certification->id}}">
                                                        <i class="fas fa-clock"></i>
                                                    </button>
                                                @else
                                                    <button type="button" class="btn btn-secondary btn-sm"
                                                            title="Deadline already extended." disabled>
                                                        <i class="fas fa-clock"></i>
                                                    </button>
                                                @endif

                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>

                                </table>
                            </div> {{-- end div: example1_wrapper--}}



                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->

                </div>
            </div>
        </div>

        {{--  Modal for Extend Dossier Assesment deadline  --}}
        <div class="modal fade" id="modal_extend_renewal_deadline" data-backdrop="static" tabindex="-1" role="dialog"
             aria-labelledby="modal_extend_renewal_deadline" aria-hidden="true">
            <div class="modal-dialog modal-md" role="document">

                <form action="{{ route('update_renewal_deadline') }}" method="POST">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Extend Deadline</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <input type="text" name='hidden_certification_id' hidden/>
                            </div>
                            <div class="form-group">
                                <label> Reason for Extension</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="extend_reason">

                                </div>
                            </div>
                            <div class="form-group">
                                <label>New Deadline</label>
                                <div class="input-group date" id="new_deadline_" data-target-input="nearest">
                                    <input type="date" class="form-control" name="new_deadline" onchange="check_max_allowed(this)">

                                </div>
                            </div>

                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-success">Extend</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        {{--  end of Modal for extend deadline  --}}

    </section>

    <script>

        function modal_extend_renewal(o) {

            document.getElementById('hidden_certification_id').value = o.value;

        }

        function check_max_allowed(o) {

                let now = new Date();

                year = now.getFullYear();
                date = now.getDate();
                month = now.getMonth();

                date =

                document.getElementById("demo").innerHTML =

        }

    </script>

@endsection
