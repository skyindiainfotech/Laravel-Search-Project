<div class="titlebar">
	<div class="user_name"> <span>{{ Auth::guard('admins')->user()->name }}</span></div>
	
		
	@if(isset($bread_crumb_array) && count($bread_crumb_array) > 0)
	<nav aria-label="breadcrumb">
	  <ol class="breadcrumb">
		<?php 
			$last = count($bread_crumb_array);
			$count = 1;
			foreach ($bread_crumb_array as $key => $value) {
				?>
					<li class="breadcrumb-item <?php echo $last == $count ? 'active':'';?>">                            
						<?php if($value != ""):?>
						<a href="<?php echo $value == "" ? '#':$value;?>"><?php echo $key;?></a>
						<?php else:?>
						<?php echo $key;?>
						<?php endif;?>
					</li>            
				<?php
				$count++;
			}
		?>
	  </ol>
	</nav>
	@endif
	<div class="clearfix"></div>
</div>