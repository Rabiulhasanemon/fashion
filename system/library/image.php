<?php
class Image {
    private $file;
    private $image;
    private $width;
    private $height;
    private $bits;
    private $mime;

    public function __construct($file) {
        // CRITICAL: Skip loading image into memory during import to prevent memory exhaustion
        // Use multiple detection methods with priority
        $import_in_progress = false;
        
        // Method 1: Check constant (fastest)
        if (defined('IMPORT_IN_PROGRESS')) {
            $import_in_progress = (bool)constant('IMPORT_IN_PROGRESS');
        }
        
        // Method 2: Check global variable
        if (!$import_in_progress && isset($GLOBALS['IMPORT_IN_PROGRESS'])) {
            $import_in_progress = (bool)$GLOBALS['IMPORT_IN_PROGRESS'];
        }
        
        // Method 3: Check REQUEST URI for import operations
        if (!$import_in_progress && isset($_SERVER['REQUEST_URI'])) {
            $uri = $_SERVER['REQUEST_URI'];
            if (stripos($uri, 'product/import') !== false || stripos($uri, 'catalog/product/import') !== false ||
                stripos($uri, 'category/import') !== false || stripos($uri, 'catalog/category/import') !== false) {
                $import_in_progress = true;
            }
        }
        
        // Method 5: Check for POST file upload (common during imports)
        if (!$import_in_progress && isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_FILES['import_file']) || isset($_FILES['file'])) {
                // If there's a file upload, be cautious and skip image loading
                $import_in_progress = true;
            }
        }
        
        // Method 4: Check backtrace for import function calls
        if (!$import_in_progress) {
            $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 15);
            foreach ($trace as $frame) {
                if (isset($frame['function']) && strtolower($frame['function']) === 'import') {
                    // Check if it's a product or category import
                    if (isset($frame['class'])) {
                        $class = strtolower($frame['class']);
                        if (strpos($class, 'product') !== false || strpos($class, 'category') !== false) {
                            $import_in_progress = true;
                            break;
                        }
                    }
                }
            }
        }
        
        // If import is in progress, NEVER load image into memory
        if ($import_in_progress) {
            // Absolute minimal initialization - zero memory usage
            // Do NOT call getimagesize() as it can use memory with large images
            $this->file = $file;
            $this->width = 0;
            $this->height = 0;
            $this->bits = '';
            $this->mime = '';
            $this->image = null;
            
            // Skip ALL image processing during import - just store the path
            // Image metadata will be set later if needed, but not during import
            return; // CRITICAL: Exit early, never load image or call getimagesize()!
        }
        
        // Normal operation - load image into memory (only when NOT importing)
        if (file_exists($file)) {
            $this->file = $file;
            $info = getimagesize($file);
            $this->width  = $info[0];
            $this->height = $info[1];
            $this->bits = isset($info['bits']) ? $info['bits'] : '';
            $this->mime = isset($info['mime']) ? $info['mime'] : '';
            if ($this->mime == 'image/gif') {
                $this->image = imagecreatefromgif($file);
            } elseif ($this->mime == 'image/png') {
                $this->image = imagecreatefrompng($file);
            } elseif ($this->mime == 'image/jpeg') {
                $this->image = imagecreatefromjpeg($file);
            } elseif ($this->mime == 'image/webp') {
                $this->image = imagecreatefromwebp($file);
            }
        } else {
            exit('Error: Could not load image ' . $file . '!');
        }
    }

    public function getFile() {
        return $this->file;
    }

    public function getImage() {
        return $this->image;
    }

    public function getWidth() {
        return $this->width;
    }

    public function getHeight() {
        return $this->height;
    }

    public function getBits() {
        return $this->bits;
    }

    public function getMime() {
        return $this->mime;
    }

    public function save($file, $quality = 90) {
        // During import, skip image saving to prevent memory issues
        $import_in_progress = (defined('IMPORT_IN_PROGRESS') && IMPORT_IN_PROGRESS) || 
                               (isset($GLOBALS['IMPORT_IN_PROGRESS']) && $GLOBALS['IMPORT_IN_PROGRESS']);
        if ($import_in_progress) {
            // Just copy the original file if it exists - never process during import
            if ($this->file && file_exists($this->file)) {
                @copy($this->file, $file);
            }
            return;
        }
        
        $info = pathinfo($file);
        $extension = strtolower($info['extension']);
        if (is_resource($this->image)) {
            if ($extension == 'jpeg' || $extension == 'jpg') {
                imagejpeg($this->image, $file, $quality);
            } elseif ($extension == 'png') {
                imagepng($this->image, $file);
            } elseif ($extension == 'gif') {
                imagegif($this->image, $file);
            } elseif ($extension == 'webp') {
                imagewebp($this->image, $file);
            }
            imagedestroy($this->image);
        }
    }

    public function resize($width = 0, $height = 0, $default = '') {
        // Skip resizing during import to prevent memory issues
        $import_in_progress = (defined('IMPORT_IN_PROGRESS') && IMPORT_IN_PROGRESS) || 
                               (isset($GLOBALS['IMPORT_IN_PROGRESS']) && $GLOBALS['IMPORT_IN_PROGRESS']);
        if ($import_in_progress) {
            return;
        }
        
        if (!$this->width || !$this->height || !$this->image) {
            return;
        }
        $xpos = 0;
        $ypos = 0;
        $scale = 1;
        $scale_w = $width / $this->width;
        $scale_h = $height / $this->height;
        if ($default == 'w') {
            $scale = $scale_w;
        } elseif ($default == 'h') {
            $scale = $scale_h;
        } else {
            $scale = min($scale_w, $scale_h);
        }
        if ($scale == 1 && $scale_h == $scale_w && $this->mime != 'image/png') {
            return;
        }
        $new_width = (int)($this->width * $scale);
        $new_height = (int)($this->height * $scale);
        $xpos = (int)(($width - $new_width) / 2);
        $ypos = (int)(($height - $new_height) / 2);
        $image_old = $this->image;
        $this->image = imagecreatetruecolor($width, $height);
        if ($this->mime == 'image/png') {
            imagealphablending($this->image, false);
            imagesavealpha($this->image, true);
            $background = imagecolorallocatealpha($this->image, 255, 255, 255, 127);
            imagecolortransparent($this->image, $background);
        } else if ($this->mime == 'image/webp') {
            imagealphablending($this->image, false);
            imagesavealpha($this->image, true);
            $background = imagecolorallocatealpha($this->image, 255, 255, 255, 127);
            imagecolortransparent($this->image, $background);
        } else {
            $background = imagecolorallocate($this->image, 255, 255, 255);
        }
        imagefilledrectangle($this->image, 0, 0, $width, $height, $background);
        imagecopyresampled($this->image, $image_old, $xpos, $ypos, 0, 0, $new_width, $new_height, $this->width, $this->height);
        imagedestroy($image_old);
        $this->width = $width;
        $this->height = $height;
    }

    public function watermark($watermark, $position = 'bottomright') {
        switch($position) {
            case 'topleft':
                $watermark_pos_x = 0;
                $watermark_pos_y = 0;
                break;
            case 'topcenter':
                $watermark_pos_x = intval(($this->width - $watermark->getWidth()) / 2);
                $watermark_pos_y = 0;
                break;
            case 'topright':
                $watermark_pos_x = $this->width - $watermark->getWidth();
                $watermark_pos_y = 0;
                break;
            case 'middleleft':
                $watermark_pos_x = 0;
                $watermark_pos_y = intval(($this->height - $watermark->getHeight()) / 2);
                break;
            case 'middlecenter':
                $watermark_pos_x = intval(($this->width - $watermark->getWidth()) / 2);
                $watermark_pos_y = intval(($this->height - $watermark->getHeight()) / 2);
                break;
            case 'middleright':
                $watermark_pos_x = $this->width - $watermark->getWidth();
                $watermark_pos_y = intval(($this->height - $watermark->getHeight()) / 2);
                break;
            case 'bottomleft':
                $watermark_pos_x = 0;
                $watermark_pos_y = $this->height - $watermark->getHeight();
                break;
            case 'bottomcenter':
                $watermark_pos_x = intval(($this->width - $watermark->getWidth()) / 2);
                $watermark_pos_y = $this->height - $watermark->getHeight();
                break;
            case 'bottomright':
                $watermark_pos_x = $this->width - $watermark->getWidth();
                $watermark_pos_y = $this->height - $watermark->getHeight();
                break;
        }

        imagealphablending( $this->image, true);
        imagesavealpha( $this->image, true);
        imagecopy($this->image, $watermark->getImage(), $watermark_pos_x, $watermark_pos_y, 0, 0, $watermark->getWidth(), $watermark->getHeight());
        imagedestroy($watermark->getImage());
    }

    public function crop($top_x, $top_y, $bottom_x, $bottom_y) {
        $image_old = $this->image;
        $this->image = imagecreatetruecolor($bottom_x - $top_x, $bottom_y - $top_y);
        imagecopy($this->image, $image_old, 0, 0, $top_x, $top_y, $this->width, $this->height);
        imagedestroy($image_old);
        $this->width = $bottom_x - $top_x;
        $this->height = $bottom_y - $top_y;
    }

    public function rotate($degree, $color = 'FFFFFF') {
        $rgb = $this->html2rgb($color);
        $this->image = imagerotate($this->image, $degree, imagecolorallocate($this->image, $rgb[0], $rgb[1], $rgb[2]));
        $this->width = imagesx($this->image);
        $this->height = imagesy($this->image);
    }

    private function filter() {
        $args = func_get_args();
        call_user_func_array('imagefilter', $args);
    }

    private function text($text, $x = 0, $y = 0, $size = 5, $color = '000000') {
        $rgb = $this->html2rgb($color);
        imagestring($this->image, $size, $x, $y, $text, imagecolorallocate($this->image, $rgb[0], $rgb[1], $rgb[2]));
    }

    private function merge($merge, $x = 0, $y = 0, $opacity = 100) {
        imagecopymerge($this->image, $merge->getImage(), $x, $y, 0, 0, $merge->getWidth(), $merge->getHeight(), $opacity);
    }

    private function html2rgb($color) {
        if ($color[0] == '#') {
            $color = substr($color, 1);
        }
        if (strlen($color) == 6) {
            list($r, $g, $b) = [$color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5]];
        } elseif (strlen($color) == 3) {
            list($r, $g, $b) = [$color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2]];
        } else {
            return false;
        }
        $r = hexdec($r);
        $g = hexdec($g);
        $b = hexdec($b);
        return [$r, $g, $b];
    }
}