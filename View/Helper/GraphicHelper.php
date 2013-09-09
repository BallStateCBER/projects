<?php
App::uses('AppHelper', 'View/Helper');

class GraphicHelper extends AppHelper {
	public function thumbnail($image_filename) {
		$filename_split = explode('.', $image_filename);
		$thumbnail_filename = array_slice($filename_split, 0, count($filename_split) - 1);
		$thumbnail_filename[] = 'thumb';
		$thumbnail_filename[] = end($filename_split);
		return implode('.', $thumbnail_filename);	
	}
}