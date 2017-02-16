<style type="text/css">
	#editor {
		position: absolute;
		width: 100%;
		height: 250px;
	}
</style>

<?php $mb->the_field('clickhandler'); ?>
<div class="izz0ware-grid">
	<div class="section noborder nomargin">
		<div class="group">
			<div class="col span_3_of_12">
				<h3>Click Action</h3>
				Best to use for initialization of jQuery elements in the popup. This will run on the click event.<br>
				jQuery in noConflict mode is available at $.<br>
				The handle for the button is in a variable namned hndl.<br>
				The content of the popup is in a variable named content.
			</div>
			<div class="col span_9_of_12" style="position: relative; height: 250px;">
				<div id="editor"><?php $mb->the_value(); ?></div>
				<input id="editor_value" type="hidden" name="<?=$mb->the_name();?>" value="<?=$mb->the_value();?>" />
				<script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.2.0/ace.js" type="text/javascript" charset="utf-8"></script>
				<script type="text/javascript">
					var editor = ace.edit("editor");
					editor.setTheme("ace/theme/monokai");
					editor.getSession().setMode("ace/mode/javascript");
					editor.resize(true);
					editor.getSession().on('change', function(e) {
						var code = editor.getSession().getValue();
						document.getElementById('editor_value').value = code;
					});
				</script>
			</div>
		</div>
		<div class="group">
			<div class="col span_3_of_12">
				<h3>Required Data Values</h3>
				Values that HAS to be defined in the shortcode like below:<br>
				<code>[izz0warepopup data-valuename="value"]</code>
			</div>
			<div class="col span_9_of_12">
				<?php while($mb->have_fields_and_multi('datanames', array('length' => 1))): ?>
					<?php $mb->the_group_open(); ?>
					<?php $mb->the_field('dataname'); ?>
					<label>Dataname: <input type="text" name="<?php $mb->the_name(); ?>" value="<?php $mb->the_value(); ?>" /></label>
					<?php $mb->the_field('default'); ?>
					<label>Default Value: <input type="text" name="<?php $mb->the_name(); ?>" value="<?php $mb->the_value(); ?>" /></label>
					<a href="#" class="dodelete button"><i class="fa fa-trash"></i></a>
					<?php $mb->the_group_close(); ?>
				<?php endwhile; ?>
				<a href="#" class="docopy-datanames button"><i class="fa fa-plus"></i></a>
			</div>
		</div>
	</div>
</div>