@extends('layouts.app')
@section('stylesheets')

<link rel="stylesheet" href="{{asset('plugins/summernote/summernote-bs4.min.css') }}">
@endsection
@section('content')

<div class="row">

    <!-- /.col -->
    <div class="col-md-8 offset-2">

            <form method="POST" action="{{ route('send_query_issue') }}" >
                @csrf
                        <div class="card card-primary card-outline">
                            <div class="card-header">
                                <h3 class="card-title">Query Issue </h3>

                            </div>
                            <!-- /.card-header -->

                                    <div class="card-body">
                                        <div class="form-group">
                                            <input class="form-control" placeholder="To: {{ $dossier_evaluation_details->application_id }}">
                                          </div>
                                          <div class="form-group">
                                            <input class="form-control" placeholder="Subject: {{ $breadcrumb_title }}">
                                          </div>

                              <input type="hidden" value="{{ $template->id }}" name="hidden_template_id" />
                              <input type="hidden" value="{{ $dossier_evaluation_details->id }}" name="hidden_dossier_asg_id" />
                                        <div class="form-group">
                                                                                <textarea id="summernote" class="form-control" name='data' style="height: 300px; display: none;">
                                                                                <div class="page">
                                                                                        <p class="MsoNormal" style="text-align:center" align="center"><b style="mso-bidi-font-weight:
                                                                                            normal"><span style="font-size:14.0pt;line-height:107%;font-family:
                                                                                            &quot;Cambria&quot;,&quot;serif&quot;" lang="EN-US">Queries to &lt;<span style="color:#2E74B5;mso-themecolor:
                                                                                            accent1;mso-themeshade:191">Name of the manufacturer</span>&gt; for the
                                                                                            Registration of &lt;<span style="color:#2E74B5;mso-themecolor:accent1;
                                                                                            mso-themeshade:191">Product name, dosage form and strength</span>&gt;</span></b></p>

                                                                                            <p class="MsoNormal" style="text-align:justify"><span style="font-size:12.0pt;line-height:107%;font-family:&quot;Cambria&quot;,&quot;serif&quot;" lang="EN-US">Upon
                                                                                            evaluation of the dossier for the registration of <b style="mso-bidi-font-weight:
                                                                                            normal">&lt;<span style="color:#2E74B5;mso-themecolor:accent1;mso-themeshade:
                                                                                            191">Product name, dosage form and strength</span>&gt;</b>, the following
                                                                                            queries have been raised. Therefore, please address the following questions
                                                                                            with the respect to the submitted product dossier.</span></p>
                                                                                            <p class="MsoNormal" style="text-align:justify"><span style="font-size:12.0pt;line-height:107%;font-family:&quot;Cambria&quot;,&quot;serif&quot;" lang="EN-US">Module
                                                                                                    1</span></p>

                                                                                                    <p class="MsoNormal" style="text-align:justify"><span style="font-size:12.0pt;line-height:107%;font-family:&quot;Cambria&quot;,&quot;serif&quot;" lang="EN-US">&lt;<span style="color:#2E74B5;mso-themecolor:accent1;mso-themeshade:191">Section title</span>&gt;
                                                                                                    (&lt;<span style="color:#2E74B5;mso-themecolor:accent1;mso-themeshade:191">Page
                                                                                                    number</span>&gt;)</span></p>

                                                                                                    <p class="MsoNormal" style="text-align:justify"><span style="font-size:12.0pt;line-height:107%;font-family:&quot;Cambria&quot;,&quot;serif&quot;" lang="EN-US">&lt;<span style="color:#2E74B5;mso-themecolor:accent1;mso-themeshade:191">insert your
                                                                                                    comments on the respective section of Module 1 here</span>&gt;</span></p>

                                                                                                    <p class="MsoNormal" style="text-align:justify"><span style="font-size:12.0pt;line-height:107%;font-family:&quot;Cambria&quot;,&quot;serif&quot;" lang="EN-US">Module
                                                                                                    2</span></p>

                                                                                                    <p class="MsoNormal" style="text-align:justify"><span style="font-size:12.0pt;line-height:107%;font-family:&quot;Cambria&quot;,&quot;serif&quot;" lang="EN-US">&lt;<span style="color:#2E74B5;mso-themecolor:accent1;mso-themeshade:191">Section title</span>&gt;
                                                                                                    (&lt;<span style="color:#2E74B5;mso-themecolor:accent1;mso-themeshade:191">Page
                                                                                                    number</span>&gt;)</span></p>

                                                                                                    <p class="MsoNormal" style="text-align:justify"><span style="font-size:12.0pt;line-height:107%;font-family:&quot;Cambria&quot;,&quot;serif&quot;" lang="EN-US">&lt;<span style="color:#2E74B5;mso-themecolor:accent1;mso-themeshade:191">insert your
                                                                                                    comments on the respective section of Module 2 here</span>&gt;</span></p>

                                                                                                    <p class="MsoNormal" style="text-align:justify"><span style="font-size:12.0pt;line-height:107%;font-family:&quot;Cambria&quot;,&quot;serif&quot;" lang="EN-US">Module
                                                                                                    3</span></p>

                                                                                                    <p class="MsoNormal" style="text-align:justify"><span style="font-size:12.0pt;line-height:107%;font-family:&quot;Cambria&quot;,&quot;serif&quot;" lang="EN-US">&lt;<span style="color:#2E74B5;mso-themecolor:accent1;mso-themeshade:191">Section title</span>&gt;
                                                                                                    (&lt;<span style="color:#2E74B5;mso-themecolor:accent1;mso-themeshade:191">Page
                                                                                                    number</span>&gt;)</span></p>

                                                                                                    <p class="MsoNormal" style="text-align:justify"><span style="font-size:12.0pt;line-height:107%;font-family:&quot;Cambria&quot;,&quot;serif&quot;" lang="EN-US">&lt;<span style="color:#2E74B5;mso-themecolor:accent1;mso-themeshade:191">insert your
                                                                                                    comments on the respective section of Module 3 here</span>&gt;</span></p>

                                                                                                    <p class="MsoNormal" style="text-align:justify"><i style="mso-bidi-font-style:
                                                                                                    normal"><span style="font-size:10.0pt;line-height:107%;font-family:
                                                                                                    &quot;Cambria&quot;,&quot;serif&quot;" lang="EN-US">&nbsp;</span></i></p>
                                                                                </div>
                                                                            </textarea>

                                        </div>
                                        <div class="form-group">
                                            <div class="btn btn-default btn-file">
                                                <i class="fas fa-paperclip"></i> Attachment
                                                <input type="file" name="attachment">
                                            </div>
                                            <p class="help-block">Max. 32MB</p>
                                        </div>
                                    </div>
                                    <!-- /.card-body -->
                            <div class="card-footer">
                                <div class="float-right">
                                    <button type="button" class="btn btn-default"><i class="fas fa-pencil-alt"></i> Draft</button>
                                    <button type="submit" class="btn btn-primary"><i class="far fa-envelope"></i> Send</button>
                                </div>
                                <button type="reset" class="btn btn-default"><i class="fas fa-times"></i> Discard</button>
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
    $(function () {


        $('#summernote').summernote()

        // CodeMirror
        CodeMirror.fromTextArea(document.getElementById("codeMirrorDemo"), {
            mode: "htmlmixed",
            theme: "monokai"
        });
    })



</script>
@endsection
