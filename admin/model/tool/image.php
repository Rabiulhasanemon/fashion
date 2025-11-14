<?php
class ModelToolImage extends Model {
	public function resize($filename, $width, $height) {
		if (!is_file(DIR_IMAGE . $filename)) {
			// Return placeholder if file doesn't exist
			if ($this->request->server['HTTPS']) {
				return HTTPS_CATALOG . 'image/cache/no_image-' . $width . 'x' . $height . '.png';
			} else {
				return HTTP_CATALOG . 'image/cache/no_image-' . $width . 'x' . $height . '.png';
			}
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

		$image_info = @getimagesize(DIR_IMAGE . $old_image);
		
		if (!$image_info) {
			// Invalid image file, return placeholder
			if ($this->request->server['HTTPS']) {
				return HTTPS_CATALOG . 'image/cache/no_image-' . $width . 'x' . $height . '.png';
			} else {
				return HTTP_CATALOG . 'image/cache/no_image-' . $width . 'x' . $height . '.png';
			}
		}
		
		list($width_orig, $height_orig) = $image_info;

		// Skip image resizing during import to prevent memory exhaustion
		$import_in_progress = (defined('IMPORT_IN_PROGRESS') && IMPORT_IN_PROGRESS) || 
		                       (isset($GLOBALS['IMPORT_IN_PROGRESS']) && $GLOBALS['IMPORT_IN_PROGRESS']);
		
		if ($import_in_progress) {
			// During import, just copy the file without resizing - never load into memory!
			@copy(DIR_IMAGE . $old_image, DIR_IMAGE . $new_image);
		} elseif ($width_orig != $width || $height_orig != $height) {
			try {
				$image = new Image(DIR_IMAGE . $old_image);
				$image->resize($width, $height);
				$image->save(DIR_IMAGE . $new_image);
			} catch (Exception $e) {
				// If resize fails, copy original or return placeholder
				if (is_file(DIR_IMAGE . $old_image)) {
					@copy(DIR_IMAGE . $old_image, DIR_IMAGE . $new_image);
				} else {
					if ($this->request->server['HTTPS']) {
						return HTTPS_CATALOG . 'image/cache/no_image-' . $width . 'x' . $height . '.png';
					} else {
						return HTTP_CATALOG . 'image/cache/no_image-' . $width . 'x' . $height . '.png';
					}
				}
			}
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