<?php


namespace Mpdf;

class CacheTest extends \Yoast\PHPUnitPolyfills\TestCases\TestCase
{
	protected $basePath;
	protected $oldTmpMode;
	protected $oldUmask;
	protected $isWin = false;

	public function __construct($name = null, array $data = [], $dataName = '')
	{
		parent::__construct($name, $data, $dataName);

		$this->basePath = __DIR__ . "/../../";
		$this->isWin = DIRECTORY_SEPARATOR === '\\';
	}

	protected function path($relativeToRoot)
	{
		return $this->basePath . $relativeToRoot;
	}

	protected function set_up()
	{
		parent::set_up();

		$dir = $this->path("tmp");
		$this->oldTmpMode = fileperms($dir);
		$this->oldUmask = umask(0);
		chmod($dir, 0777);
	}

	protected function tear_down()
	{
		chmod($this->path("tmp"), $this->oldTmpMode);
		umask($this->oldUmask);

		parent::tear_down();
	}

	public function testCacheCreatesNonexistentDirectory()
	{
		$dir = $this->path("tmp/test1");

		try {
			new Cache($dir);

			$this->assertDirectoryExists($dir);

			$this->assertFileExists($dir);
		} finally {
			@rmdir($dir);
		}
	}

	public function testCreatedDirectoryIsWorldWritable()
	{
		$dir = $this->path("tmp/test2");

		try {
			new Cache($dir);

			$this->assertEquals(0777, fileperms($dir) & 0777);
		} finally {
			@rmdir($dir);
		}
	}

	public function testCacheCreatesDirectoriesRecursively()
	{
		$dir = $this->path("tmp/test3/subdir/subdir2");

		try {
			new Cache($dir);

			$this->assertDirectoryExists($dir);
		} finally {
			@rmdir($dir);
			@rmdir($this->path("tmp/test3/subdir"));
			@rmdir($this->path("tmp/test3"));
		}
	}

	public function testRecursivelyCreatedDirectoriesAreWorldWritable()
	{
		$dir = $this->path("tmp/test4/subdir/subdir2");

		try {
			new Cache($dir);

			foreach (array(
						 "tmp/test4/subdir/subdir2",
						 "tmp/test4/subdir",
						 "tmp/test4",
					 ) as $subdir) {
				$this->assertEquals(0777, fileperms($this->path($subdir)) & 0777);
			}
		} finally {
			@rmdir($dir);
			@rmdir($this->path("tmp/test4/subdir"));
			@rmdir($this->path("tmp/test4"));
		}
	}

	public function testCreatedDirectoryInheritsParentPermissions()
	{
		chmod($this->path("tmp"), 0755);

		$dir = $this->path("tmp/test5");

		try {
			new Cache($dir);

			$this->assertEquals($this->isWin ? 0777 : 0755, fileperms($dir) & 0777);
		} finally {
			@rmdir($dir);
		}
	}

	public function testRecursivelyCreatedDirectoriesInheritsParentPermissions()
	{
		chmod($this->path("tmp"), 0750);
		$dir = $this->path("tmp/test6/subdir/subdir2");

		try {
			new Cache($dir);

			foreach (array(
				"tmp/test6/subdir/subdir2",
				"tmp/test6/subdir",
				"tmp/test6",
			) as $subdir) {
				$this->assertEquals($this->isWin ? 0777 : 0750, fileperms($this->path($subdir)) & 0777);
			}
		} finally {
			@rmdir($dir);
			@rmdir($this->path("tmp/test6/subdir"));
			@rmdir($this->path("tmp/test6"));
		}
	}

	public function testRecursivelyCreatedDirectoriesInheritsParentPermissionsAndOverridesUmask()
	{
		/* Set a umask and verify the directory is created with umask-affected permissions */
		umask(0077);
		mkdir($this->path("tmp/test7a"), 0755);
		$this->assertEquals($this->isWin ? 0777 : 0700, fileperms($this->path("tmp/test7a")) & 0777);

		/* Now verify the umask is ignored when creating the cache */
		chmod($this->path("tmp"), 0755);
		$dir = $this->path("tmp/test7b/subdir/subdir2");

		try {
			new Cache($dir);

			foreach (array(
						 "tmp/test7b/subdir/subdir2",
						 "tmp/test7b/subdir",
						 "tmp/test7b",
					 ) as $subdir) {
				$this->assertEquals($this->isWin ? 0777 : 0755, fileperms($this->path($subdir)) & 0777);
			}
		} finally {
			@rmdir($dir);
			@rmdir($this->path("tmp/test7b/subdir"));
			@rmdir($this->path("tmp/test7b"));
			@rmdir($this->path("tmp/test7a"));
		}
	}
}
