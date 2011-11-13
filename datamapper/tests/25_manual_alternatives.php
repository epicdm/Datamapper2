<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Data Mapper ORM Class
 *
 * tests : test all relationships with single primary key tables
 *
 * @license     MIT License
 * @package     DataMapper ORM
 * @category    DataMapper ORM
 * @author      Harro "WanWizard" Verton
 * @link        http://datamapper.wanwizard.eu
 * @version     2.0.0
 */

class DataMapper_Tests_Manual_Alternatives
{
	public static $CI;

	/*
	 * dummy static constructor
	 *
	 * called by the runner, to check what tests this class contains, and in
	 * which sequence they should be called
	 */
	public function _construct()
	{
		self::$CI = get_instance();

		return array(
			'title' => 'DataMapper Tests &raquo; Manual &raquo; Get (Alternatives) examples',
			'methods' => array(
				'getiterated' => 'IteratorAggregate results',
				'getpaged' => 'Paginated results',
				'getpagediterated' => 'Paginated IteratorAggregate results',
				'getraw' => 'Raw codeigniter results',
				'getsql' => 'Raw SQL results',
			),
		);
	}

	/*
	 * IteratorAggregate results
	 */
	public function getiterated()
	{
		try
		{
			$dmtesta = new Dmtesta();
			$dmtesta->get_iterated();
		}
		catch (Exception $e)
		{
			DataMapper_Tests::failed('Exception: '.$e->getMessage());
		}

		$expected_result = array(
			array(
				'id' => 1,
				'fk_id_A' => 0,
				'data_A' => 'Table A Row 1',
			),
			array(
				'id' => 2,
				'fk_id_A' => 1,
				'data_A' => 'Table A Row 2',
			),
			array(
				'id' => 3,
				'fk_id_A' => 1,
				'data_A' => 'Table A Row 3',
			),
		);

		$result = DataMapper_Tests::assertEqual($dmtesta->exists(), true, '$model->get_iterated()->exists();');
		$result = DataMapper_Tests::assertEqual($dmtesta->all_to_array(), $expected_result, '$model->get_iterated(); - iterate over the object');
		$result = DataMapper_Tests::assertEqual($dmtesta->all, array(), '$model->get_iterated(); - empty all array');
	}

	/*
	 * Paginated results
	 */
	public function getpaged()
	{
		try
		{
			$dmtesta = new Dmtesta();
			$dmtesta->get_paged();
		}
		catch (Exception $e)
		{
			DataMapper_Tests::failed('Exception: '.$e->getMessage());
		}

		$expected_result = array(
			array(
				'id' => 1,
				'fk_id_A' => 0,
				'data_A' => 'Table A Row 1',
			),
			array(
				'id' => 2,
				'fk_id_A' => 1,
				'data_A' => 'Table A Row 2',
			),
			array(
				'id' => 3,
				'fk_id_A' => 1,
				'data_A' => 'Table A Row 3',
			),
		);

		$result = DataMapper_Tests::assertEqual($dmtesta->all_to_array(), $expected_result, '$model->get_paged(); - results');

		$expected_result = array(
			'page_size' => 50,
			'items_on_page' => 3,
			'current_page' => 1,
			'current_row' => 0,
			'total_rows' => 3,
			'last_row' => 0,
			'total_pages' => 1,
			'has_previous' => false,
			'previous_page' => 1,
			'previous_row' => 0,
			'has_next' => false,
			'next_page' => 1,
			'next_row' => 0,
		);

		$result = DataMapper_Tests::assertEqual( (array) $dmtesta->paged, $expected_result, '$model->get_paged(); - paged info');

	}

	/*
	 * Paginated IteratorAggregate results
	 */
	public function getpagediterated()
	{
		try
		{
			$dmtesta = new Dmtesta();
			$dmtesta->get_paged_iterated();
		}
		catch (Exception $e)
		{
			DataMapper_Tests::failed('Exception: '.$e->getMessage());
		}

		$expected_result = array(
			array(
				'id' => 1,
				'fk_id_A' => 0,
				'data_A' => 'Table A Row 1',
			),
			array(
				'id' => 2,
				'fk_id_A' => 1,
				'data_A' => 'Table A Row 2',
			),
			array(
				'id' => 3,
				'fk_id_A' => 1,
				'data_A' => 'Table A Row 3',
			),
		);

//var_dump($dmtesta->all_to_array());

		$result = DataMapper_Tests::assertEqual($dmtesta->exists(), true, '$model->get_iterated()->exists();');
		$result = DataMapper_Tests::assertEqual($dmtesta->all_to_array(), $expected_result, '$model->get_iterated(); - iterate over the object');
		$result = DataMapper_Tests::assertEqual($dmtesta->all, array(), '$model->get_iterated(); - empty all array');
	}

