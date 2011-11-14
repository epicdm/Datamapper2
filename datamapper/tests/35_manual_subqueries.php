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

class DataMapper_Tests_Manual_Subqueries
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
			'title' => 'DataMapper Tests &raquo; Manual &raquo; Subqueries examples',
			'methods' => array(
				'select' => 'Select subqueries',
				'where' => 'Where subqueries',
			),
		);
	}

	/*
	 * Select subqueries
	 */
	public function select()
	{
		try
		{
			$dmtesta = new Dmtesta();
			$dmtestb = $dmtesta->dmtestb;

			$dmtestb->select_func('COUNT', '*', 'count');
			$dmtestb->where_related_dmteste('flag', '0');

			$dmtesta->select_subquery($dmtestb, 'flag_count')->get();
		}
		catch (Exception $e)
		{
			DataMapper_Tests::failed('Exception: '.$e->getMessage());
		}

		$expected_result = array(
			array(
				'id' => 1,
				'fk_id_a' => 0,
				'data_a' => 'Table A Row 1',
				'flag_count' => '1',
			),
			array(
				'flag_count' => '1',
				'id' => 2,
				'fk_id_a' => 1,
				'data_a' => 'Table A Row 2',
			),
			array(
				'flag_count' => '1',
				'id' => 3,
				'fk_id_a' => 1,
				'data_a' => 'Table A Row 3',
			),
  		);

		$result = DataMapper_Tests::assertEqual($dmtesta->all_to_array(TRUE), $expected_result, '$model->select_subquery($dmtestb, "flag_count")->get();');
	}

	/*
	 * Where subqueries
	 */
	public function where()
	{
		try
		{
			$dmtesta = new Dmtesta();
			$dmtesta2 = new Dmtesta();

			$dmtesta2->select('id')->where_related_dmtestb('id', 1);

			$dmtesta->where_in_subquery('id', $dmtesta2)->get();
		}
		catch (Exception $e)
		{
			DataMapper_Tests::failed('Exception: '.$e->getMessage());
		}

		$expected_result = array(
			array(
				'id' => 1,
				'fk_id_a' => 0,
				'data_a' => 'Table A Row 1',
			),
  		);

		$result = DataMapper_Tests::assertEqual($dmtesta->all_to_array(TRUE), $expected_result, '$model->where_in_subquery("id", $dmtesta2)->get();');

		try
		{
			$dmtesta = new Dmtesta();
			$dmtestb = $dmtesta->dmtestb;

			$dmtestb->select('id')->where('id', 2);

			$dmtesta->where_in_related_subquery('dmtestb', $dmtestb)->get();
		}
		catch (Exception $e)
		{
			DataMapper_Tests::failed('Exception: '.$e->getMessage());
		}

		$expected_result = array(
			array(
				'id' => 1,
				'fk_id_a' => 0,
				'data_a' => 'Table A Row 1',
			),
			array(
				'id' => 2,
				'fk_id_a' => 1,
				'data_a' => 'Table A Row 2',
			),
  		);

		$result = DataMapper_Tests::assertEqual($dmtesta->all_to_array(TRUE), $expected_result, '$model->where_in_related_subquery("dmtestb", $dmtestb)->get();');
	}
}
