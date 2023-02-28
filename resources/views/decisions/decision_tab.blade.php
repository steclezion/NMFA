@extends('layouts.app')

@section('content')

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">

                <div class="card card-primary">
                    <div class="card-header">
                        Product Decision
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="col-12 col-sm-12">
                            <div class="card card-primary card-outline card-tabs">
                                <div class="card-header p-0 pt-1 border-bottom-0">
                                    {{--START TAB NAME LIST--}}
                                    <ul class="nav nav-tabs" id="custom-tabs-three-tab" role="tablist">
                                    <li class="nav-item">
                                            <a class="nav-link active" id="custom-tabs-three-accepted-tab" data-toggle="pill"
                                                href="#custom-tabs-three-accepted" role="tab"
                                                aria-controls="custom-tabs-three-accepted" aria-selected="false">Accepted
                                                </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link " id="custom-tabs-three-rejected-tab" data-toggle="pill" href="#custom-tabs-three-rejected"
                                                role="tab" aria-controls="custom-tabs-three-rejected" 
                                                aria-selected="true">Rejected</a>
                                        </li>

                                        <li class="nav-item">
                                            <a class="nav-link " id="custom-tabs-three-deferred-tab" data-toggle="pill"
                                                href="#custom-tabs-three-deferred" role="tab"
                                                aria-controls="custom-tabs-three-deferred" aria-selected="false">Deferred</a>
                                        </li>
                                       
                                       

                                    </ul>
                                    {{--END TAB NAME LIST--}}
                                </div>
                                <div class="card-body">

                                    <div class="tab-content" id="custom-tabs-three-tabContent">

                                        {{--------------------START OF All Inprogress Dossier ------------------}}
                                        @include('decisions.rejected_index')
                                        {{--------------------END All Inprogress Dossier ------------------}}


                                        {{---------------- START OF All Unassigned Dossiers------------}}
                                        @include('decisions.deferred_index')
                                        {{---------------- END OF All Unassigned Dossiers------------}}

                                        {{----------- START OF All assigned Dossiers----------------------}}
                                        @include('decisions.accepted_index')
                                        {{--------------------END OF All assigned Dossiers ------------------}}

                                        



                                    </div>
                                </div>
                            </div>
                            <!-- /.card -->
                        </div>
                    </div>
                </div>
                <!-- /.card-body -->
            </div>
        </div>

    </div>


    {{-- MODAL TO POP-UP CONFIRM DIALOG--}}

    <div class="modal fade" id="deleteRecordModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="deleteRecordModal"
        aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <?php
                if (isset($assess_rep_doc)) {
                    $assess_report_doc_id = $assess_rep_doc->id;
                } else {
                    $assess_report_doc_id = "";
                }

                ?>

        </div>
    </div>


</section>


@endsection

@section('scripts')
    <script>
      
      function decision_que(o,command){
            const id=o.value;
            const command_type=command;


                $.ajax({

                    type:'GET',

                    url:"{{ route('decision_que') }}",

                    data:{id:id,command_type:command_type},

                    success:function(data){
                        if(data.queued=='added') {
                            toastr.success('Product Added to Registration Queue')
                            document.getElementById('add_to_que_btn_'+data.id).hidden = true;
                            document.getElementById('remove_from_que_btn_'+data.id).hidden = false;
                        }
                        else if(data.queued=='removed'){
                            toastr.warning('Product Removed From Registration Queue')

                            document.getElementById('add_to_que_btn_'+data.id).hidden = false;
                            document.getElementById('remove_from_que_btn_'+data.id).hidden = true;
                        }
                        else{
                            alert('you have error in supervisor controller' )
                            console.log(data)
                        }

                    },
                    error:function (data) {

                        console.log(data);
                    }
                });

        }
    </script>

@endsection