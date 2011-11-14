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

class DataMapper_Tests_Manual_Functions
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
			'title' => 'DataMapper Tests &raquo; Manual &raquo; SQL function examples',
			'methods' => array(
				'direct' => 'Direct SQL function calls',
				'select' => 'Select SQL function calls'
			),
		);
	}

	/*
	 * Direct function calls
	 */
	public function direct()
	{
		// UPPER('hello')
		try
		{
			$dmtesta = new Dmtesta();
			$result = $dmtesta->func('UPPER', 'hello');
		}
		catch (Exception $e)
		{
			DataMapper_Tests::failed('Exception: '.$e->getMessage());
			$result = FALSE;
		}

		$expected_result = 'UPPER(\'hello\')';

		$result = DataMapper_Tests::assertEqual($result, $expected_result, '$model->func("UPPER", "hello");');

		// round(365 * `DMTA_0`.`data_A`)
		try
		{
			$dmtesta = new Dmtesta();
			$result = $dmtesta->func('round', array(365, '*', '@data_A'));
		}
		catch (Exception $e)
		{
			DataMapper_Tests::failed('Exception: '.$e->getMessage());
			$result = FALSE;
		}

		$expected_result = 'round(365 * `DMTA_0`.`data_A`)';

		$result = DataMapper_Tests::assertEqual($result, $expected_result, '$model->func("round", array(365, "*", "@data_A"));');

		// round(sqrt(`DMTA_0`.`id`))
		try
		{
			$dmtesta = new Dmtesta();
			$result = $dmtesta->func('round', array('sqrt' => '@id'));
		}
		catch (Exception $e)
		{
			DataMapper_Tests::failed('Exception: '.$e->getMessage());
			$result = FALSE;
		}

		$expected_result = 'round(sqrt(`DMTA_0`.`id`))';

		$result = DataMapper_Tests::assertEqual($result, $expected_result, '$model->func("round", array("sqrt" => "@id"))');

		// COALESCE(`DMTA_0`.`data_A`, '')
		try
		{
			$dmtesta = new Dmtesta();
			$result = $dmtesta->func('COALESCE', '@data_A', '');
		}
		catch (Exception $e)
		{
			DataMapper_Tests::failed('Exception: '.$e->getMessage());
			$result = FALSE;
		}

		$expected_result = 'COALESCE(`DMTA_0`.`data_A`, \'\')';

		$result = DataMapper_Tests::assertEqual($result, $expected_result, '$model->func("COALESCE", "@data_A", "")');

		// Trick to get a formula with no function
		// (365 * `DMTA_0`.`data_A`)
		try
		{
			$dmtesta = new Dmtesta();
			$result = $dmtesta->func('', array(365, '*', '@data_A'));
		}
		catch (Exception $e)
		{
			DataMapper_Tests::failed('Exception: '.$e->getMessage());
			$result = FALSE;
		}

		$expected_result = '(365 * `DMTA_0`.`data_A`)';

		$result = DataMapper_Tests::assertEqual($result, $expected_result, '$model->func("", array(365, "*", "@data_A"))');

		// adds `group` table, and returns UPPER(`dmtestb`.`name`)
		try
		{
			$dmtesta = new Dmtesta();
			$result = $dmtesta->func('UPPER', '@dmtestb/data_B');
		}
		catch (Exception $e)
		{
			DataMapper_Tests::failed('Exception: '.$e->getMessage());
			$result = FALSE;
		}

		$expected_result = 'UPPER(`DMTA_1`.`data_B`)';

		$result = DataMapper_Tests::assertEqual($result, $expected_result, '$model->func("UPPER", "@dmtestb/data_B")');
	}

	/*
	 * Select SQL function calls
	 */
	public function select()
	{
		// SELECT UPPER(`DMTA_0`.`data_A`) AS uppercase_name FROM (`dmtests_A` `DMTA_0`)
		try
		{
			$dmtesta = new Dmtesta();
			$dmtesta->select_func('UPPER', '@data_A', 'uppercase_name')->get();
		}
		catch (Exception $e)
		{
			DataMapper_Tests::failed('Exception: '.$e->getMessage());
		}

		$result = $dmtesta->all_to_array( array('id', 'uppercase_name') );

		$expected_result = array(
			array(
				'id' => NULL,
				'uppercase_name' => 'TABLE A ROW 1',
			),
			array(
				'id' => NULL,
				'uppercase_name' => 'TABLE A ROW 2',
			),
			array(
				'id' => NULL,
				'uppercase_name' => 'TABLE A ROW 3',
			),
		);

		$result = DataMapper_Tests::assertEqual($result, $expected_result, '$model->select_func("UPPER", "@data_A", "uppercase_name")->get()');

		// SELECT `DMTA_1`.`id` AS `dmtestb_id`, `DMTA_1`.`data_B` AS `dmtestb_data_B`, (`DMTA_1`.`data_B` = 'Table B Row 1') AS is_row_one, `DMTA_0`.*
		// FROM (`dmtests_A` `DMTA_0`)
		// LEFT OUTER JOIN `dmtests_C`	`DMTA_5` ON `DMTA_5`.`fk_id_A` = `DMTA_0`.`id`
		// LEFT OUTER JOIN `dmtests_B` `DMTA_1` ON	`DMTA_5`.`fk_id_B` = `DMTA_1`.`id`
		// ORDER BY `DMTA_0`.`id` ASC, `dmtestb_id` ASC
		//
		// Note: ordering added to make the result consistent
		try
		{
			$dmtesta = new Dmtesta();
			$dmtesta->include_related('dmtestb')->select_func('', array('@dmtestb/data_B', '=', 'Table B Row 1'), 'is_row_one')->order_by('id', 'ASC')->order_by('dmtestb_id', 'ASC')->get();
		}
		catch (Exception $e)
		{
			DataMapper_Tests::failed('Exception: '.$e->getMessage());
		}

		$result = $dmtesta->all_to_array( array('id', 'dmtestb_id', 'is_row_one') );

		$expected_result = array(
			array(
				'id' => 1,
				'dmtestb_id' => '1',
				'is_row_one' => '1',
			),
			array(
				'id' => 1,
				'dmtestb_id' => '2',
				'is_row_one' => '0',
			),
			array(
				'id' => 1,
				'dmtestb_id' => '3',
				'is_row_one' => '0',
			),
			array(
				'id' => 2,
				'dmtestb_id' => '2',
				'is_row_one' => '0',
			),
			array(
				'id' => 3,
				'dmtestb_id' => NULL,
				'is_row_one' => NULL,
			),
		);

		$result = DataMapper_Tests::assertEqual($result, $expected_result, '$model->select_func("", array("@dmtestb/data_B", "=", "Table B Row 1"), "is_row_one")->get()');


		// SELECT `DMTA_0`.* FROM (`dmtests_A` `DMTA_0`) ORDER BY LOWER(`DMTA_0`.`data_A` & ' - ' &	`DMTA_0`.`fk_id_A`) ASC
		try
		{
			$dmtesta = new Dmtesta();
			$dmtesta->order_by_func('LOWER', array('@data_A', '&', ' - ', '&', '@fk_id_A'), 'ASC')->get();
		}
		catch (Exception $e)
		{
			DataMapper_Tests::failed('Exception: '.$e->getMessage());
		}

		$result = $dmtesta->all_to_array();

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

		$result = DataMapper_Tests::assertEqual($result, $expected_result, '$model->order_by_func("LOWER", array("@data_A", "&", " - ", "&", "@fk_id_A"), "ASC")->get()');

		// SELECT `DMTA_0`.* FROM (`dmtests_A` `DMTA_0`) WHERE `DMTA_0`.`fk_id_A` >= TRIM('1') ORDER BY `DMTA_0`.`id` asc
		try
		{
			$dmtesta = new Dmtesta();
			$dmtesta->where_field_func('fk_id_A >= ', 'TRIM', '1')->get();
		}
		catch (Exception $e)
		{
			DataMapper_Tests::failed('Exception: '.$e->getMessage());
		}

		$result = $dmtesta->all_to_array();

		$expected_result = array(
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

		$result = DataMapper_Tests::assertEqual($result, $expected_result, '$model->where_field_func("fk_id_A >= ", "TRIM", "1")->get()');
	}

}
