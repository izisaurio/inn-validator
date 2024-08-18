<?php

namespace Inn\Validator;

use JsonToArray\Json;

/**
 * Object values validator class
 *
 * @author	izisaurio
 * @version	1
 */
class DataObject
{
	/**
	 * Object|array to validate
	 *
	 * @access	private
	 * @var		mixed
	 */
	private $data;

	/**
	 * Collection of rules for this object
	 *
	 * @access	private
	 * @var		array
	 */
	private $rules;

	/**
	 * Json file containing error messages
	 *
	 * @access	public
	 * @var		Json
	 */
	public $messages;

	/**
	 * Language for multilanguage labels, label must be an array
	 *
	 * @access	private
	 * @var		string
	 */
	private $langKey;

	/**
	 * Errors found on object
	 *
	 * @access	private
	 * @var		array
	 */
	private $errors = [];

	/**
	 * Construct
	 *
	 * Sets json file with messages
	 *
	 * @access	public
	 * @param	mixed	$data		Data object to validate
	 * @param	array	$rules		Validation rules
	 * @param	array	$messages	Array error messages
	 * @param	string	$langKey	Language key for multilanguage labels
	 */
	public function __construct(
		$data,
		array $rules,
		array $messages = null,
		$langKey = null
	) {
		$this->data = $data;
		$this->rules = $rules;
		if (isset($messages)) {
			$this->messages = $messages;
		}
		$this->langKey = $langKey;
	}

	/**
	 * Gets the default messages array
	 * 
	 * @static
	 * @access	public
	 * @return	array
	 */
	public static function getDefaultMessages() {
		return require __DIR__ . '/errors.php';
	}

	/**
	 * Validates an object wth the given rules
	 *
	 * @access	public
	 * @param	object|array	$object		Object to validate
	 * @param	array			$rules		Rules of thje object properties
	 * @return	bool
	 */
	public function validate()
	{
		$data = (array) $this->data;
		foreach ($this->rules as $key => $ruleSet) {
			if (isset($ruleSet['isNullable']) && !isset($data[$key])) {
				continue;
			}
			$value = isset($data[$key]) ? $data[$key] : '';
			$dataValue = new DataValue(
				$value,
				$this->getLabel($key),
				$this->messages
			);
			$dataValue->isRequired();
			if (!$dataValue->validate()) {
				$this->addErrors($dataValue->getErrors());
				continue;
			}
			foreach ($ruleSet as $rule => $param) {
				if (!\in_array($rule, $dataValue->methods)) {
					continue;
				}
				$params = [$param];
				if (\is_string($param) && $param[0] === '@') {
					$subKey = \ltrim($param, '@');
					$params = [$data[$subKey], $this->getLabel($subKey)];
				}
				\call_user_func_array([$dataValue, $rule], $params);
			}
			if (!$dataValue->validate()) {
				$this->addErrors($dataValue->getErrors());
			}
		}
		return empty($this->errors);
	}

	/**
	 * Gets a label from the rules set
	 *
	 * @access	private
	 * @param	string	$key	Rules set key
	 * @return	string
	 */
	private function getLabel($key)
	{
		if (!isset($this->rules[$key]) || !isset($this->rules[$key]['label'])) {
			return $key;
		}
		if (!is_array($this->rules[$key]['label'])) {
			return $this->rules[$key]['label'];
		}
		return isset($this->langKey) &&
			isset($this->rules[$key]['label'][$this->langKey])
			? $this->rules[$key]['label'][$this->langKey]
			: $key;
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
	 * Pushes errors in error array
	 *
	 * @access	private
	 * @param	array	$errors		Values to push
	 */
	private function addErrors(array $errors)
	{
		$this->errors = \array_merge($this->errors, $errors);
	}
}
