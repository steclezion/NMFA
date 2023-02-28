@extends('layouts.app')
@section('content')

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">List of Submitted Variations</h3>
                            <div class="card-tools">

                                @can('application-list')
                                    <button class="btn btn-warning btn-sm"
                                            title="Submit New Variation"
                                            data-toggle="modal"
                                            data-target="#modalsend_variation">
                                        <i class="fas fa-plus"> <span
                                                    style="font-family: sans-serif; font-weight: normal ;"> New Variation </span></i>
                                    </button>

                                @endcan
                                @can('supervisor_roles')
                                    @if($application->application_type==2)
                                        <button class="btn btn-warning btn-sm"
                                                title="Submit New Variation"
                                                data-toggle="modal"
                                                data-target="#modalsend_variation">
                                            <i class="fas fa-plus"> <span
                                                        style="font-family: sans-serif; font-weight: normal ;"> New Variation </span></i>
                                        </button>

                                    @endif
                                @endcan


                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer ">
                                <table id="example1"
                                       class="table  dataTable no-footer dtr-inline"
                                       role="grid"
                                       aria-describedby="example1_info">

                                    <thead>
                                    <tr role="row">
                                        <th class="sorting sorting_asc" tabindex="0" aria-controls="example1"
                                            rowspan="1" colspan="1"
                                            aria-label="Supplier Name: activate to sort column descending"
                                            aria-sort="ascending" width="5%">S.N
                                        </th>
                                        <th class="sorting sorting_asc" tabindex="0" aria-controls="example1"
                                            rowspan="1" colspan="1"
                                            aria-label="Supplier Name: activate to sort column descending"
                                            aria-sort="ascending" width="17%">Registration Num.
                                        </th>
                                        <th class="sorting sorting_asc" tabindex="0" aria-controls="example1"
                                            rowspan="1" colspan="1"
                                            aria-label="Supplier Name: activate to sort column descending"
                                            aria-sort="ascending" width="15%">Variation Reference Number
                                        </th>
                                        <th class="sorting sorting_asc" tabindex="0" aria-controls="example1"
                                            rowspan="1" colspan="1"
                                            aria-label="Supplier Name: activate to sort column descending"
                                            aria-sort="ascending" width="16%">Date
                                        </th>
                                        <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1"
                                            colspan="1" aria-label="Country: activate to sort column ascending"
                                            width="20%">Status
                                        </th>
                                        <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1"
                                            colspan="1" aria-label="Country: activate to sort column ascending"
                                            width="10%">Decision
                                        </th>
                                        <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1"
                                            colspan="1" aria-label="Actions: activate to sort column ascending"
                                            width="20%">Actions
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php($i=1)
                                    @foreach($variations as $variation)
                                        @if($variation->status!='completed')
                                            <tr role="row" class="odd">
                                                <td>{{$i++}}</td>
                                                <td>{{$certification->registration_number}}</td>
                                                <td tabindex="0">{{$variation->variation_reference_number}}</td>
                                                <td tabindex="0">{{$variation->created_at}}  </td>
                                                <td tabindex="0">

                                                    @if(isset($variation->sealed_document_id))
                                                        @if($variation->status == 'Unassigned' or $variation->status == 'Assigned')
                                                            <span class="badge badge-primary"> In-progress</span>
                                                        @else
                                                            <span class="badge badge-primary"> {{$variation->status}}</span>
                                                        @endif
                                                    @else
                                                        <span class="badge badge-primary">In-progress</span>
                                                    @endif

                                                </td>

                                                @if(isset($variation->sealed_document_id))

                                                    @if($variation->decision_status=='Rejected')
                                                        <td tabindex="0">
                                                            <span class="badge bg-danger">Rejected</span>
                                                        </td>
                                                    @elseif($variation->decision_status=='Accepted')
                                                        <td tabindex="0">
                                                            <span class="badge bg-success">Accepted</span>
                                                        </td>
                                                    @endif
                                                @else
                                                    <td tabindex="0">
                                                        <span class="badge bg-secondary">Not-decided</span>
                                                    </td>

                                                @endif
                                                <td>
                                                    <div>

                                                        <a href="{{ route('variation_applicant_details',[$variation->id])  }}"
                                                           class="btn btn-info"><i class="fas fa-list"
                                                                                   title="Details"></i></a>

                                                    </div>
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                    </tbody>

                                </table>
                            </div>
                        </div>
                    </div>


                    {{--    Send Variation modal--}}

                    <div class="modal fade" id="modalsend_variation" data-backdrop="static" tabindex="-1" role="dialog"
                         aria-labelledby="modalsend_variation" aria-hidden="true">
                        <div class="modal-dialog modal-md" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title"> Upload New Variation</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">

                                    <form name="upload_response" method="POST"
                                          action="{{route('new_variation') }}"
                                          enctype="multipart/form-data">
                                        @csrf

                                        <div class="form-group">
                                            <label for="variation_subject">Subject</label>
                                            <input name="variation_subject" type="text" class="form-control">
                                        </div>

                                        <div class="form-group">
                                            <label for="query_response_cover_letter">Cover Letter</label>
                                            <div class="input-group">
                                                <div class="custom-file">
                                                    <input type="file" name="variation_cover_letter"
                                                           id="variation_cover_letter"
                                                           class="custom-file-input" title="Attach cover letter."
                                                           onchange="filevalidiator('cover_letter_id','variation_cover_letter','send_query_btn',['pdf'])"
                                                           required>
                                                    <label class="custom-file-label"
                                                           for="query_response_cover_letter">Choose
                                                        file (PDF)</label>
                                                </div>

                                            </div>
                                            <p class="text text-danger" id="cover_letter_id"></p>
                                        </div>

                                        <div class="form-group">
                                            <label for="variation_document">Variation Document</label>
                                            <div class="input-group">
                                                <div class="custom-file">
                                                    <input type="file" name="variation_document"
                                                           id="variation_document"
                                                           class="custom-file-input"
                                                           title="Attach Variation Documents as a ZIP."
                                                           onchange="filevalidiator('variation_document_id','variation_document','send_query_btn',['zip', 'rar'])"
                                                           required>
                                                    <label class="custom-file-label"
                                                           for="query_response_cover_letter">Choose
                                                        file (zip, rar)</label>
                                                </div>

                                            </div>
                                            <p class="text text-danger" id="variation_document_id"></p>
                                        </div>


                                        <input type="hidden" name="certificate_id" value="{{$certification->id}}"/>
                                        <div class="modal-footer justify-content-between">
                                            <button type="button" class="btn bg-white" data-dismiss="modal">Cancel
                                            </button>
                                            <button type="submit" id="send_query_btn" class="btn btn-success">Send
                                                Variation
                                            </button>
                                        </div>
                                    </form>
                                </div> {{--modal-body--}}
                            </div>
                        </div>
                    </div>

                {{--  End of Variation Modal--}}
                <!-- /.card-body -->
                </div>
            </div>
        </div>
        </div>
    </section>
@endsection
@section('scripts')
    <script src="{{ asset('plugins/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>
    <script>

        $(function () {
            bsCustomFileInput.init();
        });
    </script>

@endsection
