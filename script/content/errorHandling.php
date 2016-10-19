<?php
			$errNo = $errno;
			$errMsg = $errstr;
			$errorFile = $error_file;
			$errorLine = $error_line;
			$errorTrace = $error_trace;
			$errorCategory = $error_category;
?>
	<div class="container">
            <div class="bs-component">
              <div class="panel panel-danger">
                <div class="panel-heading">
                  <h3 class="panel-title"><?php echo strtoupper($errorCategory);?></h3>
                </div>
                <div class="panel-body">
                  <form class="form-horizontal">
					  <div class="">
						  <label  class="col-lg-2 control-label">Message</label>
						  <div class="col-lg-10 form-control-static ">
							<?php echo '<code>'.$errMsg.'</code>';?>
						  </div>
						</div>
						
						<div class="">
						  <label  class="col-lg-2 control-label">Error No.</label>
						  <div class="col-lg-10 form-control-static ">
							<?php echo '<code>'.$errNo.'</code>';?>
						  </div>
						</div>
						
						<div class="">
						  <label  class="col-lg-2 control-label">Location</label>
						  <div class="col-lg-10 form-control-static ">
							<?php echo '<code>'.$errorFile.'</code>';?>
						  </div>
						</div>
						
						<div class="">
						  <label  class="col-lg-2 control-label">Line No.</label>
						  <div class="col-lg-10 form-control-static ">
							<?php echo '<code>'.$errorLine.'</code>';?>
						  </div>
						</div>
						<?php
						  if($errorTrace!=null){
							echo '<div class="">';
							echo  '<label  class="col-lg-2 control-label">Call Stack</label>';
						  
								echo '<div class="col-lg-10 form-control-static ">';
								echo '<code>'.$errorTrace.'</code>';
								echo '</div>';
								echo '</div>';
							}
						  ?>

					</form>
                </div>
              </div>
			</div>
		</div>