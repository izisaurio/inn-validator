<?php

namespace Inn\Validator;

use \DateTime;

/**
 * Single value to validate
 *
 * @author	izisaurio
 * @version	1
 */
class DataValue
{
	/**
	 * Value to validate
	 *
	 * @access	private
	 * @var		mixed
	 */
	private $value;

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
	 * @param	mixed	$value		Value to validate
	 * @param	string	$name		Value name|label
	 * @param	array	$messages	Errors array messages
	 */
	public function __construct($value, $name, array $messages = null)
	{
		$this->value = \is_array($value) ? $value : \trim($value);
		$this->name = $name;
		$this->messages = $messages;
		$this->methods = \array_diff(\get_class_methods($this), [
			'__construct',
			'validate',
			'getErrors',
			'addError',
		]);
	}

	/**
	 * Validates required value
	 *
	 * @access	public
	 * @return	DataValue
	 */
	public function isRequired()
	{
		if ($this->value == '') {
			$this->addError('isRequired', [$this->name]);
		}
		return $this;
	}

	/**
	 * Validates int number
	 *
	 * @access	public
	 * @return	DataValue
	 */
	public function isInt()
	{
		if (!\ctype_digit($this->value) && !\is_int($this->value)) {
			$this->addError('isInt', [$this->name]);
		}
		return $this;
	}

	/**
	 * Validates numeric value
	 *
	 * @access	public
	 * @return	DataValue
	 */
	public function isNumeric()
	{
		if (!\is_numeric($this->value)) {
			$this->addError('isNumeric', [$this->name]);
		}
		return $this;
	}

	/**
	 * Validates decimal value
	 *
	 * @access	public
	 * @return	DataValue
	 */
	public function isDecimal()
	{
		if (!\is_numeric($this->value)) {
			$this->addError('isDecimal', [$this->name]);
		}
		return $this;
	}

	/**
	 * Validates text with safe values
	 *
	 * @access	public
	 * @return	DataValue
	 */
	public function isSafeText()
	{
		if (
			!\preg_match(
				"#^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑäëïöüÄËÏÖÜ’ ?!%\+\-,\.;\$¿¡=\:´_\/\\\@\(\)\#'\*|\r\n]+$#",
				$this->value
			)
		) {
			$this->addError('isSafeText', [$this->name]);
		}
		return $this;
	}

	/**
	 * Validates value is datetime
	 *
	 * @access	public
	 * @return	DataValue
	 */
	public function isDatetime()
	{
		if (
			!\preg_match(
				'#^([123]\d{3}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01]))(\s)(?:([01]?\d|2[0-3]):([0-5]?\d):)?([0-5]?\d)$#',
				$this->value
			)
		) {
			$this->addError('isDatetime', [$this->name]);
		}
		return $this;
	}

	/**
	 * Validates value is date
	 *
	 * @access	public
	 * @return	DataValue
	 */
	public function isDate()
	{
		if (
			!\preg_match(
				'#^([123]\d{3}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01]))$#',
				$this->value
			)
		) {
			$this->addError('isDate', [$this->name]);
		}
		return $this;
	}

	/**
	 * Validates value is time
	 *
	 * @access	public
	 * @return	DataValue
	 */
	public function isTime()
	{
		if (
			!\preg_match(
				'#^(?:([01]?\d|2[0-3]):([0-5]?\d):)?([0-5]?\d)$#',
				$this->value
			)
		) {
			$this->addError('isTime', [$this->name]);
		}
		return $this;
	}

	/**
	 * Validates value is boolean
	 *
	 * @access	public
	 * @return	DataValue
	 */
	public function isBool()
	{
		if (
			!in_array(
				$this->value,
				[true, false, 1, 0, 'yes', 'no', '1', '0'],
				true
			)
		) {
			$this->addError('isBool', [$this->name]);
		}
		return $this;
	}

	/**
	 * Validates value is email
	 *
	 * @access	public
	 * @return	DataValue
	 */
	public function isEmail()
	{
		if (\filter_var($this->value, FILTER_VALIDATE_EMAIL) === false) {
			$this->addError('isEmail', [$this->name]);
		}
		return $this;
	}

	/**
	 * Validates value is array
	 *
	 * @access	public
	 * @return	DataValue
	 */
	public function isArray()
	{
		if (!\is_array($this->value)) {
			$this->addError('isArray', [$this->name]);
		}
		return $this;
	}

	/**
	 * Validates value regext
	 *
	 * @access	public
	 * @param	string		$exp	Regex to compare
	 * @return	DataValue
	 */
	public function regex($exp)
	{
		if (!\preg_match($exp, $this->value)) {
			$this->addError('regex', [$this->name]);
		}
		return $this;
	}

