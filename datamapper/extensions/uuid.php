<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Data Mapper ORM Class
 *
 * DataMapper extension - uuid methods
 *
 * @license 	MIT License
 * @package		DataMapper ORM
 * @category	DataMapper ORM
 * @author  	Harro "WanWizard" Verton
 * @link		http://datamapper.wanwizard.eu/
 * @version 	2.0.0
 *
 * taken from http://codeigniter.com/forums/viewthread/168403/
 * no copyright notice present in the code, public domain is assumed
 */

class DataMapper_Uuid
{
	/*
	* The following method generates VALID RFC 4211 COMPLIANT Universally Unique IDentifiers (UUID) version 4.
	* Version 4 UUIDs are pseudo-random. UUIDs generated below validates using OSSP UUID Tool.
	*/
	public static function uuid($dmobject)
	{
		return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
		// return sprintf('%04x%04x%04x%04x%04x%04x%04x%04x',

		// 32 bits for "time_low"
		mt_rand(0, 0xffff), mt_rand(0, 0xffff),

		// 16 bits for "time_mid"
		mt_rand(0, 0xffff),

		// 16 bits for "time_hi_and_version",
		// four most significant bits holds version number 4
		mt_rand(0, 0x0fff) | 0x4000,

		// 16 bits, 8 bits for "clk_seq_hi_res",
		// 8 bits for "clk_seq_low",
		// two most significant bits holds zero and one for variant DCE1.1
		mt_rand(0, 0x3fff) | 0x8000,

		// 48 bits for "node"
		mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
		);
	}
}

/* End of file uuid.php */
/* Location: ./application/third_party/datamapper/extensions/uuid.php */
