<?php
function jsonResult($code, $msg, $data = [], $callback = null) {
	$result = ['code' => $code, 'message' => $msg, 'data' => $data];
	if(is_null($callback)) return json_encode($result);
	return $callback . '(' . json_encode($result) . ')';
}
