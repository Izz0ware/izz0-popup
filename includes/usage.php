<div class="wrap">
	<h1>Izz0ware Popups</h1>
	<div class="izz0ware-grid">
		<div class="section noborder nomargin">
			<div class="group">
				<div class="col span_12_of_12">
					<h4>Advanced usage</h4>
					This page shows some advanced usage of the Izz0ware Popup plugin.<br>
					It is made to support content designed in Site Origin Page Builder, and supports JavaScript as well.<br>
					Data for the javascript can be passed through the shortcode, as shown in the examples below.
				</div>
			</div>
		</div>
		<div class="section coloredrow">
			<div class="group header">
				<div class="col span_5_of_12">Description</div>
				<div class="col span_5_of_12">Shortcode</div>
				<div class="col span_2_of_12">Preview</div>
			</div>
			<div class="group">
				<div class="col span_5_of_12">Description</div>
				<div class="col span_5_of_12"><pre>[izz0warepopup id="plain"
	text="Popup Link"]</pre></div>
				<div class="col span_2_of_12"><?= do_shortcode('[izz0warepopup id="plain" text="Popup Link"]'); ?></div>
			</div>
			<div class="group">
				<div class="col span_5_of_12">Description</div>
				<div class="col span_5_of_12"><pre>[izz0warepopup id="java"
	text="Popup Link"
	data-value1="Custom Value"]</pre></div>
				<div class="col span_2_of_12"><?= do_shortcode('[izz0warepopup id="java" text="Popup Link" data-value1="Custom Value"]'); ?></div>
			</div>
		</div>
	</div>
</div>