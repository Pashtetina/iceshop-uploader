<?php

class Image
{
	protected
		$_acceptMimeTypes = ['jpg', 'jpeg', 'gif', 'bmp', 'png'];

	private
		$_db;

	public function __construct()
	{
		$this->_db = DB::Instance();
	}


	public function open()
	{

	}
}