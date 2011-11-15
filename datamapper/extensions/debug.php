<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Data Mapper ORM Class
 *
 * DataMapper extension - debugging methods
 *
 * @license 	MIT License
 * @package		DataMapper ORM
 * @category	DataMapper ORM
 * @author  	Harro "WanWizard" Verton
 * @link		http://datamapper.wanwizard.eu/
 * @version 	2.0.0
 */

class DataMapper_Debug
{
	/**
	 * renders the last DB query performed
	 *
	 * @param	object	$dmobject			DataMapper object
	 * @param	array	$delims				delimiters for the SQL string
	 * @param	bool	$return_as_string	if TRUE, don't output automatically
	 *
	 * @return	string	last db query formatted as a string
	 */
	public function check_last_query($dmobject, $delims = array('<hr /><pre>', '</pre><hr />'), $return_as_string = FALSE)
	{
		$q = wordwrap($dmobject->db->last_query(), 100, "\n\t");
		if ( ! empty($delims) )
		{
			$q = implode($q, $delims);
		}
		if ( $return_as_string === FALSE )
		{
			echo $q;
		}
		return $q;
	}

}

/* End of file debug.php */
/* Location: ./application/third_party/datamapper/extensions/debug.php */
