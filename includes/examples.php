<?php
switch ($a['id']) {
	case 'plain':
		$popup = array(
			'post_title' => 'Plain Popup',
			'post_content' => 'A plain popup with no JS added.',
		);
		$datavalues = array();
		$clickhandler = '';
		break;
	case 'java':
		$popup = array(
			'post_title' => 'Java Popup',
			'post_content' => 'A Java popup with some JS added.<br>Value 1: <span class="value1"></span><br>Value 2: <span class="value2"></span>',
		);
		$datavalues = array(
			array( 'dataname' => 'value1', 'default' => 'Default Value 1' ),
			array( 'dataname' => 'value2', 'default' => 'Default Value 2' ),
		);
		$clickhandler = '$(content).find(".value1").html(value1);$(content).find(".value2").html(value2);';
		break;
}