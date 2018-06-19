<?php unset ($status);?>
<script type="text/javascript">
	$(window).load(function(){        
	$('#ConfirmModal').modal('show');
	});
</script>
<!-- Modal -->
<div class="modal fade" id=ConfirmModal tabindex="-1" role="dialog" aria-labelledby="ConfirmModal" aria-hidden="true" style="display:initial;opacity: 1;padding-top:250px;background-color: rgba(0, 0, 0, 0.5);">
	<div class="modal-dialog modal-dialog-centered" role="document">
	    <div class="modal-content">
		    <div class="modal-header" style="background: #dc3030;">
		        <h2 class="modal-title" id="exampleModalLongTitle" style="color: white;">Booking Already Exists</h2>
		    </div>
		    <div class="modal-body">
		      	<div class="modal-body-article" style="display: flex; justify-content: center;">
					<p class="fa fa-times" style="font-size:30px;color:#dc3030;flex:0 1 20%;align-self: center;margin: 0;text-align: center;"></p>
					<p style="text-align: left; margin: 30px 0;">A booking for this event has already been done with this email. Please check your inbox.</p>
                </div>
		    </div>
		    <form action="index.php" id="myform">
			    <input type="submit" value="CLOSE" class="confirm-close">
			</form>
		</div>
	</div>
</div>
<!-- END MODAL -->
