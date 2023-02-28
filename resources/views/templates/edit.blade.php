@extends('layouts.app')

@section('content')

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-8">

                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Edit Templates</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">

                            <form action="{{ route('template_update',$document->id) }}" method="POST"
                                  enctype="multipart/form-data">
                                @csrf


                                <div class="form-group">
                                    <label>Document Reference Number</label>
                                    <input type="text" name="ref_num" value="{{ $document->ref_num }}"
                                           class="form-control" placeholder="Enter Reference Number">
                                </div>

                                <div class="form-group">
                                    <label>Document Title</label>
                                    <input type="text" name="title" value="{{ $document->name }}" class="form-control"
                                           placeholder="Enter title">
                                </div>

                                <div class="form-group">
                                    <label for="SelectRounded0">Document Type</label>
                                    <select class="custom-select rounded-0" name="document_type" id="SelectRounded0">
                                        <option value='{{ $document->template_type }}'>{{ $document->document_type }}</option>
                                        @foreach ($document_types as $type)
                                            <option value='{{ $type->id }}'>{{ $type->document_type }}</option>

                                            @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label>Description</label>
                                    <textarea class="form-control" name="description"
                                               rows="3">{{ $document->description }}</textarea>
                                </div>

                                <div class="form-group">
                                    <label>Existing file: <span
                                            class='text-danger'> {{ $document->path }}  </span></label> <br/>
                                    <label for="input_file">Input File</label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" name="input_file" class="custom-file-input"
                                                   id="input_file">
                                            <label class="custom-file-label" for="input_file">Choose File</label>
                                        </div>
                                        <div class="input-group-append">
                                            <button type="submit" name="submit" value='Submit' class='btn btn-primary'>Submit</button>
                                        </div>
                                    </div>
                                    <br/>
                                    <div>
                                        <a type="button" class="btn btn-secondary" href="{{url()->previous()}}">
                                            <i class="fa fa-arrow-alt-circle-left"> <span style="font-family: Arial, Helvetica, sans-serif">Back</span></i></a>
                                    </div>

                                </div>

                            </form>
                        </div>
                        <!-- /.card-body -->
                    </div> {{--card card-primary--}}
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
