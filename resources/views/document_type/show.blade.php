@extends('layouts.app')
@section('content')

<section class="content">
      <div class="container-fluid">
        <div class="row">
          <!-- left column -->
          <div class="col-md-6">
            <!-- general form elements -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">New Category</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form method='post' action="{{route('document_types.store')}}">
              @csrf
                <div class="card-body">
                  <div class="form-group">
                    <label for="exampleInputEmail1">Category Name</label>
                    <input class="form-control" id="category_name" value='{{$document->document_type}}' placeholder="Category Name" type="text">
                  </div>
                  <div class="form-group">
                    <label for="exampleInputPassword1">Description</label>
                    <input class="form-control" id="category_description" value="{{$document->description}}" placeholder="Category Description " type="text">

                  </div>



                <!-- /.card-body -->

                <div class="card-footer">
                    <a class="btn btn-default" href="{{ route('document_types.index') }}">Back</a>
                </div>
              </form>
            </div>
            <!-- /.card -->




          </div>
          <!--/.col (left) -->
          <!-- right column -->

          <!--/.col (right) -->
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>


@endsection
