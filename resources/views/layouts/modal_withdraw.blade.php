	<!-- Modal Withdraw -->		  
         <div class="modal fade" id="modal-withdraw">
          <form class='form-horizontal' role='form' method='post' enctype="multipart/form-data" action="{{route('withdrawals.store_withdrawal')}}">
          @csrf
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Request Marketing Authorization Withdrawal</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
            <input type="hidden" name="application_id" value="{{ $application->id}}"/>
            <input type="hidden" name="action_taken" value="Withdrawn"/>

			<span style="font-weight:bold;">Withdrawal Reason: </span> <input type="text" name="withdrawal_request_reason" class="form-control" /> <br/>
			<span style="font-weight:bold;">Withdrawal Date: </span><input type="date" class="form-control" required name="withdrawal_date_requested" />			<br/><br/>
			<span style="font-weight:bold;">Withdrawal document: </span><input type="file" class="form-control" required name="withdrawal_request_attachment" />		<br/>	
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Save</button>
              </div>
           </form>
          </div>
          </div>
          </div>
          <!-- /. End of  withdraw modal-content -->


<script type="text/javascript">
  $(function () {
      $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
    });


    $('#modal_withdrawl').click(function () {
        // alert('hellow');
       
       // $('#modelHeading_upload_psur').html("Upload PSUR");
        $('#modal-withdraw').modal('show');

  });

  });
</script>





