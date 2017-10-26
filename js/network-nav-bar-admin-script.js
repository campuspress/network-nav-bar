(function($) {

	// Define color picker class
	$('.nnb-color-picker').wpColorPicker();

	// Uploading files
	var file_frame;
	var wp_media_post_id = wp.media.model.settings.post.id;

	$('#upload_logo_button').on('click', function (event) {
		event.preventDefault();

		if (file_frame) {

			file_frame.open();
			return;
		}

		file_frame = wp.media.frames.file_frame = wp.media({
			title: 'Select a image to upload',
			button: {
				text: 'Use this image',
			},
			multiple: false
		});

		file_frame.on('select', function () {

			attachment = file_frame.state().get('selection').first().toJSON();

			$('#image-preview').attr('src', attachment.url).css('width', 'auto');
			$('#image_attachment_id').val(attachment.id);
			$('#remove_logo_button').removeClass('nnb-hidden');
			$('#upload_logo_button').addClass('nnb-hidden');

			wp.media.model.settings.post.id = wp_media_post_id;

		});

		file_frame.open();
	});

	$('#remove_logo_button').click(function () {
		$('#image-preview').attr('src', '');
		$('#image_attachment_id').attr('value', '');
		$('#remove_logo_button').addClass('nnb-hidden');
		$('#upload_logo_button').removeClass('nnb-hidden');
	});

	$('a.add_media').on('click', function () {
		wp.media.model.settings.post.id = wp_media_post_id;
	});

	if ('' == $('#image-preview').attr('src')) {
		$('#remove_logo_button').addClass('nnb-hidden');
	} else {
		$('#upload_logo_button').addClass('nnb-hidden');
	}

}(jQuery));
