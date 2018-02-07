<?php
function jsonResult($code, $msg, $data = [], $callback = null) {
	$result = ['code' => $code, 'message' => $msg, 'data' => $data];
	if(is_null($callback)) {
		echo json_encode($result);
		exit();
	} else {
		echo $callback . '(' . json_encode($result) . ')';
		exit();
	}
}
