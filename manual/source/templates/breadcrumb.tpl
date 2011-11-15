<table cellpadding="0" cellspacing="0" border="0" style="width:100%">
	<tr>
		<td id="breadcrumb">
			<a href="<?php echo $vars['rootdir']; ?>/">Datamapper ORM Home</a>
			<?php
				foreach ( $vars['breadcrumbs'] as $breadcrumb )
				{
					echo ' &nbsp;&#8250;&nbsp;';
					if ( isset($breadcrumb['url']) ) echo '<a href="'.$vars['rootdir'].$breadcrumb['url'].'">';
					if ( isset($breadcrumb['title']) ) echo $title;
					if ( isset($breadcrumb['url']) ) echo '</a>';
				}
			?>
		</td>
	</tr>
</table>
