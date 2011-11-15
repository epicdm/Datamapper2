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

class DataMapper_Tests_Manual_Utilities
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
			'title' => 'DataMapper Tests &raquo; Manual &raquo; Utilities',
			'methods' => array(
				'utilities' => 'Utility methods'
			),
		);
	}

	/*
	 * Utility methods
	 */
	public function utilities()
	{
		// exists()
		try
		{
			$dmtesta = new Dmtesta();
			$result1 = $dmtesta->exists();

			$dmtesta->get_by_id(1);
			$result2 = $dmtesta->exists();

			$dmtesta->id = NULL;
			$result3 = $dmtesta->exists();

			$dmtesta->all = array();
			$result4 = $dmtesta->exists();
		}
		catch (Exception $e)
		{
			DataMapper_Tests::failed('Exception: '.$e->getMessage());
			isset($result1) OR $result1 = NULL;
			isset($result2) OR $result2 = NULL;
			isset($result3) OR $result3 = NULL;
			isset($result4) OR $result4 = NULL;
		}

		$result = DataMapper_Tests::assertEqual($result1, FALSE, '$model->exists() - on an empty object');
		$result = DataMapper_Tests::assertEqual($result2, TRUE, '$model->exists() - on a loaded object');
		$result = DataMapper_Tests::assertEqual($result3, TRUE, '$model->exists() - on a loaded object with an empty key');
		$result = DataMapper_Tests::assertEqual($result3, TRUE, '$model->exists() - on a loaded object with an empty all array');

		// clear()
		try
		{
			$dmtesta = new Dmtesta(1);
			$result1 = $dmtesta->exists();

			$dmtesta->clear();
			$result2 = $dmtesta->exists();
		}
		catch (Exception $e)
		{
			DataMapper_Tests::failed('Exception: '.$e->getMessage());
			isset($result1) OR $result1 = NULL;
			isset($result2) OR $result2 = NULL;
		}

		$result = DataMapper_Tests::assertEqual($result1, TRUE, '$model->clear() - before');
		$result = DataMapper_Tests::assertEqual($result2, FALSE, '$model->clear() - after');

		// reinitialize_model()
		$TODO = 'test for a succesful language change on an already loaded model';

		// query()
		try
		{
			$dmtesta = new Dmtesta();
			$dmtesta->query('SELECT `id`, `data_A` from `dmtests_A` ORDER BY `id` DESC');
		}
		catch (Exception $e)
		{
			DataMapper_Tests::failed('Exception: '.$e->getMessage());
		}

		$expected_result = array(
			array(
				'id' => 3,
				'fk_id_A' => NULL,
				'data_A' => 'Table A Row 3',
			),
			array(
				'id' => 2,
				'fk_id_A' => NULL,
				'data_A' => 'Table A Row 2',
			),
			array(
				'id' => 1,
				'fk_id_A' => NULL,
				'data_A' => 'Table A Row 1',
			),
		);

		$result = DataMapper_Tests::assertEqual($dmtesta->all_to_array(), $expected_result, '$model->query("SELECT `id`, `data_A` from `table` ORDER BY `id` DESC");');

		try
		{
			$dmtesta = new Dmtesta();
			$dmtesta->query('SELECT `id`, `data_A` from `dmtests_A` WHERE `id` > ?', array(1));
		}
		catch (Exception $e)
		{
			DataMapper_Tests::failed('Exception: '.$e->getMessage());
		}

		$expected_result = array(
			array(
				'id' => 2,
				'fk_id_A' => NULL,
				'data_A' => 'Table A Row 2',
			),
			array(
				'id' => 3,
				'fk_id_A' => NULL,
				'data_A' => 'Table A Row 3',
			),
		);

		$result = DataMapper_Tests::assertEqual($dmtesta->all_to_array(), $expected_result, '$model->query("SELECT `id`, `data_A` from `dmtests_A` WHERE `id` > ?", array(1));');

		// add_table_name()

		try
		{
			$dmtesta = new Dmtesta();
			$validfield = $dmtesta->add_table_name('id');
			$invalidfield = $dmtesta->add_table_name('notvalid');
		}
		catch (Exception $e)
		{
			DataMapper_Tests::failed('Exception: '.$e->getMessage());
			isset($validfield) OR $validfield = NULL;
			isset($invalidfield) OR $invalidfield = NULL;
		}

		$result = DataMapper_Tests::assertEqual($validfield, '`DMTA_0`.`id`', '$model->add_table_name("valid_field_name");');
		$result = DataMapper_Tests::assertEqual($invalidfield, 'notvalid', '$model->add_table_name("invalid_field_name");');

		// check_last_query()

		try
		{
			$dmtesta = new Dmtesta(1);
			$result = $dmtesta->check_last_query($delims = array(), TRUE);
		}
		catch (Exception $e)
		{
			DataMapper_Tests::failed('Exception: '.$e->getMessage());
			isset($result) OR $result = NULL;
		}

		$expected_result = "SELECT `DMTA_0`.*\nFROM (`dmtests_A` `DMTA_0`)\nWHERE `DMTA_0`.`id` =  1\nORDER BY `DMTA_0`.`id` asc";

		$result = DataMapper_Tests::assertEqual($result, $expected_result, '$model->check_last_query();');
	}

}