	/*
	 * Raw codeigniter results
	 */
	public function getraw()
	{
		try
		{
			// run the query
			$dmtesta = new Dmtesta();
			$dmtesta->include_related('dmtestb')->order_by('id', 'ASC')->order_by('dmtestb_data_b', 'ASC')->get();
			$query = $dmtesta->get_raw();

			// store the first result
			$result = $query->result();
			foreach ( $result as $key => $value )
			{
				$result[$key] = (array) $value;
			}

			// convert the response so we can assert it
			$query = (array) $query;
			$query['conn_id'] = (array) $query['conn_id'];
			$query['result_id'] = (array) $query['result_id'];
			foreach ( $query['result_object'] as $key => $value )
			{
				$query['result_object'][$key] = (array) $value;
			}

		}
		catch (Exception $e)
		{
			DataMapper_Tests::failed('Exception: '.$e->getMessage());
			$result = null;
			$query = null;
		}

		$expected_result = array(
			array(
				'id' => '1',
				'fk_id_A' => '0',
				'data_A' => 'Table A Row 1',
			),
			array(
				'id' => '2',
				'fk_id_A' => '1',
				'data_A' => 'Table A Row 2',
			),
			array(
				'id' => '3',
				'fk_id_A' => '1',
				'data_A' => 'Table A Row 3',
			),
		);

		$result = DataMapper_Tests::assertEqual($result, $expected_result, '$model->include_related("dmtestb")->order_by("id", "ASC")->order_by("dmtestb_data_b", "ASC")->get_raw(); - results');

		$expected_result = array(
			'conn_id' => array(
				'affected_rows' => null,
				'client_info' => null,
				'client_version' => null,
				'connect_errno' => null,
				'connect_error' => null,
				'errno' => null,
				'error' => null,
				'field_count' => null,
				'host_info' => null,
				'info' => null,
				'insert_id' => null,
				'server_info' => null,
				'server_version' => null,
				'sqlstate' => null,
				'protocol_version' => null,
				'thread_id' => null,
				'warning_count' => null,
			),
			'result_id' => array(
				'current_field' => null,
				'field_count' => null,
				'lengths' => null,
				'num_rows' => null,
				'type' => null,
			),
			'result_array' => array(),
			'result_object' => array(
				array(
					'id' => '1',
					'fk_id_A' => '0',
					'data_A' => 'Table A Row 1',
				),
				array(
					'id' => '2',
					'fk_id_A' => '1',
					'data_A' => 'Table A Row 2',
				),
				array(
					'id' => '3',
					'fk_id_A' => '1',
					'data_A' => 'Table A Row 3',
				),
			),
			'custom_result_object' => array(),
			'current_row' => 0,
			'num_rows' => 3,
			'row_data' => null,
		);

		$result = DataMapper_Tests::assertEqual($query, $expected_result, '$model->include_related("dmtestb")->order_by("id", "ASC")->order_by("dmtestb_data_b", "ASC")->get_raw(); - CI query object');

	}

	/*
	 * Raw SQL results
	 */
	public function getsql()
	{
		try
		{
			// run the query
			$dmtesta = new Dmtesta();
			$result = $dmtesta->include_related('dmtestb')->order_by('id', 'ASC')->order_by('dmtestb_data_b', 'ASC')->get_sql();
		}
		catch (Exception $e)
		{
			DataMapper_Tests::failed('Exception: '.$e->getMessage());
			$result = null;
		}

		$expected_result = "SELECT `DMTA_1`.`id` AS `dmtestb_id`, `DMTA_1`.`data_B` AS `dmtestb_data_B`, `DMTA_0`.*\n".
							"FROM (`dmtests_A` `DMTA_0`)\n".
							"LEFT OUTER JOIN `dmtests_C` `DMTA_5` ON `DMTA_5`.`fk_id_A` = `DMTA_0`.`id`\n".
							"LEFT OUTER JOIN `dmtests_B` `DMTA_1` ON `DMTA_5`.`fk_id_B` = `DMTA_1`.`id`\n".
							"ORDER BY `DMTA_0`.`id` ASC, `dmtestb_data_b` ASC";

		$result = DataMapper_Tests::assertEqual($result, $expected_result, '$model->include_related("dmtestb")->order_by("id", "ASC")->order_by("dmtestb_data_b", "ASC")->get_sql();');
	}

}
