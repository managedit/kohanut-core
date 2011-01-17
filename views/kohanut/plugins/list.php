 <div class="grid_12">
	
	<div class="box">
		<h1><?php echo __('Plugins') ?></h1>
			
			
		<ul class="standardlist">
			
		<?php
		$zebra = false;
		if (count($plugins) > 0)
		{
			foreach($plugins as $item)
			{
			?>
				<li <?php echo text::alternate('class="z"','') ?> title="<?php echo __('Click to edit') ?>" >
					<div class='actions'>
						<?php
						echo html::anchor(Route::get('kohanut-admin')->uri(array('controller'=>'plugins', 'action'=>'install', 'params' => $item->name)),
							 '<div class="fam-plugin-add"></div><span>'.__('install').'</span>',array('title'=>__('Click to install')));
						echo html::anchor(Route::get('kohanut-admin')->uri(array('controller'=>'plugins', 'action'=>'uninstall', 'params' => $item->name)),
							 '<div class="fam-plugin-delete"></div><span>'.__('uninstall').'</span>',array('title'=>__('Click to uninstall')));
						?>
					</div>
					<p><?php echo $item->name . ($item->installed ? ' (Installed)' : '') ?></p>
				</li>
				
			<?php
			}
		}
		else
		{
			echo '<li><p>'.__('No plugins found').'</p></li>';
		}
		?>
		</ul>
			
		</div>
			
	</div>
	
<div class="grid_4">
	<div class="box">
		<h1><?php echo __('Help') ?></h1>
		
		<h3><?php echo __('What are plugins?') ?></h3>
		<p><?php echo __('Plugins add new functionality to your Kohnaut install.') ?></p>
		   
	</div>
</div>
