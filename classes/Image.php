<?php

class Image
{
	public $name;
	public $tmpdir = 'tmp';
	public $dir = 'imgs';
	public $path;
	public $mime;
	public $alias;
	public $size;
	public $errors = [];
	public $result = [];

	const ERR_MIME = 'File type isnt acceptable',
		ERR_SIZE = 'File size is too large',
		ERR_DB = 'Database error',
		ERR_RENAME = 'Can`t move uploladed file';

	protected
		$_acceptMimeTypes = ['image/jpeg', 'image/gif', 'image/bmp', 'image/png'];

	protected
		$_maxFilesize = 5 * 1024 * 1024;

	private
		$_db;

	public function __construct()
	{
		$this->_db = DB::Instance();
	}

	public function init()
	{

	}


	public function open()
	{
		if (!empty($_POST))
		{
			if (isset($_POST['link']))
			{
				$link = $_POST['link'];
				$img = file_get_contents($link);
				$this->name = basename($link);

				$tmppath = $this->tmpdir . DIRECTORY_SEPARATOR . $this->name;
				if (file_put_contents($tmppath, $img))
				{
					//file moved to temporary storage
					$this->mime = mime_content_type($tmppath);
					$this->size = filesize($tmppath);
					$this->path = $tmppath;
					if ($this->_validate())
					{
						$this->store();
					}
					else
					{
						unlink($this->path);
						$this->_response($this->errors);
					}
				}

			}
		}
		else return false;
	}

	/**
	 * @todo need mime map
	 */
	public function store()
	{
		$this->alias = uniqid('img-');
		var_dump($this->path, $this->dir . DIRECTORY_SEPARATOR . $this->alias . $this->mime);
		if (rename($this->path, $this->dir . DIRECTORY_SEPARATOR . $this->alias . $this->mime))
		{
			$this->path = $this->dir . DIRECTORY_SEPARATOR . $this->alias . $this->mime;
			if($this->_db->insert('images', ['path' => $this->path, 'alias' => $this->alias]))
			{
				$res = ['status' => 'ok', 'hash' => $this->alias, 'link' => $this->_link($this->alias)];
				$this->_response($res);
			}
			else $this->errors[] = self::ERR_DB;
		}
		else
		{
			$this->errors[] = self::ERR_RENAME;
		}

		$this->_response($this->errors);
	}


	private function _response($data)
	{
		Response::Json($data);
	}

	private function _link($name)
	{
		 return $_SERVER['SCRIPT_URI'].$this->dir.DIRECTORY_SEPARATOR.$name;
	}

	private function _validate()
	{
		if (!$this->_checkMimeType())
		{
			$this->errors[] = self::ERR_MIME;
		}

		if ($this->size > $this->_maxFilesize)
		{
			$this->errors[] = self::ERR_SIZE;
		}

		return empty($this->errors) ? true : false;
	}


	private function _checkMimeType()
	{
		return in_array($this->mime, $this->_acceptMimeTypes);
	}
}