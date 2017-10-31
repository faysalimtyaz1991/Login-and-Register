<?php include('header.php'); ?>




	<div class="well text-center">

		<h1>
			Welcome to "Login and Register with PHP" homepage
		</h1>


		<?php if( isset($_SESSION['user']) ){ ?>
		<p>
			Loged In User Info:
			<hr />

			<p>
				<h3><?=$_SESSION['name']?></h3>
			</p>

		</p>
		<?php } ?>

	</div>
	
	
	


<?php include('footer.php'); ?>

