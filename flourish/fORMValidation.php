<?php
/**
 * Handles validation for fActiveRecord classes
 * 
 * @copyright  Copyright (c) 2007-2009 Will Bond, others
 * @author     Will Bond [wb] <will@flourishlib.com>
 * @author     Jeff Turcotte [jt] <jeff.turcotte@gmail.com>
 * @license    http://flourishlib.com/license
 * 
 * @package    Flourish
 * @link       http://flourishlib.com/fORMValidation
 * 
 * @version    1.0.0b18
 * @changes    1.0.0b18  Fixed ::checkOnlyOneRule() and ::checkOneOrMoreRule() to consider blank strings as NULL [wb, 2009-08-21]
 * @changes    1.0.0b17  Added @internal methods ::removeStringReplacement() and ::removeRegexReplacement() [wb, 2009-07-29]
 * @changes    1.0.0b16  Backwards Compatibility Break - renamed ::addConditionalValidationRule() to ::addConditionalRule(), ::addManyToManyValidationRule() to ::addManyToManyRule(), ::addOneOrMoreValidationRule() to ::addOneOrMoreRule(), ::addOneToManyValidationRule() to ::addOneToManyRule(), ::addOnlyOneValidationRule() to ::addOnlyOneRule(), ::addValidValuesValidationRule() to ::addValidValuesRule() [wb, 2009-07-13]
 * @changes    1.0.0b15  Added ::addValidValuesValidationRule() [wb/jt, 2009-07-13]
 * @changes    1.0.0b14  Added ::addStringReplacement() and ::addRegexReplacement() for simple validation message modification [wb, 2009-07-01]
 * @changes    1.0.0b13  Changed ::reorderMessages() to compare string in a case-insensitive manner [wb, 2009-06-30]
 * @changes    1.0.0b12  Updated ::addConditionalValidationRule() to allow any number of `$main_columns`, and if any of those have a matching value, the condtional columns will be required [wb, 2009-06-30]
 * @changes    1.0.0b11  Fixed a couple of bugs with validating related records [wb, 2009-06-26]
 * @changes    1.0.0b10  Fixed UNIQUE constraint checking so it is only done once per constraint, fixed some UTF-8 case sensitivity issues [wb, 2009-06-17]
 * @changes    1.0.0b9   Updated code for new fORM API [wb, 2009-06-15]
 * @changes    1.0.0b8   Updated code to use new fValidationException::formatField() method [wb, 2009-06-04]  
 * @changes    1.0.0b7   Updated ::validateRelated() to use new fORMRelated::validate() method and ::checkRelatedOneOrMoreRule() to use new `$related_records` structure [wb, 2009-06-02]
 * @changes    1.0.0b6   Changed date/time/timestamp checking from `strtotime()` to fDate/fTime/fTimestamp for better localization support [wb, 2009-06-01]
 * @changes    1.0.0b5   Fixed a bug in ::checkOnlyOneRule() where no values would not be flagged as an error [wb, 2009-04-23]
 * @changes    1.0.0b4   Fixed a bug in ::checkUniqueConstraints() related to case-insensitive columns [wb, 2009-02-15]
 * @changes    1.0.0b3   Implemented proper fix for ::addManyToManyValidationRule() [wb, 2008-12-12]
 * @changes    1.0.0b2   Fixed a bug with ::addManyToManyValidationRule() [wb, 2008-12-08]
 * @changes    1.0.0b    The initial implementation [wb, 2007-08-04]
 */
class fORMValidation
{
	// The following constants allow for nice looking callbacks to static methods
	const addConditionalRule       = 'fORMValidation::addConditionalRule';
	const addManyToManyRule        = 'fORMValidation::addManyToManyRule';
	const addOneOrMoreRule         = 'fORMValidation::addOneOrMoreRule';
	const addOneToManyRule         = 'fORMValidation::addOneToManyRule';
	const addOnlyOneRule           = 'fORMValidation::addOnlyOneRule';
	const addRegexReplacement      = 'fORMValidation::addRegexReplacement';
	const addStringReplacement     = 'fORMValidation::addStringReplacement';
	const addValidValuesRule       = 'fORMValidation::addValidValuesRule';
	const inspect                  = 'fORMValidation::inspect';
	const removeStringReplacement  = 'fORMValidation::removeStringReplacement';
	const removeRegexReplacement   = 'fORMValidation::removeRegexReplacement';
	const reorderMessages          = 'fORMValidation::reorderMessages';
	const replaceMessages          = 'fORMValidation::replaceMessages';
	const reset                    = 'fORMValidation::reset';
	const setColumnCaseInsensitive = 'fORMValidation::setColumnCaseInsensitive';
	const setMessageOrder          = 'fORMValidation::setMessageOrder';
	const validate                 = 'fORMValidation::validate';
	const validateRelated          = 'fORMValidation::validateRelated';	
	
	
	/**
	 * Columns that should be treated as case insensitive when checking uniqueness
	 * 
	 * @var array
	 */
	static private $case_insensitive_columns = array();
	
	/**
	 * Conditional rules
	 * 
	 * @var array
	 */
	static private $conditional_rules = array();
	
	/**
	 * Ordering rules for messages
	 * 
	 * @var array
	 */
	static private $message_orders = array();
	
	/**
	 * One or more rules
	 * 
	 * @var array
	 */
	static private $one_or_more_rules = array();
	
