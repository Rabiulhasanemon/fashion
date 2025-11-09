<?php
class ModelToolImage extends Model {
	public function resize($filename, $width, $height) {
		if (!is_file(DIR_IMAGE . $filename)) {
			return;
		}

		$extension = pathinfo($filename, PATHINFO_EXTENSION);

		$old_image = $filename;
		$new_image = 'cache/' . utf8_substr($filename, 0, utf8_strrpos($filename, '.')) . '-' . $width . 'x' . $height . '.' . $extension;

		if (!is_file(DIR_IMAGE . $new_image) || (filectime(DIR_IMAGE . $old_image) > filectime(DIR_IMAGE . $new_image))) {
			$path = '';

			$directories = explode('/', dirname(str_replace('../', '', $new_image)));

			foreach ($directories as $directory) {
				$path = $path . '/' . $directory;

				if (!is_dir(DIR_IMAGE . $path)) {
					@mkdir(DIR_IMAGE . $path, 0777);
				}
			}

		list($width_orig, $height_orig) = getimagesize(DIR_IMAGE . $old_image);

		// Skip image resizing during import to prevent memory exhaustion
		$import_in_progress = (defined('IMPORT_IN_PROGRESS') && IMPORT_IN_PROGRESS) || 
		                       (isset($GLOBALS['IMPORT_IN_PROGRESS']) && $GLOBALS['IMPORT_IN_PROGRESS']);
		
		if ($import_in_progress) {
			// During import, just copy the file without resizing - never load into memory!
			@copy(DIR_IMAGE . $old_image, DIR_IMAGE . $new_image);
		} elseif ($width_orig != $width || $height_orig != $height) {
			$image = new Image(DIR_IMAGE . $old_image);
			$image->resize($width, $height);
			$image->save(DIR_IMAGE . $new_image);
		} else {
			copy(DIR_IMAGE . $old_image, DIR_IMAGE . $new_image);
		}
		}

		if ($this->request->server['HTTPS']) {
			return HTTPS_CATALOG . 'image/' . $new_image;
		} else {
			return HTTP_CATALOG . 'image/' . $new_image;
		}
	}

	public function url($filename) {

        if ($this->request->server['HTTPS']) {
            return HTTPS_CATALOG . 'image/' . $filename;
        } else {
            return HTTP_CATALOG . 'image/' . $filename;
        }
    }
}