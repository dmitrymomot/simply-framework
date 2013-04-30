<?php

namespace Helpers;

/**
 * View fragment caching. This is primarily used to cache small parts of a view
 * that rarely change. For instance, you may want to cache the footer of your
 * template because it has very little dynamic content. Or you could cache a
 * user profile page and delete the fragment when the user updates.
 *
 * For obvious reasons, fragment caching should not be applied to any
 * content that contains forms.
 */
class Fragment {

	/**
	 * @var  integer  default number of seconds to cache for
	 */
	public static $lifetime = 30;

	/**
	 * @var  boolean  use multilingual fragment support?
	 */
	public static $i18n = FALSE;

	/**
	 * @var  array  list of buffer => cache key
	 */
	protected static $_caches = array();

	/**
	 * Generate the cache key name for a fragment.
	 *
	 *     $key = \Helpers\Fragment::_cache_key('footer', TRUE);
	 *
	 * @param   string  $name   fragment name
	 * @param   boolean $i18n   multilingual fragment support
	 * @return  string
	 * @uses    \Core\I18n::lang
	 * @since   3.0.4
	 */
	protected static function _cache_key($name, $i18n = NULL)
	{
		if ($i18n === NULL)
		{
			// Use the default setting
			$i18n = static::$i18n;
		}

		// Language prefix for cache key
		$i18n = ($i18n === TRUE) ? \Core\I18n::lang() : '';

		// Note: $i18n and $name need to be delimited to prevent naming collisions
		return '\Helpers\Fragment::cache('.$i18n.'+'.$name.')';
	}

	/**
	 * Load a fragment from cache and display it. Multiple fragments can
	 * be nested with different life times.
	 *
	 *     if ( ! \Helpers\Fragment::load('footer')) {
	 *         // Anything that is echo'ed here will be saved
	 *         \Helpers\Fragment::save();
	 *     }
	 *
	 * @param   string  $name       fragment name
	 * @param   integer $lifetime   fragment cache lifetime
	 * @param   boolean $i18n       multilingual fragment support
	 * @return  boolean
	 */
	public static function load($name, $lifetime = NULL, $i18n = NULL)
	{
		// Set the cache lifetime
		$lifetime = ($lifetime === NULL) ? static::$lifetime : (int) $lifetime;

		// Get the cache key name
		$cache_key = static::_cache_key($name, $i18n);

		if ($fragment = \Simply::cache($cache_key, NULL, $lifetime))
		{
			// Display the cached fragment now
			echo $fragment;

			return TRUE;
		}
		else
		{
			// Start the output buffer
			ob_start();

			// Store the cache key by the buffer level
			static::$_caches[ob_get_level()] = $cache_key;

			return FALSE;
		}
	}

	/**
	 * Saves the currently open fragment in the cache.
	 *
	 *     \Helpers\Fragment::save();
	 *
	 * @return  void
	 */
	public static function save()
	{
		// Get the buffer level
		$level = ob_get_level();

		if (isset(static::$_caches[$level]))
		{
			// Get the cache key based on the level
			$cache_key = static::$_caches[$level];

			// Delete the cache key, we don't need it anymore
			unset(static::$_caches[$level]);

			// Get the output buffer and display it at the same time
			$fragment = ob_get_flush();

			// Cache the fragment
			\Simply::cache($cache_key, $fragment);
		}
	}

	/**
	 * Delete a cached fragment.
	 *
	 *     \Helpers\Fragment::delete($key);
	 *
	 * @param   string  $name   fragment name
	 * @param   boolean $i18n   multilingual fragment support
	 * @return  void
	 */
	public static function delete($name, $i18n = NULL)
	{
		// Invalid the cache
		\Simply::cache(static::_cache_key($name, $i18n), NULL, -3600);
	}
}
