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
                        <h3 class="card-title">Variation Query Details</h3>
                    </div>
                    <!-- /.card-header -->

                    <div class="card-body">


                        <input type="hidden" value="{{ $template->id }}" name="hidden_template_id" />
                        <input type="hidden" value="{{ $variation->variation }}" name="hidden_variation_id" />
                        <input type="hidden" value="query_details" name="type" />

                        <div class="form-group" >
                        <textarea id="summernote" class="form-control" name='data' style="height: 300px; display: none;font-size: xx-large">



                                            <p class="MsoNormal" style="text-align:center" align="center"><b style="mso-bidi-font-weight:
                                                normal"><span style="font-size:14.0pt;line-height:107%;font-family:
                                                &quot;Cambria&quot;,&quot;serif&quot;" lang="EN-US">Queries to <span style="color:#2E74B5;mso-themecolor:
                                                accent1;mso-themeshade:191">
                                                 @if (isset($variation))

                                                                {{ $variation->applicant_first_name }} {{ $variation->applicant_middle_name }}

                                                            @else
                                                                &lt;
                                                                Name of the manufacturer
                                                                &gt;
                                                            @endif
</span> on the variation of  <span style="color:#2E74B5;mso-themecolor:accent1;
                                                mso-themeshade:191"> @if (isset($variation))

                                                                {{ $variation->product_trade_name }},{{ $variation->dosage_form_name }}, {{ $variation->brand_name }}

                                                            @else
                                                                &lt; [name of the product, dosage form, strength] [Brand name] &gt;
                                                            @endif</span></span></span></b></p>

                                                <p class="MsoNormal" style="text-align:justify"><span style="font-size:12.0pt;line-height:107%;font-family:&quot;Cambria&quot;,&quot;serif&quot;" lang="EN-US">
                                                Upon evaluation of the submitted variations of  <b style="mso-bidi-font-weight:
                                                normal"><span style="color:#2E74B5;mso-themecolor:accent1;mso-themeshade:
                                                191">

                                                @if (isset($variation))

                                                                    {{ $variation->product_trade_name }},{{ $variation->dosage_form_name }}, {{ $variation->brand_name }}

                                                                @else
                                                                    &lt; [name of the product, dosage form, strength] [Brand name] &gt;
                                                                @endif
</span></b>, the following queries have been raised. Therefore, please address the following questions with the respect to the product variation.</span></p>
                                                <p class="MsoNormal" style="text-align:justify"><span style="font-size:12.0pt;line-height:107%;font-family:&quot;Cambria&quot;,&quot;serif&quot;" lang="EN-US">Question                                                        1</span></p>

                                                        <p class="MsoNormal" style="text-align:justify"><span style="font-size:12.0pt;line-height:107%;font-family:&quot;Cambria&quot;,&quot;serif&quot;" lang="EN-US">&lt;<span style="color:#2E74B5;mso-themecolor:accent1;mso-themeshade:191">Section title</span>&gt;
                                                        (&lt;<span style="color:#2E74B5;mso-themecolor:accent1;mso-themeshade:191">Page
                                                        number</span>&gt;)</span></p>

                                                        <p class="MsoNormal" style="text-align:justify"><span style="font-size:12.0pt;line-height:107%;font-family:&quot;Cambria&quot;,&quot;serif&quot;" lang="EN-US">&lt;<span style="color:#2E74B5;mso-themecolor:accent1;mso-themeshade:191">insert your
                                                        comments on the respective section here</span>&gt;</span></p>

                                                        <p class="MsoNormal" style="text-align:justify"><span style="font-size:12.0pt;line-height:107%;font-family:&quot;Cambria&quot;,&quot;serif&quot;" lang="EN-US">Question
                                                        2</span></p>

                                                        <p class="MsoNormal" style="text-align:justify"><span style="font-size:12.0pt;line-height:107%;font-family:&quot;Cambria&quot;,&quot;serif&quot;" lang="EN-US">&lt;<span style="color:#2E74B5;mso-themecolor:accent1;mso-themeshade:191">Section title</span>&gt;
                                                        (&lt;<span style="color:#2E74B5;mso-themecolor:accent1;mso-themeshade:191">Page
                                                        number</span>&gt;)</span></p>

                                                        <p class="MsoNormal" style="text-align:justify"><span style="font-size:12.0pt;line-height:107%;font-family:&quot;Cambria&quot;,&quot;serif&quot;" lang="EN-US">&lt;<span style="color:#2E74B5;mso-themecolor:accent1;mso-themeshade:191">insert your
                                                        comments on the respective section here</span>&gt;</span></p>


                                                        <p class="MsoNormal" style="text-align:justify"><i style="mso-bidi-font-style:
                                                        normal"><span style="font-size:10.0pt;line-height:107%;font-family:
                                                        &quot;Cambria&quot;,&quot;serif&quot;" lang="EN-US">&nbsp;</span></i></p>



                            </textarea>

                        </div>

                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <div class="modal-footer justify-content-between">
                            <a class="btn btn-secondary" href="/variation_evaluation/edit/{{$variation->variation_id}}" >Back</a>
                            <button type="submit" class="btn btn-success"><i class="fas fa-download"></i> Download </button>
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
