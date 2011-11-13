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

class DataMapper_Tests_Manual_Counting
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
			'title' => 'DataMapper Tests &raquo; Manual &raquo; Counting',
			'methods' => array(
				'normal' => 'Counting methods',
				'related' => 'Related counting methods',
			),
		);
	}

	/*
	 * Counting methods
	 */
	public function normal()
	{
		// result count on get() and get_iterated()
		try
		{
			$dmtesta = new Dmtesta();
			$dmtesta2 = new Dmtesta();
			$dmtesta->get();
			$dmtesta2->get_iterated();
		}
		catch (Exception $e)
		{
			DataMapper_Tests::failed('Exception: '.$e->getMessage());
		}

		$result = DataMapper_Tests::assertEqual($dmtesta->result_count(), 3, '$model->get()->result_count();');
		$result = DataMapper_Tests::assertEqual($dmtesta2->result_count(), 3, '$model->get_interated()->result_count();');

		// standard count() query
		try
		{
			$dmtesta = new Dmtesta();
			$result = $dmtesta->count();
		}
		catch (Exception $e)
		{
			DataMapper_Tests::failed('Exception: '.$e->getMessage());
		}

		$result = DataMapper_Tests::assertEqual($result, 3, '$model->count();');

		// standard count() query with where() clause
		try
		{
			$dmtesta = new Dmtesta();
			$result = $dmtesta->where('id >', 1)->count();
		}
		catch (Exception $e)
		{
			DataMapper_Tests::failed('Exception: '.$e->getMessage());
		}

		$result = DataMapper_Tests::assertEqual($result, 2, '$model->where("id >", 1)->count();');

		// standard count() query with where() clause and exclude keys
		try
		{
			$dmtesta = new Dmtesta();
			$result = $dmtesta->where('id >', 1)->count( array(array(1), array(2)) );
		}
		catch (Exception $e)
		{
			DataMapper_Tests::failed('Exception: '.$e->getMessage());
		}

		$result = DataMapper_Tests::assertEqual($result, 1, '$model->where("id >", 1)->count( array(array(1), array(2)) );');

		// standard count_distinct() query
		try
		{
			$dmtesta = new Dmtesta();
			$result = $dmtesta->count_distinct();
		}
		catch (Exception $e)
		{
			DataMapper_Tests::failed('Exception: '.$e->getMessage());
		}

		$result = DataMapper_Tests::assertEqual($result, 3, '$model->count_distinct();');

		// count_distinct() query with custom column and exclude
		try
		{
			$dmtesta = new Dmtesta();
			$result = $dmtesta->count_distinct(array(array(1)), array('fk_id_A'));
		}
		catch (Exception $e)
		{
			DataMapper_Tests::failed('Exception: '.$e->getMessage());
		}

		$result = DataMapper_Tests::assertEqual($result, 1, '$model->count_distinct(array(array(1)), array("fk_id_A"));');
	}

	/*
	 * Related counting methods
	 */
	public function related()
	{
		// related count
		try
		{
			$dmtesta = new Dmtesta(2);
			$result = $dmtesta->dmtestb->count();
		}
		catch (Exception $e)
		{
			DataMapper_Tests::failed('Exception: '.$e->getMessage());
		}

		$result = DataMapper_Tests::assertEqual($result, 1, '$model->dmtestb->count();');
	}
}
