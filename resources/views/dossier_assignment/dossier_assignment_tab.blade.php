@extends('layouts.app')

@section('content')

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">

                <div class="card card-primary">
                    <div class="card-header">
                        Dossier Assignment
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="col-12 col-sm-12">
                            <div class="card card-primary card-outline card-tabs">
                                <div class="card-header p-0 pt-1 border-bottom-0">
                                    {{--START TAB NAME LIST--}}
                                    <ul class="nav nav-tabs" id="custom-tabs-three-tab" role="tablist">


                                        <li class="nav-item">
                                            <a class="nav-link " id="custom-tabs-three-unassigned-tab" data-toggle="pill"
                                                href="#custom-tabs-three-unassigned" role="tab" onclick="change_tab_num('custom-tabs-three-unassigned')"
                                                aria-controls="custom-tabs-three-unassigned" aria-selected="false">Unassigned                                                Dossiers</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="custom-tabs-three-assigned-tab" data-toggle="pill"
                                                href="#custom-tabs-three-assigned" role="tab" onclick="change_tab_num('custom-tabs-three-assigned')"
                                                aria-controls="custom-tabs-three-assigned" aria-selected="false">Assigned
                                                Dossiers</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="custom-tabs-three-reassign-tab" data-toggle="pill"
                                                href="#custom-tabs-three-reassign" role="tab" onclick="change_tab_num('custom-tabs-three-assigned')"
                                                aria-controls="custom-tabs-three-reassign" aria-selected="false">Reassign
                                                Dossiers</a>
                                        </li>

                                    </ul>
                                    {{--END TAB NAME LIST--}}
                                </div>
                                <div class="card-body">

                                    <div class="tab-content" id="custom-tabs-three-tabContent">



                                        {{---------------- START OF All Unassigned Dossiers------------}}
                                        @include('dossier_assignment.unassigned_tab')
                                        {{---------------- END OF All Unassigned Dossiers------------}}

                                        {{----------- START OF All assigned Dossiers----------------------}}
                                        @include('dossier_assignment.assigned_tab')
                                        {{--------------------END OF All assigned Dossiers ------------------}}

                                        {{----------- START OF All reassign Dossiers----------------------}}
                                        @include('dossier_assignment.reassign')
                                        {{--------------------END OF All reassign Dossiers ------------------}}




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
<script src="{{ asset('plugins/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>
<script>



    var tab_hidden_id = document.getElementById('hidden_tab_id').value
    var a_element = document.getElementById(tab_hidden_id + '-tab')
    a_element.className += ' active'
    var b_element = document.getElementById(tab_hidden_id)
    b_element.className += ' show active'




    function show_upload() {
        if (document.getElementById('assessment_report_submit').hidden == false) {
            document.getElementById('assessment_report_submit').hidden = true;

        } else {
            document.getElementById('assessment_report_submit').hidden = false;
        }
    }

    $(function () {
        bsCustomFileInput.init();
    });
    $(document).ready(function () {
        $('[data-toggle="tooltip"]').tooltip();
    });
    $(function () {
        bsCustomFileInput.init();
    });


    // Delete Record Modal to confirm before deletion
    $('#deleteRecordModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var action = button.data('action');
        var modal = $(this);
        modal.find('form').attr('action', action);
    });
    function change_tab_num(tab_id) {
        let dossier_assign_id = document.getElementById('hidden_dossier_assign_id').value
        var tab_id = tab_id;


        //    $.ajax({

        //             type:'GET',

        //             url:"{{ route('update_dossier_tab') }}",

        //             data:{tab_id:tab_id,dossier_assign_id:dossier_assign_id},

        //             success:function(data){


        //              },
        //           error:function (data) {

        //           console.log(data);
        //   }
        //           });

    }
</script>
@endsection