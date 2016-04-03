<?php

function pagination($results, $properties = array(), $file_game, $file_cat,$file_type) {
	$defaultProperties = array(
		'get_vars'	=> array(),
		'per_page' 	=> 15,
		'per_side'	=> 4,
		'get_name'	=> 'page'
	);
	
	foreach($defaultProperties as $name => $default) { $properties[$name] = (isset($properties[$name])) ? $properties[$name] : $default; }
	
	foreach($properties['get_vars'] as $name => $value) {
		if (isset($_GET[$name]) && $name != $properties['get_name']) {
			$GETItems[] = $name.'='.$value;
		}
	}
	$l = (empty($GETItems)) ? '?'.$properties['get_name'].'=' : '?'.implode('&', $GETItems).'&'.$properties['get_name'].'=';
	
	$totalPages		= ceil($results / $properties['per_page']);
	$currentPage 	= (isset($_GET[$properties['get_name']]) && $_GET[$properties['get_name']] > 1) ? $_GET[$properties['get_name']] : 1;
	$currentPage 	= ($currentPage > $totalPages) ? $totalPages : $currentPage;
	
	$previousPage 	= $currentPage - 1;
	$nextPage 		= $currentPage + 1;
	
	// calculate which pages to show
	if ($totalPages <= ($properties['per_side'] * 2) + 1) {
		$loopStart = 1;
		$loopRange = $totalPages;
	} else {
		$loopStart = $currentPage - $properties['per_side'];
		$loopRange = $currentPage + $properties['per_side'];
		
		$loopStart = ($loopStart < 1) ? 1 : $loopStart;
		while ($loopRange - $loopStart < $properties['per_side'] * 2) { $loopRange++; }
		
		$loopRange = ($loopRange > $totalPages) ? $totalPages : $loopRange;
		while ($loopRange - $loopStart < $properties['per_side'] * 2) { $loopStart--; }
	}

	// start placing data to output
	$output = '';
	$output .= '
	<div class="text-center">
	<a class="btn btn-default btn-block toggle-pagination"><i class="glyphicon glyphicon-plus"></i> Toggle Pagination</a>
	<ul class="pagination pagination-responsive pagination-lg">
	 ';
	
	
	// first and previous page
	if ($currentPage != 1) {
		$output	.= '<li><a href=\''.$l.'1&game='.$file_game.'&type='.$file_type.'&cat='.$file_cat.'\'>&#171;</a></li>';
		$output .= '<li><a href=\''.$l.$previousPage.'&game='.$file_game.'&type='.$file_type.'&cat='.$file_cat.'\' class=\'active\'>‹</a></li>';
	} else {
		$output .= '<li><span class=\'inactive\'>&#171;</span></li>';
		$output .= '<li><span class=\'inactive\'>‹</span></li>';
	}
	
	/* if ($loopStart > 2) {
		$output	.= '<a href=\''.$l.'1\' class=\'active\'>1</a><span class=\'inactive\'>...</span>';
	} */
	
	// add the pages
	for ($p = $loopStart; $p <= $loopRange; $p++) {
		if ($p != $currentPage) {
			$output .= '<li><a href=\''.$l.$p.'&game='.$file_game.'&type='.$file_type.'&cat='.$file_cat.'\' class=\'active\'>'.$p.'</a></li>';
		} else {
			$output .= '<li><span class=\'current\'>'.$p.'</span></li>';
		}
	}

	/* if ($totalPages - $loopRange > 1) {
		$output	.= '<span class=\'inactive\'>...</span><a href=\''.$l.$totalPages.'\' class=\'active\'>'.$totalPages.'</a>';
	} */
	
	// next and last page
	if ($currentPage != $totalPages) {
		$output .= '<li><a href=\''.$l.$nextPage.'&game='.$file_game.'&type='.$file_type.'&cat='.$file_cat.'\' class=\'active\'>›</a></li>';
		$output .= '<li><a href=\''.$l.$totalPages.'&game='.$file_game.'&type='.$file_type.'&cat='.$file_cat.'\' class=\'active\'>&#187;</a></li>';
	} else {
		$output .= '<li><span class=\'inactive\'>›</span></li>';
		$output .= '<li><span class=\'inactive\'>&#187;</span></li>';
	}
	
	$output .= '</ul>
   </div>';
	// end of output
	
	return array(
		'limit' => array(
			'first' 	=> $previousPage * $properties['per_page'],
			'second' 	=> $properties['per_page']
		),
		
		'output' => $output
	);
}