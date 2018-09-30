<?php
class Response
{
	static public function Json($data)
	{
		header('Content-type: application/json');
		echo json_encode($data);
		die();
	}
}