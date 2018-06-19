
<script type="text/javascript">
	$(window).load(function(){        
	$('#ConfirmModal').modal('show');
	});
</script>
<!-- Modal -->
<div class="modal fade" id=ConfirmModal tabindex="-1" role="dialog" aria-labelledby="ConfirmModal" aria-hidden="true" style="display:initial;opacity: 1;padding-top:250px;background-color: rgba(0, 0, 0, 0.5);">
	<div class="modal-dialog modal-dialog-centered" role="document" style="width:20%;">
	    <div class="modal-content">
		    <div class="modal-body">
		      	<div class="modal-body-article" style="display: flex; justify-content: center;">
					<p class="fa fa-times" style="font-size:30px;color:#dc3030;flex:0 1 100%;align-self: center;margin: 40px 0;text-align: center;"></p>
                </div>
		    </div>
		</div>
	</div>
</div>
<!-- END MODAL -->
<?php
	echo'loading signup.php';
	get_template_part('signup');
?>

