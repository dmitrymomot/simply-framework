<?php

namespace Core;

/**
 * Core\Autoload
 *
 * Class implementation that implements the technical interoperability
 * standards for PHP 5.3 namespaces and class names.
 */
class Autoload {

	/**
	 * Class file extention
	 *
	 * @var string
	 */
    private $_file_extension = '.php';

    /**
     * @var string
     */
    private $_namespace;

    /**
     * Paths for autoloading
     *
     * @var array
     */
    private $_paths;

    /**
     * @var string
     */
    private $_namespace_separator = '\\';

	/**
	 * Directory to load from class
	 */
    private $_class_dir = 'classes';

    /**
     * Creates a new <tt>Autoload</tt> that loads classes of the
     * specified namespace.
     *
     * @param string $ns The namespace to use.
     */
    public function __construct($paths = NULL, $class_dir = NULL)
    {
        $this->_namespace 	= $ns;
        $this->_paths 		= (is_array($paths)) ? $paths : array($paths);
        $this->_class_dir	= ($class_dir) ? $class_dir : $this->_class_dir;
    }

    /**
     * Sets the namespace separator used by classes in the namespace of this class loader.
     *
     * @param string $sep The separator to use.
     */
    public function set_namespace_separator($sep)
    {
        $this->_namespace_separator = $sep;
    }

    /**
     * Gets the namespace seperator used by classes in the namespace of this class loader.
     *
     * @return void
     */
    public function get_namespace_separator()
    {
        return $this->_namespace_separator;
    }

    /**
     * Sets the base include path for all class files in the namespace of this class loader.
     *
     * @param string|array $paths
     */
    public function set_paths($paths)
    {
		$paths = (is_array($paths)) ? $paths : array($paths);
        $this->_paths = $paths;
    }

    /**
     * Gets the base include path for all class files in the namespace of this class loader.
     *
     * @return string $paths
     */
    public function get_paths()
    {
        return $this->_paths;
    }

    /**
     * Add the base include path for all class files in the namespace of this class loader.
     *
     * @param string|array $paths
     */
    public function add_paths($paths)
    {
		$paths = (is_array($paths)) ? $paths : array($paths);
        $this->_paths = array_merge($this->_paths, $paths);
    }

    /**
     * Add path to top array
     *
     * @param string|array $paths
     */
    public function add_paths_before($paths)
    {
		$paths = (is_array($paths)) ? $paths : array($paths);
        $this->_paths = array_merge($paths, $this->_paths);
    }

    /**
     * Add path to end array.
     * This is alias of function add_paths()
     *
     * @param string|array $paths
     */
    public function add_paths_after($paths)
    {
        $this->add_paths($paths);
    }

    /**
     * Sets the file extension of class files in the namespace of this class loader.
     *
     * @param string $file_extention
     */
    public function set_file_extention($file_extention)
    {
        $this->_file_extension = $file_extention;
    }

    /**
     * Gets the file extension of class files in the namespace of this class loader.
     *
     * @return string $file_extention
     */
    public function get_file_extention()
    {
        return $this->_file_extension;
    }

    /**
     * Installs this class loader on the SPL autoload stack.
     */
    public function register()
    {
        spl_autoload_register(array($this, 'load_class'));
    }

    /**
     * Uninstalls this class loader from the SPL autoloader stack.
     */
    public function unregister()
    {
        spl_autoload_unregister(array($this, 'load_class'));
    }

    /**
     * Loads the given class or interface.
     *
     * @param string $class_name The name of the class to load.
     * @return void
     */
    public function load_class($class_name)
    {
		$class_name = ltrim($class_name, $this->_namespace_separator);
		$file_name 	= '';
		$namespace 	= '';

		if (($last_namespace_position = strripos($class_name, $this->_namespace_separator)) !== FALSE)
		{
			$namespace 	= substr($class_name, 0, $last_namespace_position);
			$class_name = substr($class_name, $last_namespace_position + 1);
			$file_name 	= str_replace($this->_namespace_separator, DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
		}

		$file_name .= str_replace('_', DIRECTORY_SEPARATOR, $class_name) . $this->_file_extension;

		if ($path = $this->find_class($file_name))
		{
			// Load the class file
			require $path;

			// Class has been found
			return TRUE;
		}

		// Class is not in the filesystem
        return FALSE;
    }


	/**
	 * Find class
	 *
	 * @param string $file_name
	 * @return boolean|string filepath
	 */
    public function find_class($file_name)
    {
		$file_name = DIRECTORY_SEPARATOR.$this->_class_dir.DIRECTORY_SEPARATOR.$file_name;
echo $file_name;
		foreach($this->_paths as $path)
		{
			if (is_file($path.$file_name))
			{
				return $path.$file_name;
			}
		}

		return FALSE;
	}
}
