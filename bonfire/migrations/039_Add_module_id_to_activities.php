<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Add_module_id_to_activities extends Migration {

	public function up()
	{
        $prefix = $this->db->dbprefix;

        $this->dbforge->add_column('activities', array(
			'module_id'	=> array(
				'type'			=> 'varchar',
				'constraint'	=> 255,
				'default' 		=> '0'
			)
		));
	}

	//--------------------------------------------------------------------

	public function down()
	{
        $prefix = $this->db->dbprefix;

		$this->dbforge->drop_column("activities","module_id");
	}

	//--------------------------------------------------------------------

}