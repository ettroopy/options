<?php namespace Troopy\Options;

class Options {

	protected $cache = array();

	function __construct() {}

	/**
 	 * Returns the value of the key stored in the database.
 	 *
 	 * @return mixed
 	 */
	public function get($key, $arrayItem = '')
	{
		// Checking first for cache value. In case it exists, returns it. This will
		// avoid making queries to the database. Just checking later if the data
		// stored is serialized or not. Once done returns the value requested
		if (isset($this->cache[$key]))
			return $this->cache[$key];

		$value = $this->parseValue(OptionsModel::find($key)->value);

		if ( ! empty($arrayItem) && is_array($value))
			return $value[$arrayItem];

		return $value;
	}

 	/**
 	 * Stores a given value in a particular key.
 	 *
 	 * @return mixed
 	 */
	public function set($key, $value)
	{
		// First of everything is to store the data in the cache variable to be able
		// to return it in the current sequence. Just later creates the new key in
		// the database if doesn't exists. Serialize data if is array and save.
		$this->cache[$key] = $value;

		$opt = OptionsModel::find($key);

		if ( ! $opt)
			$opt = new OptionsModel(array('key' => $key));

		if (is_array($value))
			$value = serialize($value);

		$opt->value = $value;

		return $opt->save();
	}

	/**
 	 * Change the key to a new one.
	 *
 	 * @param $key Key to be changed.
 	 * @param $newKey New key name.
 	 * @return void
 	 */
	public function changeKey($key, $newKey)
	{
		$opt = OptionsModel::find($key)->key = $newKey;
		$opt->save();
	}

	/**
 	 * Delete a given key.
 	 *
 	 * @return void
 	 */
	public function destroy($key)
	{
		return OptionsModel::destroy($key);
	}

	/**
	 * Parses the string, if it's serialized, returns it unserialized.
	 * otherwise just returns the value itself.
	 *
	 * @param string $value
	 * @return string
	 */
	public function parseValue($value)
	{
		if ($result = $this->isSerialized($value))
			return $return;

		return $value;
	}

	/**
	 * Check if the string is serialized
	 *
	 * @param string $value
	 * @return boolean
	 */
	public function isSerialized($value) {
		$serial = @unserialize($string);

	    return ($serial !== FALSE) ? $serial : FALSE;
	}
}