<?php

	echo form_open($form_action);

	$this->table->set_template($cp_table_template);
	$this->table->set_heading(array(array('style' => 'width: 50%', 'data' => lang('preference')), lang('setting')));


	$this->table->add_row(
		lang('Facebook ID:', 'facebook_id'),
		form_input('facebook_id', $facebook_id, 'id="facebook_id" style="width: 98%"')
	);

	echo $this->table->generate();

	$this->table->set_template($cp_table_template);
	$this->table->set_heading(array(array('style' => 'width: 50%', 'data' => lang('Album')), lang('Show')));

	foreach( $facebook_albums as $album_name => $album ) {
		$data = array(
				'name' => 'facebook_albums[]',
				'id' => $album,
				'checked' => TRUE
		);
		$this->table->add_row(
			lang( $album_name . ':', 'facebook_albums'),
			form_checkbox( $data )
		);
	}

	echo $this->table->generate();

	echo form_submit(array('name' => 'submit', 'value' => lang('submit'), 'class' => 'submit'));
	echo form_close();

?>