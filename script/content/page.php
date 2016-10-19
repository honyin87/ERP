<?php 
include_once('../init.php');
include_once('../../config/config.php');
use RedBeanPHP\R;

$container= R::load( 'uicontainer', 1 );
?>
<div class="row tile_count">
		</div>
		
		<div class="panel panel-default">
<?php 
		if($container!=null && $container->title!=null){
			echo '<div class="panel-heading ">'.$container->title;
			echo '<ul class="nav navbar-right panel_toolbox">';
            echo '<li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>';
            echo '</li>';
			echo '<li class="dropdown">';
            echo          '<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>';
            //echo          '<ul class="dropdown-menu" role="menu">';
            //echo            '<li><a href="#">Settings 1</a>';
            //echo            '</li>';
            //echo            '<li><a href="#">Settings 2</a>';
            //echo            '</li>';
            //echo          '</ul>';
            echo        '</li>';
			echo '<li><a class="close-link"><i class="fa fa-close"></i></a></ul>';
			echo '</div>';
		}
?>
			<div class="panel-body">
			  <div class="container container-table">

				<div class="table-responsive col-lg-12">
					 <?php
					$dataTableID='test_table';
					include('dataTable.php');
					?>
				</div>
		
			</div>		
			</div>
		</div>
		<?php
			$javascript_type='PANEL';
			include('../javascript.php');
		?>