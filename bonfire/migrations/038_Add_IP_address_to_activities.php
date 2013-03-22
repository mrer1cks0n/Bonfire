<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Add_IP_address_to_activities extends Migration {

	public function up()
	{
        $prefix = $this->db->dbprefix;

        $this->dbforge->add_column('activities', array(
			'ip_address'	=> array(
				'type'			=> 'varchar',
				'constraint'	=> 127,
				'default' 		=> '1'
			)
		));
	}

	//--------------------------------------------------------------------

	public function down()
	{
        $prefix = $this->db->dbprefix;

		$this->dbforge->drop_column("activities","ip_address");
	}

	//--------------------------------------------------------------------

}