	/**
	 * Only one rules
	 * 
	 * @var array
	 */
	static private $only_one_rules = array();
	
	/**
	 * Regular expression replacements performed on each message
	 * 
	 * @var array
	 */
	static private $regex_replacements = array();
	
	/**
	 * Rules that require at least one or more *-to-many related records to be associated
	 * 
	 * @var array
	 */
	static private $related_one_or_more_rules = array();
	
	/**
	 * String replacements performed on each message
	 * 
	 * @var array
	 */
	static private $string_replacements = array();
	
	/**
	 * Valid values rules
	 * 
	 * @var array
	 */
	static private $valid_values_rules = array();
	
	
	/**
	 * Adds a conditional rule
	 * 
	 * If a non-empty value is found in one of the `$main_columns`, or if
	 * specified, a value from the `$conditional_values` array, all of the
	 * `$conditional_columns` will also be required to have a value.
	 *
	 * @param  mixed         $class                The class name or instance of the class this rule applies to
	 * @param  string|array  $main_columns         The column(s) to check for a value
	 * @param  mixed         $conditional_values   If `NULL`, any value in the main column will trigger the conditional column(s), otherwise the value must match this scalar value or be present in the array of values
	 * @param  string|array  $conditional_columns  The column(s) that are to be required
	 * @return void
	 */
	static public function addConditionalRule($class, $main_columns, $conditional_values, $conditional_columns)
	{
		$class = fORM::getClass($class);
		
		if (!isset(self::$conditional_rules[$class])) {
			self::$conditional_rules[$class] = array();
		}
		
		settype($main_columns, 'array');
		settype($conditional_columns, 'array');
		if ($conditional_values !== NULL) {
			settype($conditional_values, 'array');
		}	
		
		$rule = array();
		$rule['main_columns']        = $main_columns;
		$rule['conditional_values']  = $conditional_values;
		$rule['conditional_columns'] = $conditional_columns;
		
		self::$conditional_rules[$class][] = $rule;
	}
	
	
	/**
	 * Add a many-to-many rule that requires at least one related record is associated with the current record
	 *
	 * @param  mixed  $class          The class name or instance of the class to add the rule for
	 * @param  string $related_class  The name of the related class
	 * @param  string $route          The route to the related class
	 * @return void
	 */
	static public function addManyToManyRule($class, $related_class, $route=NULL)
	{
		$class = fORM::getClass($class);
		
		if (!isset(self::$related_one_or_more_rules[$class])) {
			self::$related_one_or_more_rules[$class] = array();
		}
		
		if (!isset(self::$related_one_or_more_rules[$class][$related_class])) {
			self::$related_one_or_more_rules[$class][$related_class] = array();
		}
		
		$route = fORMSchema::getRouteName(
			fORM::tablize($class),
			fORM::tablize($related_class),
			$route,
			'many-to-many'
		);
		
		self::$related_one_or_more_rules[$class][$related_class][$route] = TRUE;
	}
	
	
	/**
	 * Adds a one-or-more rule that requires at least one of the columns specified has a value
	 *
	 * @param  mixed $class    The class name or instance of the class the columns exists in
	 * @param  array $columns  The columns to check
	 * @return void
	 */
	static public function addOneOrMoreRule($class, $columns)
	{
		$class = fORM::getClass($class);
		
		settype($columns, 'array');
		
		if (!isset(self::$one_or_more_rules[$class])) {
			self::$one_or_more_rules[$class] = array();
		}
		
		$rule = array();
		$rule['columns'] = $columns;
		
		self::$one_or_more_rules[$class][] = $rule;
	}
	
	
	/**
	 * Add a one-to-many rule that requires at least one related record is associated with the current record
	 *
	 * @param  mixed  $class          The class name or instance of the class to add the rule for
	 * @param  string $related_class  The name of the related class
	 * @param  string $route          The route to the related class
	 * @return void
	 */
	static public function addOneToManyRule($class, $related_class, $route=NULL)
	{
		$class = fORM::getClass($class);
		
		if (!isset(self::$related_one_or_more_rules[$class])) {
			self::$related_one_or_more_rules[$class] = array();
		}
		
		if (!isset(self::$related_one_or_more_rules[$class][$related_class])) {
			self::$related_one_or_more_rules[$class][$related_class] = array();
		}
		
		$route = fORMSchema::getRouteName(
			fORM::tablize($class),
			fORM::tablize($related_class),
			$route,
			'one-to-many'
		);
		
		self::$related_one_or_more_rules[$class][$related_class][$route] = TRUE;
	}
	
	
	/**
	 * Add an only-one rule that requires exactly one of the columns must have a value
	 *
	 * @param  mixed $class    The class name or instance of the class the columns exists in
	 * @param  array $columns  The columns to check
	 * @return void
	 */
	static public function addOnlyOneRule($class, $columns)
	{
		$class = fORM::getClass($class);
		
		settype($columns, 'array');
		
		if (!isset(self::$only_one_rules[$class])) {
			self::$only_one_rules[$class] = array();
		}
		
		$rule = array();
		$rule['columns'] = $columns;
		
		self::$only_one_rules[$class][] = $rule;
	}
	
	
	/**
	 * Adds a call to [http://php.net/oreg_replace `preg_replace()`] for each message
	 * 
	 * Regex replacement is done after the `post::validate()` hook, and right
	 * before the messages are reordered.
	 * 
	 * If a message is an empty string after replacement, it will be
	 * removed from the list of messages.
	 * 
	 * @param  mixed  $class    The class name or instance of the class the columns exists in
	 * @param  string $search   The PCRE regex to search for - see http://php.net/pcre for details
	 * @param  string $replace  The string to replace with - all $ and \ are used in back references and must be escaped with a \ when meant literally
	 * @return void
	 */
	static public function addRegexReplacement($class, $search, $replace)
	{
		$class = fORM::getClass($class);
		
		if (!isset(self::$regex_replacements[$class])) {
			self::$regex_replacements[$class] = array(
				'search'  => array(),
				'replace' => array()
			);
		}
		
		self::$regex_replacements[$class]['search'][]  = $search;
		self::$regex_replacements[$class]['replace'][] = $replace;
	}
	
	
	/**
	 * Adds a call to [http://php.net/str_replace `str_replace()`] for each message
	 * 
	 * String replacement is done after the `post::validate()` hook, and right
	 * before the messages are reordered.
	 * 
	 * If a message is an empty string after replacement, it will be
	 * removed from the list of messages.
	 * 
	 * @param  mixed  $class    The class name or instance of the class the columns exists in
	 * @param  string $search   The string to search for
	 * @param  string $replace  The string to replace with
	 * @return void
	 */
	static public function addStringReplacement($class, $search, $replace)
	{
		$class = fORM::getClass($class);
		
		if (!isset(self::$string_replacements[$class])) {
			self::$string_replacements[$class] = array(
				'search'  => array(),
				'replace' => array()
			);
		}
		
		self::$string_replacements[$class]['search'][]  = $search;
		self::$string_replacements[$class]['replace'][] = $replace;
	}
	
	
	/**
	 * Restricts a column to having only a value from the list of valid values
	 * 
	 * Please note that `NULL` values are always allowed, even if not listed in
	 * the `$valid_values` array, if the column is not set as `NOT NULL`.
	 * 
	 * This functionality can also be accomplished by added a `CHECK` constraint
	 * on the column in the database, or using a MySQL `ENUM` data type.
	 *
	 * @param  mixed   $class         The class name or instance of the class this rule applies to
	 * @param  string  $column        The column to validate
	 * @param  array   $valid_values  The valid values to check - `NULL` values are always allows if the column is not set to `NOT NULL`
	 * @return void
	 */
	static public function addValidValuesRule($class, $column, $valid_values)
	{
		$class = fORM::getClass($class);
		
		if (!isset(self::$valid_values_rules[$class])) {
			self::$valid_values_rules[$class] = array();
		}
		
		settype($valid_values, 'array');
		
		self::$valid_values_rules[$class][$column] = $valid_values;
		
		fORM::registerInspectCallback($class, $column, self::inspect);
	}
	
	
	/**
	 * Validates a value against the database schema
	 *
	 * @param  fActiveRecord  $object       The instance of the class the column is part of
	 * @param  string         $column       The column to check
	 * @param  array          &$values      An associative array of all values going into the row (needs all for multi-field unique constraint checking)
	 * @param  array          &$old_values  The old values from the record
	 * @return string  An error message for the column specified
	 */
	static private function checkAgainstSchema($object, $column, &$values, &$old_values)
	{
		$class = get_class($object);
		$table = fORM::tablize($class);
		
		$column_info = fORMSchema::retrieve()->getColumnInfo($table, $column);
		// Make sure a value is provided for required columns
		if ($values[$column] === NULL && $column_info['not_null'] && $column_info['default'] === NULL && $column_info['auto_increment'] === FALSE) {
			return self::compose(
				'%sPlease enter a value',
				fValidationException::formatField(fORM::getColumnName($class, $column))
			);
		}
		
		$message = self::checkDataType($class, $column, $values[$column]);
		if ($message) { return $message; }
		
		// Make sure a valid value is chosen
		if (isset($column_info['valid_values']) && $values[$column] !== NULL && !in_array($values[$column], $column_info['valid_values'])) {
			return self::compose(
				'%1$sPlease choose from one of the following: %2$s',
				fValidationException::formatField(fORM::getColumnName($class, $column)),
				join(', ', $column_info['valid_values'])
			);
		}
		
		// Make sure the value isn't too long
		if ($column_info['type'] == 'varchar' && isset($column_info['max_length']) && $values[$column] !== NULL && is_string($values[$column]) && fUTF8::len($values[$column]) > $column_info['max_length']) {
			return self::compose(
				'%1$sPlease enter a value no longer than %2$s characters',
				fValidationException::formatField(fORM::getColumnName($class, $column)),
				$column_info['max_length']
			);
		}
		
		// Make sure the value is the proper length
		if ($column_info['type'] == 'char' && isset($column_info['max_length']) && $values[$column] !== NULL && is_string($values[$column]) && fUTF8::len($values[$column]) != $column_info['max_length']) {
			return self::compose(
				'%1$sPlease enter exactly %2$s characters',
				fValidationException::formatField(fORM::getColumnName($class, $column)),
				$column_info['max_length']
			);
		}
		
		$message = self::checkForeignKeyConstraints($class, $column, $values);
		if ($message) { return $message; }
	}
	
	
	/**
	 * Validates against a conditional rule
	 *
	 * @param  string $class                The class this rule applies to
	 * @param  array  &$values              An associative array of all values for the record
	 * @param  array  $main_columns         The columns to check for a value
	 * @param  array  $conditional_values   If `NULL`, any value in the main column will trigger the conditional columns, otherwise the value must match one of these
	 * @param  array  $conditional_columns  The columns that are to be required
	 * @return array  The error messages for the rule specified
	 */
	static private function checkConditionalRule($class, &$values, $main_columns, $conditional_values, $conditional_columns)
	{
		$check_for_missing_values = FALSE;
		
		foreach ($main_columns as $main_column) {
			$matches_conditional_value = $conditional_values !== NULL && in_array($values[$main_column], $conditional_values);
			$has_some_value            = $conditional_values === NULL && strlen((string) $values[$main_column]);
			if ($matches_conditional_value || $has_some_value) {
				$check_for_missing_values = TRUE;
				break;	
			}	
		}
		
		if (!$check_for_missing_values) {
			return;	
		}
		
		$messages = array();
		foreach ($conditional_columns as $conditional_column) {
			if ($values[$conditional_column] !== NULL) { continue; }
			$messages[] = self::compose(
				'%sPlease enter a value',
				fValidationException::formatField(fORM::getColumnName($class, $conditional_column))
			);
		}
		if ($messages) {
			return $messages;
		}
	}
	
	
	/**
	 * Validates a value against the database data type
	 *
	 * @param  string $class   The class the column is part of
	 * @param  string $column  The column to check
	 * @param  mixed  $value   The value to check
	 * @return string  An error message for the column specified
	 */
	static private function checkDataType($class, $column, $value)
	{
		$table       = fORM::tablize($class);
		$column_info = fORMSchema::retrieve()->getColumnInfo($table, $column);
		
		if ($value !== NULL) {
			switch ($column_info['type']) {
				case 'varchar':
				case 'char':
				case 'text':
				case 'blob':
					if (!is_string($value) && !is_numeric($value)) {
						return self::compose(
							'%sPlease enter a string',
							fValidationException::formatField(fORM::getColumnName($class, $column))
						);
					}
					break;
				case 'integer':
				case 'float':
					if (!is_numeric($value)) {
						return self::compose(
							'%sPlease enter a number',
							fValidationException::formatField(fORM::getColumnName($class, $column))
						);
					}
					break;
				case 'timestamp':
					try {
						new fTimestamp($value);	
					} catch (fValidationException $e) {
						return self::compose(
							'%sPlease enter a date/time',
							fValidationException::formatField(fORM::getColumnName($class, $column))
						);
					}
					break;
				case 'date':
					try {
						new fDate($value);	
					} catch (fValidationException $e) {
						return self::compose(
							'%sPlease enter a date',
							fValidationException::formatField(fORM::getColumnName($class, $column))
						);
					}
					break;
				case 'time':
					try {
						new fTime($value);	
					} catch (fValidationException $e) {
						return self::compose(
							'%sPlease enter a time',
							fValidationException::formatField(fORM::getColumnName($class, $column))
						);
					}
					break;
				
			}
		}
	}
	
	
	/**
	 * Validates values against foreign key constraints
	 *
	 * @param  string $class    The class to check the foreign keys for
	 * @param  string $column   The column to check
	 * @param  array  &$values  The values to check
	 * @return string  An error message for the column specified
	 */
	static private function checkForeignKeyConstraints($class, $column, &$values)
	{
		if ($values[$column] === NULL) {
			return;
		}
		
		$table        = fORM::tablize($class);
		$foreign_keys = fORMSchema::retrieve()->getKeys($table, 'foreign');
		
		foreach ($foreign_keys AS $foreign_key) {
			if ($foreign_key['column'] == $column) {
				try {
					$sql  = "SELECT " . $foreign_key['foreign_column'];
					$sql .= " FROM " . $foreign_key['foreign_table'];
					$sql .= " WHERE ";
					$sql .= $column . fORMDatabase::escapeBySchema($table, $column, $values[$column], '=');
					$sql  = str_replace('WHERE ' . $column, 'WHERE ' . $foreign_key['foreign_column'], $sql);
					
					$result = fORMDatabase::retrieve()->translatedQuery($sql);
					$result->tossIfNoRows();
				} catch (fNoRowsException $e) {
					return self::compose(
						'%sThe value specified is invalid',
						fValidationException::formatField(fORM::getColumnName($class, $column))
					);
				}
			}
		}
	}
	
	
	/**
	 * Validates against a one-or-more rule
	 *
	 * @param  string $class    The class the columns are part of
	 * @param  array  &$values  An associative array of all values for the record
	 * @param  array  $columns  The columns to check
	 * @return string  An error message for the rule
	 */
	static private function checkOneOrMoreRule($class, &$values, $columns)
	{
		settype($columns, 'array');
		
		$found_value = FALSE;
		foreach ($columns as $column) {
			if ($values[$column] !== NULL && $values[$column] !== '') {
				$found_value = TRUE;
			}
		}
		
		if (!$found_value) {
			$column_names = array();
			foreach ($columns as $column) {
				$column_names[] = fORM::getColumnName($class, $column);
			}
			return self::compose(
				'%sPlease enter a value for at least one',
				fValidationException::formatField(join(', ', $column_names))
			);
		}
	}
	
	
	/**
	 * Validates against an only-one rule
	 *
	 * @param  string $class    The class the columns are part of
	 * @param  array  &$values  An associative array of all values for the record
	 * @param  array  $columns  The columns to check
	 * @return string  An error message for the rule
	 */
	static private function checkOnlyOneRule($class, &$values, $columns)
	{
		settype($columns, 'array');
		
		$column_names = array();
		foreach ($columns as $column) {
			$column_names[] = fORM::getColumnName($class, $column);
		}
		
		$found_value = FALSE;
		foreach ($columns as $column) {
			if ($values[$column] !== NULL && $values[$column] !== '') {
				if ($found_value) {
					return self::compose(
						'%sPlease enter a value for only one',
						fValidationException::formatField(join(', ', $column_names))
					);
				}
				$found_value = TRUE;
			}
		}
		
		if (!$found_value) {
			return self::compose(
				'%sPlease enter a value for one',
				fValidationException::formatField(join(', ', $column_names))
			);	
		}	
	}
	
	
	/**
	 * Makes sure a record with the same primary keys is not already in the database
	 *
	 * @param  fActiveRecord  $object       The instance of the class to check
	 * @param  array          &$values      An associative array of all values going into the row (needs all for multi-field unique constraint checking)
	 * @param  array          &$old_values  The old values for the record
	 * @return string  An error message
	 */
	static private function checkPrimaryKeys($object, &$values, &$old_values)
	{
		$class = get_class($object);
		$table = fORM::tablize($class);
		
		$primary_keys = fORMSchema::retrieve()->getKeys($table, 'primary');
		$columns      = array();
		
		$found_value  = FALSE;
		foreach ($primary_keys as $primary_key) {
			$columns[] = fORM::getColumnName($class, $primary_key);
			if ($values[$primary_key]) {
				$found_value = TRUE;	
			}
		}
		
		if (!$found_value) {
			return;	
		}
		
		$different = FALSE;
		foreach ($primary_keys as $primary_key) {
			if (!fActiveRecord::hasOld($old_values, $primary_key)) {
				continue;	
			}
			$old_value = fActiveRecord::retrieveOld($old_values, $primary_key);
			$value     = $values[$primary_key];
			if (self::isCaseInsensitive($class, $primary_key) && self::stringlike($value) && self::stringlike($old_value)) {
				if (fUTF8::lower($value) != fUTF8::lower($old_value)) {
					$different = TRUE;
				}	
			} elseif ($old_value != $value) {
				$different = TRUE;	
			}
		}
		
		if (!$different) {
			return;	
		}
		
		try {
			$sql    = "SELECT " . join(', ', $primary_keys) . " FROM " . $table . " WHERE ";
			$conditions = array();
			foreach ($primary_keys as $primary_key) {
				if (self::isCaseInsensitive($class, $primary_key) && self::stringlike($values[$primary_key])) {
					$conditions[] = 'LOWER(' . $primary_key . ')' . fORMDatabase::escapeBySchema($table, $primary_key, fUTF8::lower($values[$primary_key]), '=');
				} else {
					$conditions[] = $primary_key . fORMDatabase::escapeBySchema($table, $primary_key, $values[$primary_key], '=');
				} 
			}
			$sql .= join(' AND ', $conditions);
			
			$result = fORMDatabase::retrieve()->translatedQuery($sql);
			$result->tossIfNoRows();
			
			return self::compose(
				'Another %1$s with the same %2$s already exists',
				fORM::getRecordName($class),
				fGrammar::joinArray($columns, 'and')
			);
			
		} catch (fNoRowsException $e) { }
	}
	
	
	/**
	 * Validates against a *-to-many one or more rule
	 *
	 * @param  fActiveRecord $object            The object being checked
	 * @param  array         &$values           The values for the object
	 * @param  array         &$related_records  The related records for the object
	 * @param  string        $related_class     The name of the related class
	 * @param  string        $route             The name of the route from the class to the related class
	 * @return string  An error message for the rule
	 */
	static private function checkRelatedOneOrMoreRule($object, &$values, &$related_records, $related_class, $route)
	{
		$related_table   = fORM::tablize($related_class);
		$class           = get_class($object);
		
		$exists          = $object->exists();
		$records_are_set = isset($related_records[$related_table][$route]);
		$has_records     = $records_are_set && $related_records[$related_table][$route]['count'];
		
		if ($exists && (!$records_are_set || $has_records)) {
			return;
		}
		
		if (!$exists && $has_records) {
			return;	
		}
		
		return self::compose(
			'%sPlease select at least one',
			fValidationException::formatField(fGrammar::pluralize(fORMRelated::getRelatedRecordName($class, $related_class, $route)))
		);
	}
	
	
	/**
	 * Validates values against unique constraints
	 *
	 * @param  fActiveRecord  $object       The instance of the class to check
	 * @param  array          &$values      The values to check
	 * @param  array          &$old_values  The old values for the record
	 * @return string  An error message for the unique constraints
	 */
	static private function checkUniqueConstraints($object, &$values, &$old_values)
	{
		$class = get_class($object);
		$table = fORM::tablize($class);
		
		$key_info = fORMSchema::retrieve()->getKeys($table);
		
		$primary_keys = $key_info['primary'];
		$unique_keys  = $key_info['unique'];
		
		foreach ($unique_keys AS $unique_columns) {
			settype($unique_columns, 'array');
			
			// NULL values are unique
			$found_not_null = FALSE;
			foreach ($unique_columns as $unique_column) {
				if ($values[$unique_column] !== NULL) {
					$found_not_null = TRUE;
				}
			}
			if (!$found_not_null) {
				continue;
			}
			
			$sql = "SELECT " . join(', ', $key_info['primary']) . " FROM " . $table . " WHERE ";
			$first = TRUE;
			foreach ($unique_columns as $unique_column) {
				if ($first) { $first = FALSE; } else { $sql .= " AND "; }
				$value = $values[$unique_column];
				if (self::isCaseInsensitive($class, $unique_column) && self::stringlike($value)) {
					$sql .= 'LOWER(' . $unique_column . ')' . fORMDatabase::escapeBySchema($table, $unique_column, fUTF8::lower($value), '=');
				} else {
					$sql .= $unique_column . fORMDatabase::escapeBySchema($table, $unique_column, $value, '=');
				}
			}
			
			if ($object->exists()) {
				foreach ($primary_keys as $primary_key) {
					$value = fActiveRecord::retrieveOld($old_values, $primary_key, $values[$primary_key]);
					$sql  .= ' AND ' . $primary_key . fORMDatabase::escapeBySchema($table, $primary_key, $value, '<>');
				}
			}
			
			try {
				$result = fORMDatabase::retrieve()->translatedQuery($sql);
				$result->tossIfNoRows();
			
				// If an exception was not throw, we have existing values
				$column_names = array();
				foreach ($unique_columns as $unique_column) {
					$column_names[] = fORM::getColumnName($class, $unique_column);
				}
				if (sizeof($column_names) == 1) {
					return self::compose(
						'%sThe value specified must be unique, however it already exists',
						fValidationException::formatField(join('', $column_names))
					);
				} else {
					return self::compose(
						'%sThe values specified must be a unique combination, however the specified combination already exists',
						fValidationException::formatField(join(', ', $column_names))
					);
				}
			
			} catch (fNoRowsException $e) { }
		}
	}
	
	
	/**
	 * Validates against a valid values rule
	 *
	 * @param  string $class         The class this rule applies to
	 * @param  array  &$values       An associative array of all values for the record
	 * @param  string $column        The column the rule applies to
	 * @param  array  $valid_values  An array of valid values to check the column against
	 * @return string  The error message for the rule specified
	 */
	static private function checkValidValuesRule($class, &$values, $column, $valid_values)
	{
		if ($values[$column] === NULL) {
			return;	
		}
		
		if (!in_array($values[$column], $valid_values)) {
			return self::compose(
				 '%1$sPlease choose from one of the following: %2$s',
				fValidationException::formatField(fORM::getColumnName($class, $column)),
				join(', ', $valid_values)
			);
		}
	}
	
	
	/**
	 * Composes text using fText if loaded
	 * 
	 * @param  string  $message    The message to compose
	 * @param  mixed   $component  A string or number to insert into the message
	 * @param  mixed   ...
	 * @return string  The composed and possible translated message
	 */
	static private function compose($message)
	{
		$args = array_slice(func_get_args(), 1);
		
		if (class_exists('fText', FALSE)) {
			return call_user_func_array(
				array('fText', 'compose'),
				array($message, $args)
			);
		} else {
			return vsprintf($message, $args);
		}
	}
	
	
	/**
	 * Makes sure each rule array is set to at least an empty array
	 *
	 * @internal
	 * 
	 * @param  string $class  The class to initilize the arrays for
	 * @return void
	 */
	static private function initializeRuleArrays($class)
	{
		self::$conditional_rules[$class]         = (isset(self::$conditional_rules[$class]))         ? self::$conditional_rules[$class]         : array();
		self::$one_or_more_rules[$class]         = (isset(self::$one_or_more_rules[$class]))         ? self::$one_or_more_rules[$class]         : array();
		self::$only_one_rules[$class]            = (isset(self::$only_one_rules[$class]))            ? self::$only_one_rules[$class]            : array();
		self::$related_one_or_more_rules[$class] = (isset(self::$related_one_or_more_rules[$class])) ? self::$related_one_or_more_rules[$class] : array();
		self::$valid_values_rules[$class]        = (isset(self::$valid_values_rules[$class]))        ? self::$valid_values_rules[$class]        : array();
	}
	
	
	/**
	 * Adds metadata about features added by this class
	 * 
	 * @internal
	 * 
	 * @param  string $class      The class being inspected
	 * @param  string $column     The column being inspected
	 * @param  array  &$metadata  The array of metadata about a column
	 * @return void
	 */
	static public function inspect($class, $column, &$metadata)
	{
		if (!empty(self::$valid_values_rules[$class][$column])) {
			$metadata['valid_values'] = self::$valid_values_rules[$class][$column];
		}
	}
	
	
	/**
	 * Checks to see if a column has been set as case insensitive
	 *
	 * @internal
	 * 
	 * @param  string $class   The class to check
	 * @param  string $column  The column to check
	 * @return boolean  If the column is set to be case insensitive
	 */
	static private function isCaseInsensitive($class, $column)
	{
		return isset(self::$case_insensitive_columns[$class][$column]);
	}
	
	
	/**
	 * Returns FALSE if the string is empty - used for array filtering
	 * 
	 * @param  string $string  The string to check
	 * @return boolean  If the string is not blank
	 */
	static private function isNonBlankString($string)
	{
		return ((string) $string) !== '';
	}
	
	
	/**
	 * Removes a regex replacement
	 * 
	 * @internal
	 * 
	 * @param  mixed  $class    The class name or instance of the class the columns exists in
	 * @param  string $search   The string to search for
	 * @param  string $replace  The string to replace with
	 * @return void
	 */
	static public function removeRegexReplacement($class, $search, $replace)
	{
		$class = fORM::getClass($class);
		
		if (!isset(self::$regex_replacements[$class])) {
			self::$regex_replacements[$class] = array(
				'search'  => array(),
				'replace' => array()
			);
		}
		
		$replacements = count(self::$regex_replacements[$class]['search']);
		
		for ($i = 0; $i < $replacements; $i++) {
			$match_search  = self::$regex_replacements[$class]['search'][$i] == $search;
			$match_replace = self::$regex_replacements[$class]['replace'][$i] == $replace;
			if ($match_search && $match_replace) {
				unset(self::$regex_replacements[$class]['search'][$i]);
				unset(self::$regex_replacements[$class]['replace'][$i]);
			}
		}
		
		// Remove the any gaps in the arrays
		self::$regex_replacements[$class]['search']  = array_merge(self::$regex_replacements[$class]['search']);
		self::$regex_replacements[$class]['replace'] = array_merge(self::$regex_replacements[$class]['replace']);
	}
	
	
	/**
	 * Removes a string replacement
	 * 
	 * @internal
	 * 
	 * @param  mixed  $class    The class name or instance of the class the columns exists in
	 * @param  string $search   The string to search for
	 * @param  string $replace  The string to replace with
	 * @return void
	 */
	static public function removeStringReplacement($class, $search, $replace)
	{
		$class = fORM::getClass($class);
		
		if (!isset(self::$string_replacements[$class])) {
			self::$string_replacements[$class] = array(
				'search'  => array(),
				'replace' => array()
			);
		}
		
		$replacements = count(self::$string_replacements[$class]['search']);
		
		for ($i = 0; $i < $replacements; $i++) {
			$match_search  = self::$string_replacements[$class]['search'][$i] == $search;
			$match_replace = self::$string_replacements[$class]['replace'][$i] == $replace;
			if ($match_search && $match_replace) {
				unset(self::$string_replacements[$class]['search'][$i]);
				unset(self::$string_replacements[$class]['replace'][$i]);
			}
		}
		
		// Remove the any gaps in the arrays
		self::$string_replacements[$class]['search']  = array_merge(self::$string_replacements[$class]['search']);
		self::$string_replacements[$class]['replace'] = array_merge(self::$string_replacements[$class]['replace']);
	}
	
	
	/**
	 * Reorders list items in an html string based on their contents
	 * 
	 * @internal
	 * 
	 * @param  string $class     The class to reorder messages for
	 * @param  array  $messages  An array of the messages
	 * @return array  The reordered messages
	 */
	static public function reorderMessages($class, $messages)
	{
		if (!isset(self::$message_orders[$class])) {
			return $messages;
		}
			
		$matches = self::$message_orders[$class];
		
		$ordered_items = array_fill(0, sizeof($matches), array());
		$other_items   = array();
		
		foreach ($messages as $message) {
			foreach ($matches as $num => $match_string) {
				if (fUTF8::ipos($message, $match_string) !== FALSE) {
					$ordered_items[$num][] = $message;
					continue 2;
				}
			}
			
			$other_items[] = $message;
		}
		
		$final_list = array();
		foreach ($ordered_items as $ordered_item) {
			$final_list = array_merge($final_list, $ordered_item);
		}
		return array_merge($final_list, $other_items);
	}
	
	
	/**
	 * Takes a list of messages and performs string and regex replacements on them
	 * 
	 * @internal
	 * 
	 * @param  string $class     The class to reorder messages for
	 * @param  array  $messages  The array of messages
	 * @return array  The new array of messages
	 */
	static public function replaceMessages($class, $messages)
	{
		if (isset(self::$string_replacements[$class])) {
			$messages = str_replace(
				self::$string_replacements[$class]['search'],
				self::$string_replacements[$class]['replace'],
				$messages	
			);
		}
		
		if (isset(self::$regex_replacements[$class])) {
			$messages = preg_replace(
				self::$regex_replacements[$class]['search'],
				self::$regex_replacements[$class]['replace'],
				$messages	
			);
		}
		
		return array_filter($messages, array('fORMValidation', 'isNonBlankString'));
	}
	
	
	/**
	 * Resets the configuration of the class
	 * 
	 * @internal
	 * 
	 * @return void
	 */
	static public function reset()
	{
		self::$case_insensitive_columns  = array();
		self::$conditional_rules         = array();
		self::$message_orders            = array();
		self::$one_or_more_rules         = array();
		self::$only_one_rules            = array();
		self::$regex_replacements        = array();
		self::$related_one_or_more_rules = array();
		self::$string_replacements       = array();
		self::$valid_values_rules        = array();
	}
	
	
	/**
	 * Sets a column to be compared in a case-insensitive manner when checking `UNIQUE` and `PRIMARY KEY` constraints
	 *
	 * @param  mixed  $class   The class name or instance of the class the column is located in
	 * @param  string $column  The column to set as case-insensitive
	 * @return void
	 */
	static public function setColumnCaseInsensitive($class, $column)
	{
		$class = fORM::getClass($class);
		$table = fORM::tablize($class);
		
		$type = fORMSchema::retrieve()->getColumnInfo($table, $column, 'type');
		$valid_types = array('varchar', 'char', 'text');
		if (!in_array($type, $valid_types)) {
			throw new fProgrammerException(
				'The column specified, %1$s, is of the data type %2$s. Must be one of %3$s to be treated as case insensitive.',
				$column,
				$type,
				join(', ', $valid_types)
			);
		}
		
		if (!isset(self::$case_insensitive_columns[$class])) {
			self::$case_insensitive_columns[$class] = array();
		}
		
		self::$case_insensitive_columns[$class][$column] = TRUE;
	}
	
	
	/**
	 * Allows setting the order that the list items in a message will be displayed
	 *
	 * All string comparisons during the reordering process are done in a
	 * case-insensitive manner.
	 * 
	 * @param  mixed $class    The class name or an instance of the class to set the message order for
	 * @param  array $matches  This should be an ordered array of strings. If a line contains the string it will be displayed in the relative order it occurs in this array.
	 * @return void
	 */
	static public function setMessageOrder($class, $matches)
	{
		$class = fORM::getClass($class);
		uasort($matches, array('self', 'sortMessageMatches'));
		self::$message_orders[$class] = $matches;
	}
	
	
	/**
	 * Compares the message matching strings by longest first so that the longest matches are made first
	 *
	 * @param  string $a  The first string to compare
	 * @param  string $b  The second string to compare
	 * @return integer  `-1` if `$a` is longer than `$b`, `0` if they are equal length, `1` if `$a` is shorter than `$b`
	 */
	static private function sortMessageMatches($a, $b)
	{
		if (strlen($a) == strlen($b)) {
			return 0;	
		}
		if (strlen($a) > strlen($b)) {
			return -1;	
		}
		return 1;
	}
	
	
	/**
	 * Returns `TRUE` for non-empty strings, numbers, objects, empty numbers and string-like numbers (such as `0`, `0.0`, `'0'`)
	 * 
	 * @param  mixed $value  The value to check
	 * @return boolean  If the value is string-like
	 */
	static private function stringlike($value)
	{
		if ((!is_string($value) && !is_object($value) && !is_numeric($value)) || !strlen(trim($value))) {
			return FALSE;	
		}
		
		return TRUE;
	}
	
	
	/**
	 * Validates values for an fActiveRecord object against the database schema and any additional rules that have been added
	 *
	 * @internal
	 * 
	 * @param  fActiveRecord  $object      The instance of the class to validate
	 * @param  array          $values      The values to validate
	 * @param  array          $old_values  The old values for the record
	 * @return array  An array of messages
	 */
	static public function validate($object, $values, $old_values)
	{
		$class = get_class($object);
		$table = fORM::tablize($class);
		
		self::initializeRuleArrays($class);
		
		$validation_messages = array();
		
		// Convert objects into values for validation
		foreach ($values as $column => $value) {
			$values[$column] = fORM::scalarize($class, $column, $value);
		}
		foreach ($old_values as $column => $column_values) {
			foreach ($column_values as $key => $value) {
				$old_values[$column][$key] = fORM::scalarize($class, $column, $value);
			}
		}
		
		$message = self::checkPrimaryKeys($object, $values, $old_values);
		if ($message) { $validation_messages[] = $message; }
		
		$column_info = fORMSchema::retrieve()->getColumnInfo($table);
		foreach ($column_info as $column => $info) {
			$message = self::checkAgainstSchema($object, $column, $values, $old_values);
			if ($message) { $validation_messages[] = $message; }
		}
		
		$message = self::checkUniqueConstraints($object, $values, $old_values);
		if ($message) { $validation_messages[] = $message; }
		
		foreach (self::$valid_values_rules[$class] as $column => $valid_values) {
			$message = self::checkValidValuesRule($class, $values, $column, $valid_values);
			if ($message) { $validation_messages[] = $message; }
		}
		
		foreach (self::$conditional_rules[$class] as $rule) {
			$messages = self::checkConditionalRule($class, $values, $rule['main_columns'], $rule['conditional_values'], $rule['conditional_columns']);
			if ($messages) { $validation_messages = array_merge($validation_messages, $messages); }
		}
		
		foreach (self::$one_or_more_rules[$class] as $rule) {
			$message = self::checkOneOrMoreRule($class, $values, $rule['columns']);
			if ($message) { $validation_messages[] = $message; }
		}
		
		foreach (self::$only_one_rules[$class] as $rule) {
			$message = self::checkOnlyOneRule($class, $values, $rule['columns']);
			if ($message) { $validation_messages[] = $message; }
		}
		
		return $validation_messages;
	}
	
	
	/**
	 * Validates related records for an fActiveRecord object
	 *
	 * @internal
	 * 
	 * @param  fActiveRecord $object            The object to validate
	 * @param  array         &$values           The values for the object
	 * @param  array         &$related_records  The related records for the object
	 * @return array         An array of messages
	 */
	static public function validateRelated($object, &$values, &$related_records)
	{
		$class = get_class($object);
		$table = fORM::tablize($class);
		
		$validation_messages = array();
		
		// Check related rules 
		foreach (self::$related_one_or_more_rules[$class] as $related_class => $routes) {
			foreach ($routes as $route => $enabled) {
				$message = self::checkRelatedOneOrMoreRule($object, $values, $related_records, $related_class, $route);
				if ($message) { $validation_messages[] = $message; }
			}
		}
		
		$related_messages    = fORMRelated::validate($class, $values, $related_records);
		$validation_messages = array_merge($validation_messages, $related_messages);
		
		return $validation_messages;
	}
}



/**
 * Copyright (c) 2007-2009 Will Bond <will@flourishlib.com>, others
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */