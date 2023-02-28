@extends('layouts.app')
 
     <script src="{{asset('plugins/tree/jquery-3.3.1.slim.min.js')}}"></script>
    <script src="{{asset('plugins/tree/jquery-simpleTreeMenu.js')}}"></script>
    <link href="{{asset('plugins/tree/jquerysctipttop_3.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('plugins/tree/jquery-simpleTreeMenu.css')}}" rel="stylesheet" type="text/css" />


@section('content')


    <section class="content">
			
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Hierarchal file List</h3>
                            <div class="card-tools">
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">

						        <div class="row">
			  <?php
			  
			  $dir = "C:\\tests";
			  
        echo '    
		<div style="width:400px; background-color: white" class="well">
';
  
  function listFolderFiles2($dir)
{
    echo '<ul id="demoTree">';

    foreach (new DirectoryIterator($dir) as $fileInfo) {
        if (!$fileInfo->isDot()) {
            if ($fileInfo->isDir()) {
				echo '<li>
                               <button type="button" class="btn btn-primary p-0">
 <i class="expandable-table-caret fa fa-folder fa-fw"></i></button>
                                <a class="stm-content">' . $fileInfo->getFilename();				
				echo '</a>';                
			    listFolderFiles2($fileInfo->getPathname());
				echo '</li>';                
            }else{
				echo '<li>
                                <button type="button" class="btn btn-primary list_button p-0">
								<i class="expandable-table-caret fas fa-file-text"></i>
								</button>
                                <a class="stm-content" href="">' . $fileInfo->getFilename();				
			    echo '</a></li>';
			}
        }
    }
	    echo '</ul>';
}


listFolderFiles2('C:\\tests');

  ?>
                    <!-- /.card-body -->
                </div>
            </div>
        </div>
    </section>
	
	<script type="text/javascript">
    $('#demoTree').simpleTreeMenu();
</script>

@endsection




