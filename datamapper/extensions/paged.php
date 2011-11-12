<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Data Mapper ORM Class
 *
 * DataMapper extension - pagination methods
 *
 * @license 	MIT License
 * @package		DataMapper ORM
 * @category	DataMapper ORM
 * @author  	Harro "WanWizard" Verton
 * @link		http://datamapper.wanwizard.eu/
 * @version 	2.0.0
 */

class DataMapper_Paged
{
	/**
	 * convenience method that runs a query based on pages
	 *
	 * this object will have two new values, $query_total_pages and
	 * $query_total_rows, which can be used to determine how many pages and
	 * how many rows are available in total, respectively
	 *
	 * @param	DataMapper	$dmobject	the DataMapper object to convert
	 * @param	int		$page				page (1-based) to start on, or row (0-based) to start on
	 * @param	int		$page_size			number of rows in a page
	 * @param	bool	$page_num_by_rows	when TRUE, $page is the starting row, not the starting page
	 * @param	bool	$iterated			internal use only
	 *
	 * @return	DataMapper	Returns self for method chaining
	 */
	public static function get_paged($dmobject, $page = 1, $page_size = 50, $page_num_by_rows = FALSE, $info_object = 'paged', $iterated = FALSE)
	{
		// first, duplicate this query, so we have a copy for the query
		$count_query = $dmobject->get_clone(TRUE);

		if($page_num_by_rows)
		{
			$page = 1 + floor(intval($page) / $page_size);
		}

		// never less than 1
		$page = max(1, intval($page));
		$offset = $page_size * ($page - 1);

		// for performance, we clear out the select AND the order by statements,
		// since they aren't necessary and might slow down the query.
		$count_query->db->ar_select = NULL;
		$count_query->db->ar_orderby = NULL;
		$total = $count_query->db->ar_distinct ? $count_query->count_distinct() : $count_query->count();

		// common vars
		$last_row = $page_size * floor($total / $page_size);
		$total_pages = ceil($total / $page_size);

		// make sure offset doesn't go beyond the last row
		if($offset >= $last_row)
		{
			// too far!
			$offset = $last_row;
			$page = $total_pages;
		}

		// now query this object
		if($iterated)
		{
			$dmobject->get_iterated($page_size, $offset);
		}
		else
		{
			$dmobject->get($page_size, $offset);
		}

		// create the paged info object
		$dmobject->{$info_object} = new stdClass();

		$dmobject->{$info_object}->page_size = $page_size;
		$dmobject->{$info_object}->items_on_page = $dmobject->result_count();
		$dmobject->{$info_object}->current_page = intval($page);
		$dmobject->{$info_object}->current_row = intval($offset);
		$dmobject->{$info_object}->total_rows = $total;
		$dmobject->{$info_object}->last_row = intval($last_row);
		$dmobject->{$info_object}->total_pages = intval($total_pages);
		$dmobject->{$info_object}->has_previous = $offset > 0;
		$dmobject->{$info_object}->previous_page = max(1, $page-1);
		$dmobject->{$info_object}->previous_row = max(0, $offset-$page_size);
		$dmobject->{$info_object}->has_next = $page < $total_pages;
		$dmobject->{$info_object}->next_page = intval(min($total_pages, $page+1));
		$dmobject->{$info_object}->next_row = intval(min($last_row, $offset+$page_size));

		// return the DataMapper object for chaining
		return $dmobject;
	}

	// --------------------------------------------------------------------

	/**
	 * runs get_paged, but as an Iterable
	 *
	 * @see	get_paged
	 *
	 * @param	DataMapper	$dmobject	the DataMapper object to convert
	 * @param	int		$page				page (1-based) to start on, or row (0-based) to start on
	 * @param	int		$page_size			number of rows in a page
	 * @param	bool	$page_num_by_rows	when TRUE, $page is the starting row, not the starting page
	 * @param	bool	$iterated			internal use only
	 *
	 * @return	DataMapper	Returns self for method chaining
	 */
	public static function get_paged_iterated($dmobject, $page = 1, $page_size = 50, $page_num_by_rows = FALSE, $info_object = 'paged')
	{
		return self::get_paged($dmobject, $page, $page_size, $page_num_by_rows, $info_object, TRUE);
	}

}

/* End of file paged.php */
/* Location: ./application/third_party/datamapper/extensions/paged.php */
