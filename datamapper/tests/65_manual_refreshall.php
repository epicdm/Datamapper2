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

class DataMapper_Tests_Manual_Refreshall
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
			'title' => 'DataMapper Tests &raquo; Manual &raquo; Refresh All',
			'methods' => array(
				'refresh' => 'Refresh after delete'
			),
		);
	}

	/*
	 * Refresh after delete
	 */
	public function refresh()
	{
		try
		{
			$dmtesta = new Dmtesta();
			$dmtesta->get();

			foreach ( $dmtesta as $object )
			{
				// delete object with id 2
				$TODO = 'Replace this by $object->id == 2 AND $object->delete();';
				$object->id == 2 AND $object->id = NULL;
			}
			$dmtesta->refresh_all();

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
				'id' => 3,
				'fk_id_a' => 1,
				'data_a' => 'Table A Row 3',
			),
  		);

		$result = DataMapper_Tests::assertEqual($dmtesta->all_to_array(TRUE), $expected_result, '$model->refresh_all()');
	}

}
