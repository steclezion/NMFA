@extends('layouts.app')
@section('stylesheets')

    <link rel="stylesheet" href="{{asset('plugins/summernote/summernote-bs4.min.css') }}">
@endsection
@section('content')

    <div class="row">

        <!-- /.col -->
        <div class="col-md-8 offset-2">

            <form method="POST" action="{{ route('other_invitation') }}">
                @csrf
                <input type='hidden' name='venue' value="{{$data['venue']}}">
                <input type='hidden' name='time' value="{{$data['time']}}">
                <input type='hidden' name='decision_date' value=" {{$data['meeting_date']}}">
                <input type='hidden' name='description' value=" {{$data['description']}}">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title">PERC Meeting Invitation Letter</h3>
                    </div>
                    <!-- /.card-header -->

                    <div class="card-body">

                        <div class="form-group">
                            <label>Date: </label>
                            <span style="color:#2E74B5;mso-themecolor:accent1;mso-themeshade:191">
                                        @if (isset($date))
                                    {{ $date }}

                                @else
                                    [Date/Month/Year]
                                @endif
                                            </span>
                            <br>
                            <label>To:</label>
                            <span style="color:#2E74B5;mso-themecolor:accent1;mso-themeshade:191">
                                                @if (isset($percs))
                                    ALL PERC MEMBERS
                                    <br>
                                    @foreach($percs as $perc)
                                        {{$perc->first_name}}  {{$perc->middle_name}},&nbsp;&nbsp;
                                    @endforeach

                                @else
                                    ALL PERC MEMBERS

                                @endif

                                         </span>
                        </div>

                        <input type="hidden" value="{{ $template->id }}" name="hidden_template_id"/>
                        <div class="form-group">
                        <textarea id="summernote" class="form-control" name='data'
                                  style="height: 300px; display: none;font-size: xx-large">
                            <div id="name">
                                        

                                        <label>Subject: Product Evaluation and Registration Committee Meeting </label>

                                        <br>
                                        <br>
                                        Dear Sir/Madam
                                        <br>
                                        <br>
                                        As a member of the Product Evaluation and Registration Committee (PERC) you are kindly requested to attend a meeting which is to be held on
                                        <span style="color:#2E74B5;mso-themecolor:accent1;mso-themeshade:191"
                                              id='meeting_date_span'> @if (isset($data))
                                                {{$data['meeting_date']}}
                                            @else
                                                []
                                            @endif</span>.
                                        The objective of the meeting is to discuss on 
                                       <span style="color:#2E74B5;mso-themecolor:accent1;mso-themeshade:191"> [*possible points]</span>
                                        
                                        <br>
                                        <br>
                                        <br>
                                        <br>
                                       <span style="color:#2E74B5;mso-themecolor:accent1;mso-themeshade:191"> *Variation evaluation</span><br><br>
                                       <span style="color:#2E74B5;mso-themecolor:accent1;mso-themeshade:191"> *Waiver for registration</span><br><br>
                                       <span style="color:#2E74B5;mso-themecolor:accent1;mso-themeshade:191"> *Periodic safety updates</span><br><br>
                                       <span style="color:#2E74B5;mso-themecolor:accent1;mso-themeshade:191"> *Relevant meeting</span><br><br>
                                       <br>
                                       <br>
                                       <br>

                                       
                                         <span style="color:#2E74B5;mso-themecolor:accent1;mso-themeshade:191"
                                               id='venue_span_id'>
                                         @if(isset($data))
                                                 Venue: {{$data['venue']}}
                                             @else
                                                 Venue: [ name and address of the place]
                                             @endif
                                             </span>
                                             <br>
                                            
                                             <span style="color:#2E74B5;mso-themecolor:accent1;mso-themeshade:191"
                                                   id='time_span_id'>
                                             @if(isset($data))
                                                     Time: {{$data['time']}}
                                                 @else
                                                     Time: [xx:xx AM/PM]
                                                 @endif

                                             </span>
                                          <br>
                                          <br>
                                         Best regards,
                                         <br>
                                         <br>
                                         Secretary Name: <br>
                                         PERC Secretary<br>
                                         Ministry of Health<br>
                                         Asmara, Eritrea

                            </div>

                            </textarea>
                        </div>

                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <div class="modal-footer justify-content-between">
                            <a href="{{url()->previous()}}" class="btn btn-secondary"><i
                                        class="fas fa-arrow-circle-left"></i> Back </a>
                            <button type="submit" class="btn btn-primary"><i class="fas fa-upload"></i> Send</button>
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
            $('#name').printThis({
                header: "<img src='images/nmfa_header.png' alt='tst'/>",
                footer: "<img src='images/nmfa_footer.png' alt='tst'/>"

            });
        }

        function SummerNoteChanger(o, span_id) {
            document.getElementById(span_id).innerHTML = o.value;

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
