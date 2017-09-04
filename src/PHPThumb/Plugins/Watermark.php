<?php namespace PHPThumb\Plugins;

class Watermark implements \PHPThumb\PluginInterface {
	
	protected $_rightMargin;
	protected $_bottomMargin;
	protected $_pathWatermark;
	protected $_currentDimensions;
    protected $_workingImage;
    protected $_widthFill;
	
	public function __construct(int $rightMargin, int $bottomMargin, string $pathToWatermark, $widthFill = .5) {
	
		$this->_rightMargin = $rightMargin;
		$this->_bottomMargin = $bottomMargin;
		$this->_pathToWatermark = $pathToWatermark;
		$this->_widthFill = $widthFill;
	
	}

	public function execute($phpthumb) {
		
		if (is_readable($this->_pathToWatermark)) {
			
			$this->_currentDimensions = $phpthumb->getCurrentDimensions();
			$this->_workingImage      = $phpthumb->getWorkingImage();

			// load image used as watermark
			$stamp = imagecreatefrompng($this->_pathToWatermark);
			$sx = imagesx($stamp);
			$sy = imagesy($stamp);
			
			imagealphablending($this->_workingImage, true);
			imagesavealpha($this->_workingImage, true);
			
			$isx = imagesx($this->_workingImage);
			$isy = imagesy($this->_workingImage);

			$newThumbWidth = $isx * $this->_widthFill;
			$newThumbHeight = $sy * $newThumbWidth / $sx;

			$this->imageCopyMergeAlpha($this->_workingImage,
					  $stamp,
					  $isx - $newThumbWidth,
					  $isy - $newThumbHeight,
					  0,
					  0,
					  $newThumbWidth,
					  $newThumbHeight,
					  $sx,
					  $sy
					  );

			$phpthumb->setOldImage($this->_workingImage);
			
		}
			
		return $phpthumb;

	}
    
   	protected function imageCopyMergeAlpha(&$dst_im, &$src_im, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h){
   		imagecopyresampled($dst_im, $src_im, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h);
    }

}
