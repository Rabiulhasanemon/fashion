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
				if ($directory && $directory != '.') {
					$path = $path . '/' . $directory;

					if (!is_dir(DIR_IMAGE . $path)) {
						if (!@mkdir(DIR_IMAGE . $path, 0777, true)) {
							// If directory creation fails, try to use original image
							$log = new Log('image_cache_error.log');
							$log->write('Failed to create cache directory: ' . DIR_IMAGE . $path);
							// Return original image URL as fallback
							if ($this->request->server['HTTPS']) {
								return $this->config->get('config_ssl') . '/image/' . $filename;
							} else {
								return $this->config->get('config_url') . '/image/' . $filename;
							}
						}
					}
					
					// Ensure directory is writable
					if (!is_writable(DIR_IMAGE . $path)) {
						@chmod(DIR_IMAGE . $path, 0777);
					}
				}
			}

			// Get image dimensions
			$image_info = @getimagesize(DIR_IMAGE . $old_image);
			if (!$image_info) {
				// If getimagesize fails, return original
				if ($this->request->server['HTTPS']) {
					return $this->config->get('config_ssl') . '/image/' . $filename;
				} else {
					return $this->config->get('config_url') . '/image/' . $filename;
				}
			}
			
			list($width_orig, $height_orig) = $image_info;

			if ($width_orig != $width || $height_orig != $height) {
				try {
					$image = new Image(DIR_IMAGE . $old_image);
					$image->resize($width, $height);
					$result = $image->save(DIR_IMAGE . $new_image);
					
					// Verify file was created
					if (!is_file(DIR_IMAGE . $new_image)) {
						// Save failed, return original
						if ($this->request->server['HTTPS']) {
							return $this->config->get('config_ssl') . '/image/' . $filename;
						} else {
							return $this->config->get('config_url') . '/image/' . $filename;
						}
					}
				} catch (Exception $e) {
					// Image processing failed, return original
					$log = new Log('image_cache_error.log');
					$log->write('Image resize failed: ' . $e->getMessage() . ' for ' . $filename);
					if ($this->request->server['HTTPS']) {
						return $this->config->get('config_ssl') . '/image/' . $filename;
					} else {
						return $this->config->get('config_url') . '/image/' . $filename;
					}
				}
			} else {
				// Same size, just copy
				if (!@copy(DIR_IMAGE . $old_image, DIR_IMAGE . $new_image)) {
					// Copy failed, return original
					if ($this->request->server['HTTPS']) {
						return $this->config->get('config_ssl') . '/image/' . $filename;
					} else {
						return $this->config->get('config_url') . '/image/' . $filename;
					}
				}
			}
		}

		// Return cache image URL
		if ($this->request->server['HTTPS']) {
			return $this->config->get('config_ssl') . '/image/' . $new_image;
		} else {
			return $this->config->get('config_url') . '/image/' . $new_image;
		}
	}
}