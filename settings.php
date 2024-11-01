<?php
require('wpframe.php');

wpframe_stop_direct_call(__FILE__);

if($_REQUEST['updated'] == 'true') wpframe_message(__('Hierarchy Updated', 'nsh'));

?>

<div class="wrap">
  <h2><?php _e("Manage Network Sites Hierarchy", 'nsh'); ?></h2>

  <table class="widefat">
    
	<thead>
      
	  <tr>
        
		<th scope="col"><div style="text-align: center;"><?php _e('ID', 'nsh') ?></div></th>
        
		<th scope="col"><?php _e('Site Title / Site Parent', 'nsh') ?></th>
        
		<th scope="col"><?php _e('Change Parent', 'nsh') ?></th>
        
		<th scope="col"><?php _e('Action', 'nsh') ?></th>
      
	  </tr>
    
	</thead>
    
	<tbody id="the-list">
      
	  <?php global $nsh_all_blogs;

		$dropdown = array();

		$nsh_class = '';
		
		if ( count($nsh_all_blogs) ) {

			foreach($nsh_all_blogs as $blog) {
				
				$dropdown[] = array( 'id' => absint($blog), 'name' => get_blog_option( absint($blog), 'blogname' ) );
			}
				
			foreach($nsh_all_blogs as $blog) {
				
				$descendants = nsh_get_descendants( $blog );
				
				$nsh_class = ('alternate' == $nsh_class) ? '' : 'alternate'; ?>
				
				<form id="form_<?php echo $blog; ?>" action="edit.php?action=nsh_updateparent" method="post">
				
				<?php echo "<tr class='". $nsh_class . "'>"; ?>	
				
				<th scope="row" style="text-align: center;"><?php echo $blog; ?><input name="site_id" type="hidden" value="<?php echo $blog; ?>"></th>
				
				<td><?php echo '<b>' . get_blog_option(absint($blog), 'blogname') . '</b>';
					
					if ( get_blog_option(absint($blog), 'nsh_parent') != '0' )
						echo __(' (parent: ','nsh'). get_blog_option(absint(get_blog_option(absint($blog), 'nsh_parent')),'blogname') .')'; ?>
				
				</td>
				
				<td><?php if ($blog != '1') {
					
					echo '<SELECT name="parent">';
					
					echo '<option value="0">'. __('No hierarchy','nsh'). '</option>';
					
					foreach ($dropdown as $arr) {
						
						if ($blog != $arr['id'] && !in_array( $arr['id'], $descendants ) ) {
							
							echo '<OPTION value="'.$arr['id'] .'"' ;
							
							if ( get_blog_option($blog, 'nsh_parent') == $arr['id'] )
								echo ' SELECTED';
							
							echo ' > '.$arr['name'].'</option>';
							}
					}
					
					echo '</select>';
				}
					
				else {
				
					_e('This is the main site.','nsh');
				}
				?></td>
				
				<td> <?php if ($blog != '1') { ?>
				
				<button type="submit" value="submit">
				
					<?php _e('Save Parent', 'nsh')?>
				
				</button>
				
				<?php } ?></td> 
				
				</tr>
				
				</form> 
    <?php 
		} 
	} 
	
	else { ?>

    <tr>
		
		<td colspan="3"><?php _e('No sites found in this network.', 'nsh') ?></td>
    
	</tr>
    
	<?php 
} ?>
    </tbody>
    
  </table>

</div>