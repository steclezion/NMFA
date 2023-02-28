@extends('layouts.app')
@section('stylesheets')

    <link rel="stylesheet" href="{{asset('plugins/summernote/summernote-bs4.min.css') }}">
@endsection
@section('content')

    <div class="row">

        <!-- /.col -->
        <div class="col-md-8 offset-2">

            <form method="POST" action="{{ route('save_to_draft') }}">
                @csrf
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title">Query Issue Cover Letter</h3>
                    </div>
                    <!-- /.card-header -->

                    <div class="card-body">


                        <input type="hidden" value="{{ $template->id }}" name="hidden_template_id" />
                        <input type="hidden" value="{{ $dossier_evaluation_details->dossier_ass_id }}" name="hidden_dossier_asg_id" />
                        <div class="form-group" id="data_div" >
                        <textarea id="summernote" class="form-control" name='data' style="height: 300px; display: none;">
                          @php
                          echo $data;
                          @endphp

                            </textarea>

                        </div>

                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <div class="modal-footer justify-content-between">
                            <a class="btn btn-primary" href="/dossier_evaluation/edit/{{$dossier_evaluation_details->dossier_ass_id}}" >Back</a>
                            <button type="submit" class="btn btn-default"><i class="far fa-envelope"></i> Save To Draft </button>
                            <a onclick="test()" class="btn btn-primary"><i class="fa fa-download"></i> Download  </a>
                        </div>
                    </div>
                    <!-- /.card-footer -->
                </div>
                <!-- /.card -->
            </form>

        </div>


        <!-- /.col -->
    </div>
@endsection
@section('scripts')
    <script src="{{asset('plugins/word_converter/printThis.js') }}"></script>
    <script src="{{ asset('plugins/summernote/summernote-bs4.min.js')}}"></script>

    <script>
        function test() {
            document.getElementById('name').style.width= '210mm';
            document.getElementById('name').style.height='297mm';
            document.getElementById('name').font_size='200px';
            $('#name').printThis({
                header: "<img src='images/nmfa_header.png' alt='tst'/>",
                footer: "<img src='images/nmfa_footer.png' alt='tst'/>"

            });
        }

        $(function () {


            $('#summernote').summernote()

            // CodeMirror
            CodeMirror.fromTextArea(document.getElementById("codeMirrorDemo"), {
                mode: "htmlmixed",
                theme: "monokai"
            });
        })

        $('#reservationdate').datetimepicker({
            format: 'L'

        });


    </script>
@endsection
