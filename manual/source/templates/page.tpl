<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

	<?php isset($html['head']) AND echo $html['head'];?>

	<body>

		<!-- START NAVIGATION -->
		<?php isset($html['navigation']) AND echo $html['navigation'];?>
		<!-- END NAVIGATION -->

		<!-- START BREADCRUMB -->
		<?php isset($html['breadcrumb']) AND echo $html['breadcrumb'];?>
		<!-- END BREADCRUMB -->

		<br clear="all" />

		<!-- START CONTENT -->
		<div id="content">
			<?php isset($html['content']) AND echo $html['content'];?>
		</div>
		<!-- END CONTENT -->

		<!-- START FOOTER -->
		<div id="footer">
			<p><a href="#top">Top of Page</a></p>
			<div id="copyrights">
				<p><a href="http://datamapper.wanwizard.eu/pages/index.html">Datamapper ORM v2.0</a> &nbsp;&middot;&nbsp; Copyright &copy; 2010-<?php echo date('Y'); ?> &nbsp;&middot;&nbsp; <a href="http://wanwizard.eu">Harro "WanWizard" Verton</a></p>
				<p><a href="<?php echo $vars['rootdir']; ?>/pages/license.html">Other License Information</a></p>
			</div>
		</div>
		<!-- END FOOTER -->

		<!-- START SCRIPTS -->
		<script type="text/javascript" src="<?php echo $vars['rootdir']; ?>/js/mootools.js"></script>
		<script type="text/javascript" src="<?php echo $vars['rootdir']; ?>/js/menu.js"></script>
		<script type="text/javascript">
			<!--
				window.addEvent('domready', function() {

					// Create Menu
					var menu = new Menu({
						basepath: '<?php echo $vars['rootdir']; ?>/',
						pagespath: '<?php echo $vars['rootdir']; ?>/pages/'
					});

				});
			//-->
		</script>
		<!-- END SCRIPTS -->

	</body>

</html>
