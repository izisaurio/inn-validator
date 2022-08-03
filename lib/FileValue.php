<?php

namespace Inn\Validator;

/**
 * Array with file info validate
 *
 * @author	izisaurio
 * @version	1
 */
class FileValue
{
	/**
	 * File array to validate
	 *
	 * @access	private
	 * @var		array
	 */
	private $file;

	/**
	 * Value name|label to use in errors
	 *
	 * @access	private
	 * @var		string
	 */
	private $name;

	/**
	 * Json with error messages
	 *
	 * @access	public
	 * @var		Json
	 */
	public $messages;

	/**
	 * Errors found on value
	 *
	 * @access	private
	 * @var		array
	 */
	private $errors = [];

	/**
	 * Has the current validation methods by name
	 *
	 * @access	public
	 * @var		array
	 */
	public $methods;

	/**
	 * Construct
	 *
	 * Sets value to validate
	 *
	 * @access	public
	 * @param	array	$file		File array to validate
	 * @param	string	$name		Value name|label
	 * @param	array	$messages	Errors array messages
	 */
	public function __construct(array $file, $name, array $messages = null)
	{
		$this->file = $file;
		$this->name = $name;
		$this->messages = $messages;
		$this->methods = \array_diff(\get_class_methods($this), [
			'__construct',
			'isUploaded',
			'validate',
			'getErrors',
			'addError',
		]);
	}

	/**
	 * Checks uploaded file
	 *
	 * @access	public
	 * @return	DataValue
	 */
	public function isUploaded()
	{
		return !isset($this->file['error'])
			? false
			: $this->file['error'] !== UPLOAD_ERR_NO_FILE;
	}

	/**
	 * Validates required file
	 *
	 * @access	public
	 * @return	DataValue
	 */
	public function isOk()
	{
		if (!isset($this->file['error'])) {
			$this->addError('isRequired', [$this->name]);
			return $this;
		}
		if ($this->file['error'] !== UPLOAD_ERR_OK) {
			$this->addError('isOk:' . $this->file['error'], [$this->name]);
		}
		return $this;
	}

	/**
	 * Validates mime type
	 *
	 * @access	public
	 * @return	DataValue
	 */
	public function isMimeType(array $types)
	{
		if (
			!isset($this->file['type']) ||
			!\in_array($this->file['type'], $types)
		) {
			$this->addError('isMimeType', [$this->name]);
		}
		return $this;
	}

	/**
	 * Moves a file to server
	 *
	 * @access	public
	 * @return	DataValue
	 */
	public function move($path)
	{
		if (
			!isset($this->file['tmp_name']) ||
			\move_uploaded_file($this->file['tmp_name'], $path) === false
		) {
			$this->addError('move', [$this->name]);
		}
		return $this;
	}

	/**
	 * Returns the value validation
	 *
	 * @access	public
	 * @return	bool
	 */
	public function validate()
	{
		return empty($this->errors);
	}

	/**
	 * Returns the value validation errors
	 *
	 * @access	public
	 * @return	array
	 */
	public function getErrors()
	{
		return $this->errors;
	}

	/**
	 * Adds a message to the error collection
	 *
	 * @access	private
	 * @param	string	$key	Key of error file
	 * @param	array	$attrs	Atrributes of message
	 */
	private function addError($key, array $attrs = [])
	{
		if (isset($this->messages)) {
			$this->errors[] = \vsprintf($this->messages[$key], $attrs);
			return;
		}
		$attr = \join(', ', $attrs);
		$this->errors[] = "{$attr} {$key}";
	}
}
