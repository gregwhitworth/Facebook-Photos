<?php	

	if( isset( $message ) ) 
	{
		printf(' <div class="notice">%s</div>', $message);
	}

	echo form_open($form_action);

	$this->table->set_template($cp_table_template);
	$this->table->set_heading(array(array('style' => 'width: 50%', 'data' => lang('preference')), lang('setting')));


	$this->table->add_row(
		lang('Facebook ID:', 'facebook_id'),
		form_input('facebook_id', $facebook_id, 'id="facebook_id" style="width: 98%"')
	);

	echo $this->table->generate();

	if( isset( $facebook_albums ) )
	{
		
		// CHECKED ALBUMS
		if( isset($facebook_albums['checked']) )
		{
			$this->table->set_template($cp_table_template);
			$this->table->set_heading(array(array('style' => 'width: 50%', 'data' => lang('Album')), lang('Show')));
			$this->table->set_caption('Selected Albums');

			foreach( $facebook_albums['checked'] as $album_name => $album ) 
			{
				$data = array(
						'name' => 'facebook_albums[]',
						'id' => $album_name,
						'value' => $album,
						'checked' => TRUE
				);
				$this->table->add_row(
					lang( $album_name . ':', 'facebook_albums'),
					form_checkbox( $data )
				);
			}

			echo $this->table->generate();
		}

		if( isset($facebook_albums['unchecked']) )
		{
			$this->table->set_template($cp_table_template);
			$this->table->set_heading(array(array('style' => 'width: 50%', 'data' => lang('Album')), lang('Show')));
			$this->table->set_caption('Available Albums');

			// UNCHECKED ALBUMS
			foreach( $facebook_albums['unchecked'] as $album_name => $album ) 
			{
				$data = array(
						'name' => 'facebook_albums[]',
						'id' => $album_name,
						'value' => $album,
						'checked' => FALSE
				);
				$this->table->add_row(
					lang( $album_name . ':', 'facebook_albums'),
					form_checkbox( $data )
				);
			}

			echo $this->table->generate();
		}
	}

	echo form_submit(array('name' => 'submit', 'value' => lang('submit'), 'class' => 'submit'));
	echo form_close();

?>