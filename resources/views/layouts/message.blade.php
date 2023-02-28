<section class="content">
<div class="container-fluid" id="alert-messages">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h5><i class="icon fas fa-check"></i> Success</h5>
            {{session('success')}}
        </div>
    @elseif(session('warning'))

        <div class="alert alert-warning alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h5><i class="icon fas fa-exclamation-triangle"></i> Warning</h5>
            {{session('warning')}}
        </div>

    @elseif(session('danger'))
        <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <!-- <h5><i class="icon fas fa-ban"></i> Error!</h5> -->
            {{session('danger')}}
        </div>

    @elseif(session('info'))
        <div class="alert alert-info alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h5><i class="icon fas fa-info"></i> Information</h5>
            {{session('info')}}
        </div>
    @endif
</div>
</section>