	/**
	 * Validates value min length
	 *
	 * @access	public
	 * @param	int		$length		Min length value
	 * @return	DataValue
	 */
	public function minLength($length)
	{
		if (\strlen($this->value) < $length) {
			$this->addError('minLength', [$this->name, $length]);
		}
		return $this;
	}

	/**
	 * Validates value max length
	 *
	 * @access	public
	 * @param	int		$length		Max length value
	 * @return	DataValue
	 */
	public function maxLength($length)
	{
		if (\strlen($this->value) > $length) {
			$this->addError('maxLength', [$this->name, $length]);
		}
		return $this;
	}

	/**
	 * Validates value min length
	 *
	 * @access	public
	 * @param	int		$length		Min length value
	 * @return	DataValue
	 */
	public function mbMinLength($length)
	{
		if (\mb_strlen($this->value) < $length) {
			$this->addError('minLength', [$this->name, $length]);
		}
		return $this;
	}

	/**
	 * Validates value max length
	 *
	 * @access	public
	 * @param	int		$length		Max length value
	 * @return	DataValue
	 */
	public function mbMaxLength($length)
	{
		if (\mb_strlen($this->value) > $length) {
			$this->addError('maxLength', [$this->name, $length]);
		}
		return $this;
	}

	/**
	 * Validates value min
	 *
	 * @access	public
	 * @param	int		$length		Min value
	 * @return	DataValue
	 */
	public function min($length)
	{
		if ($this->value < $length) {
			$this->addError('min', [$this->name, $length]);
		}
		return $this;
	}

	/**
	 * Validates value max
	 *
	 * @access	public
	 * @param	int		$length		Max value
	 * @return	DataValue
	 */
	public function max($length)
	{
		if ($this->value > $length) {
			$this->addError('max', [$this->name, $length]);
		}
		return $this;
	}

	/**
	 * Validates value compare to other
	 *
	 * @access	public
	 * @param	mixed		$to		Value to compare to
	 * * @param	mixed		$toName	Value to compare to name
	 * @return	DataValue
	 */
	public function greater($to, $toName)
	{
		if ($this->value < $to) {
			$this->addError('greater', [$this->name, $toName]);
		}
		return $this;
	}

	/**
	 * Validates datetime compare to other
	 *
	 * @access	public
	 * @param	mixed		$to		Value to compare to
	 * @param	mixed		$toName	Value to compare to name
	 * @param	string		$format	Dates format
	 * @return	DataValue
	 */
	public function greaterDatetime($to, $toName, $format = 'Y-m-d H:i:s')
	{
		if (
			DateTime::createFromFormat($format, $this->value) <=
			DateTime::createFromFormat($format, $to)
		) {
			$this->addError('greaterDatetime', [$this->name, $toName]);
		}
		return $this;
	}

	/**
	 * Validates date compare to other
	 *
	 * @access	public
	 * @param	mixed		$to		Value to compare to
	 * @param	mixed		$toName	Value to compare to name
	 * @param	string		$format	Dates format
	 * @return	DataValue
	 */
	public function greaterDate($to, $toName, $format = 'Y-m-d')
	{
		if (
			DateTime::createFromFormat($format, $this->value) <=
			DateTime::createFromFormat($format, $to)
		) {
			$this->addError('greaterDate', [$this->name, $toName]);
		}
		return $this;
	}

	/**
	 * Validates value compare to other
	 *
	 * @access	public
	 * @param	mixed		$to		Value to compare to
	 * * @param	mixed		$toName	Value to compare to name
	 * @return	DataValue
	 */
	public function less($to, $toName)
	{
		if ($this->value > $to) {
			$this->addError('less', [$this->name, $toName]);
		}
		return $this;
	}

	/**
	 * Validates datetime compare to other
	 *
	 * @access	public
	 * @param	mixed		$to		Value to compare to
	 * @param	mixed		$toName	Value to compare to name
	 * @param	string		$format	Dates format
	 * @return	DataValue
	 */
	public function lessDatetime($to, $toName, $format = 'Y-m-d H:i:s')
	{
		if (
			DateTime::createFromFormat($format, $this->value) <=
			DateTime::createFromFormat($format, $to)
		) {
			$this->addError('lessDatetime', [$this->name, $toName]);
		}
		return $this;
	}

	/**
	 * Validates date compare to other
	 *
	 * @access	public
	 * @param	mixed		$to		Value to compare to
	 * @param	mixed		$toName	Value to compare to name
	 * @param	string		$format	Dates format
	 * @return	DataValue
	 */
	public function lessDate($to, $toName, $format = 'Y-m-d')
	{
		if (
			DateTime::createFromFormat($format, $this->value) <=
			DateTime::createFromFormat($format, $to)
		) {
			$this->addError('lessDate', [$this->name, $toName]);
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
