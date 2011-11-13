<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Data Mapper ORM Class
 *
 * DataMapper extension - function methods
 *
 * @license 	MIT License
 * @package		DataMapper ORM
 * @category	DataMapper ORM
 * @author  	Harro "WanWizard" Verton
 * @link		http://datamapper.wanwizard.eu/
 * @version 	2.0.0
 */

class DataMapper_Functions
{
	// --------------------------------------------------------------------

	/**
	 * creates a SQL-function with the given (optional) arguments
	 *
	 * each argument can be one of several forms:
	 * 1) an un escaped string value, which will be automatically escaped: "hello"
	 * 2) an escaped value or non-string, which is copied directly: "'hello'" 123, etc
	 * 3) an operator, *, or a non-escaped string is copied directly: "[non-escaped]" ">", etc
	 * 4) a field on this model: "@property"  (Also, "@<whatever>" will be copied directly
	 * 5) a field on a related or deeply related model: "@model/property" "@model/other_model/property"
	 * 6) an array, which is processed recursively as a forumla.
	 *
	 * @param	DataMapper	$dmobject	the DataMapper object
	 * @param	string	$function_name	function name
	 * @param	mixed	$args,... 		(optional) any commands that need to be passed to the function
	 *
	 * @return	string	the new SQL function string
	 */
	public static function func($dmobject, $function_name)
	{
		// get the arguments
		$arguments = func_get_args();

		// pop the first argument
		$elm = array_shift($arguments);

		// if it was our object, do another pop to remove the function name
		if ( $elm instanceOf DataMapper )
		{
			array_shift($arguments);
		}

		// some storage for the result
		$result = '';

		// process the arguments
		foreach($arguments as $argument)
		{
			$result .= ( empty($result) ? '' : ', ') . self::dm_process_function_argument($dmobject, $argument);
		}

		// add the function name
		$result = $function_name . '(' . $result . ')';

		// return the result
		return $result;
	}

	// --------------------------------------------------------------------

	/**
	 * used by the magic method for select_func, {where}_func, etc
	 *
	 * @param	DataMapper	$dmobject	the DataMapper object
	 * @param	string		$query		name of query function
	 * @param	array		$arguments	arguments for func()
	 *
	 * @return	mixed	a query result, or self for method chaining
	 */
	public static function dm_func($dmobject, $query, $arguments)
	{
		// make sure we have enough arguments
		if ( count($arguments) < 2 )
		{
			throw new DataMapper_Exception("DataMapper: invalid number of arguments to {$query}_func: must be at least 2 arguments.");
		}

		// non-selects return a query result
		if ( $query != 'select' )
		{
			$param = array_pop($arguments);
			array_unshift($arguments, $dmobject);
			$value = call_user_func_array('self::func', $arguments);
			return $dmobject->{$query}($value, $param);
		}

		// select queries are treated differently
		$alias = array_pop($arguments);
		array_unshift($arguments, $dmobject);
		$value = call_user_func_array('self::func', $arguments);
		$value .= " AS $alias";

		// we can't use the normal select method, because CI likes to breaky
		$dmobject->dm_manual_select($value);

		// for method chaining
		return $dmobject;
	}

	// --------------------------------------------------------------------

	/**
	 * used by the magic method for {where}_field_func, etc.
	 *
	 * @param	DataMapper	$dmobject	the DataMapper object
	 * @param	string		$query		name of query function
	 * @param	array 		$arguments	arguments for func()
	 *
	 * @return	DataMapper	returns self for method chaining
	 */
	public static function dm_field_func($dmobject, $query, $args)
	{
		// make sure we have enough arguments
		if ( count($arguments) < 2 )
		{
			throw new DataMapper_Exception("DataMapper: invalid number of arguments to {$query}_field_func: must be at least 2 arguments.");
		}

		// pop the fieldname
		$field = array_shift($args);

		// create the function
		array_unshift($arguments, $dmobject);
		$func = call_user_func_array('self::func', $args);

		// deal with any where_in type queries
		return $dmobject->dm_alter_where_in($query, $field, $func);
	}

	// --------------------------------------------------------------------

	private static function dm_process_function_argument($dmobject, $argument, $is_formula = FALSE)
	{
		// some storage for the result
		$ret = '';

		if ( is_array($argument) )
		{
			// formula
			foreach ( $argument as $function => $formula_argument )
			{
				// separate multiple functions
				! empty($ret) AND $ret .= ' ';

				if (is_numeric($function) )
				{
					// process non-functions
					$ret .= self::dm_process_function_argument($dmobject, $formula_argument, TRUE);
				}
				else
				{
					// recursively process functions within functions
					$ret .= call_user_func_array(array($this, 'func'), array_merge(array($function), (array)$formula_argument));
				}
			}
			return $ret;
		}

		$operators = array(
			'AND', 'OR', 'NOT', // binary logic
			'<', '>', '<=', '>=', '=', '<>', '!=', // comparators
			'+', '-', '*', '/', '%', '^', // basic maths
			'|/', '||/', '!', '!!', '@', '&', '|', '#', '~', // advanced maths
			'<<', '>>'); // binary operators

		if ( is_string($argument) )
		{
			if ( ($is_formula AND in_array($argument, $operators)) OR
				 $argument == '*' OR
				 ($argument[0] == "'" AND $argument[strlen($argument)-1] == "'") OR
				 ($argument[0] == "[" AND $argument[strlen($argument)-1] == "]") )
			{
				// simply add already-escaped strings, the special * value, or operators in formulas
				if ( $argument[0] == "[" AND $argument[strlen($argument)-1] == "]")
				{
					// arguments surrounded by square brackets are added directly, minus the brackets
					$argument = substr($argument, 1, -1);
				}
				$ret .= $argument;
			}
			elseif ( $argument[0] == '@' )
			{
				// model or sub-model property
				$argument = substr($argument, 1);
				if ( strpos($argument, '/') !== FALSE )
				{
					// related property
					if ( strpos($argument, 'parent/') === 0 )
					{
						// special parent property for subqueries
						$ret .= str_replace('parent/', '${parent}.', $argument);
					}
					else
					{
die($TODO='functions to deep related models');
						$rel_elements = explode('/', $argument);
						$property = array_pop($rel_elements);
						$table = $this->_add_related_table(implode('/', $rel_elements));
						$ret .= $this->db->protect_identifiers($table . '.' . $property);
					}
				}
				else
				{
					$ret .= $dmobject->db->protect_identifiers($dmobject->add_table_name($argument));
				}
			}
			else
			{
				$ret .= $dmobject->db->escape($argument);
			}
		}
		else
		{
			$ret .= $argument;
		}

		return $ret;
	}
}

/* End of file functions.php */
/* Location: ./application/third_party/datamapper/extensions/functions.php */
