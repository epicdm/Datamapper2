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

class DataMapper_Tests_Manual_Cloning
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
			'title' => 'DataMapper Tests &raquo; Manual &raquo; Cloning and Copying',
			'methods' => array(
				'clones' => 'Making clones',
				'copies' => 'Making copies'
			),
		);
	}

	/*
	 * Making clones
	 */
	public function clones()
	{
		try
		{
			$dmtesta = new Dmtesta(1);
			$dmtesta2 = $dmtesta->get_clone();
		}
		catch (Exception $e)
		{
			DataMapper_Tests::failed('Exception: '.$e->getMessage());
			$dmtesta2 = NULL;
		}

		$expected_result = array(
			array(
				'id' => 1,
				'fk_id_a' => 0,
				'data_a' => 'Table A Row 1',
			),
  		);

		$result = DataMapper_Tests::assertEqual($dmtesta === $dmtesta2, FALSE, '$model->get_clone(); - identical instance check');
		$result = DataMapper_Tests::assertEqual($dmtesta2->all_to_array(TRUE), $expected_result, '$model->get_clone(); - identical content check');
	}

	/*
	 * Making copies
	 */
	public function copies()
	{
		try
		{
			$dmtesta = new Dmtesta(1);
			$dmtesta2 = $dmtesta->get_copy();
		}
		catch (Exception $e)
		{
			DataMapper_Tests::failed('Exception: '.$e->getMessage());
			$dmtesta2 = NULL;
		}

		$expected_result = array(
			array(
				'id' => NULL,
				'fk_id_a' => 0,
				'data_a' => 'Table A Row 1',
			),
  		);

		$result = DataMapper_Tests::assertEqual($dmtesta === $dmtesta2, FALSE, '$model->get_copy(); - identical instance check');
		$result = DataMapper_Tests::assertEqual($dmtesta2->all_to_array(TRUE), $expected_result, '$model->get_copy(); - reset of keys check');
	}
}
