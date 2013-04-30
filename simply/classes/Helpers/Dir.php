<?php

namespace Helpers;

/**
 * Helper Dir
 */
class Dir {

	/**
	 * Create folder
	 *
	 * @param string $path
	 * @param string $rights - default = 0777
	 * @return string path
	 */
	public static function mkdir($path, $rights = 0777)
	{
		$path = trim($path, '/');

		if ( ! is_dir($path) )
		{
			mkdir($path, $rights, TRUE);
		}

		if ( ! is_writable($path))
		{
			chmod($path, $rights);
		}

		return $path;
	}

	/**
	 * Removes directory and(or only) entire folder content
	 *
	 * @static
	 * @throws Kohana_Exception
	 * @param string $dir_name
	 * @param bool   $entire_only If set clears only entire folder content (folders and files inside)
	 * @return void
	 */
	public static function rmdir($dir_name, $entire_only = FALSE)
	{
		if (is_dir($dir_name))
		{
			$objects = scandir($dir_name);

			foreach ($objects as $object)
			{
				if ($object != '.' AND $object != '..')
				{
					$object_inside = $dir_name . DIRECTORY_SEPARATOR . $object;

					if (filetype($object_inside) == 'dir')
					{
						static::rmdir($object_inside);
					}
					else
					{
						if( ! unlink($object_inside))
						{
							throw new \Core\Exception('can\'t remove object ' . $object_inside);
						}
					}
				}
			}

			reset($objects);

			if($entire_only === FALSE)
			{
				if( ! rmdir($dir_name))
				{
					throw new \Core\Exception('can\'t remove directory ' . $dir_name);
				}
			}
		}
		\Simply::$log->add(\Core\Log::INFO, 'Cache directory '.$dir_name.' was cleared due to TTL expiration');
	}
}
