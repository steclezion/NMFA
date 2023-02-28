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
                    <input class="form-control" id="category_name" name='category_name' placeholder="Category Name" type="text">
                  </div>
                  <div class="form-group">
                    <label for="exampleInputPassword1">Description</label>
                    <input class="form-control" id="category_description" name="category_description" placeholder="Category Description " type="text">

                  </div>
                  
                 
                  
                <!-- /.card-body -->

                <div class="card-footer">
                    <button type="submit" class="btn btn-default">Cancel</button>
                  <button type="submit" class="btn btn-success" style="float:right">Create</button>
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