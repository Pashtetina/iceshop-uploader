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
	public $mimeMap = [
		'jpg' => 'image/jpeg',
		'png' => 'image/png',
		'gif' => 'image/gif',
		'bmp' => 'image/bmp'
	];

	protected $incoming = [],
		$_acceptMimeTypes = ['image/jpeg', 'image/gif', 'image/bmp', 'image/png'],
		$_maxFilesize = 5242880,
		$_tableName = 'images';

	private
		$_db;

	const ERR_MIME = 'File type isnt acceptable',
		ERR_SIZE = 'File size is too large',
		ERR_DB = 'Database error',
		ERR_OPEN = 'Can`t open file',
		ERR_WRITE = 'Can`t write file',
		ERR_NOT_FOUND = 'Image not found',
		ERR_RENAME = 'Can`t move uploladed file';


	public function init()
	{
		if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')
		{
			if (!empty($_POST))
			{
				if (isset($_POST['search']) && !empty($_POST['search']))
				{
					$this->search();
				}

				if (isset($_POST['links']))
				{
					foreach ($_POST['links'] as $link)
					{
						if (!empty($link))
							$this->incoming[] = $link;
					}
					$this->handle();
				}
			}
		}
	}


	public function handle()
	{
		foreach ($this->incoming as $link)
		{
			if ($img = file_get_contents($link))
			{
				$this->name = basename($link);
				$tmppath = $this->tmpdir . DIRECTORY_SEPARATOR . $this->name;

				if (file_put_contents($tmppath, $img))
				{
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
					}
				}
				else $this->errors[] = self::ERR_WRITE;
			}
			else $this->errors[] = self::ERR_OPEN;

		}

		$this->_response();
	}


	public function store()
	{
		$this->alias = uniqid('img-');
		$newpath = $this->dir . DIRECTORY_SEPARATOR . $this->alias . '.' . array_keys($this->mimeMap, $this->mime)[0];
		if (rename($this->path, $newpath))
		{
			$this->path = $newpath;
			if ($this->_db->insert($this->_tableName, ['path' => $this->path, 'alias' => $this->alias]))
			{
				$this->result[$this->alias] = ['status' => 'ok', 'hash' => $this->alias, 'link' => $this->_link()];
			}
			else $this->errors[] = self::ERR_DB;
		}
		else
		{
			$this->errors[] = self::ERR_RENAME;
		}
	}


	public function search()
	{
		$res = $this->_db->select($this->_tableName, "`alias` = '" . $_POST['hash']."'");

		if(!empty($res))
		{
			$this->path = $res[0]->path;
			$this->result[] = $this->_link();
		}
		else $this->errors[] = self::ERR_NOT_FOUND;

		return $this->_response();
	}


	private function _response()
	{
		Response::Json(['result' => $this->result, 'errors' => $this->errors]);
	}


	private function _link()
	{
		$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
		return $protocol.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']. $this->path;
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


	public function __construct()
	{
		$this->_db = DB::Instance();
	}
}