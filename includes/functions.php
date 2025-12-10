<?php

function sanitize_output($data) {
// converts tabs, newlines, and entities
    // $data = htmlentities($data, ENT_QUOTES);
    // $data = htmlentities("hey!! 	   ", ENT_QUOTES);
	// if (preg_match('/[\n&\t\"\'\\\\]/', $data)) {
	//     $data = preg_replace('/\t/', '<tab></tab>', $data);
	//     $data = preg_replace('/\n/', '<br>', $data);
	// }

	return $data;
	// return preg_replace('/\s\s+/', '', $data);
}
?>