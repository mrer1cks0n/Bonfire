<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Install_history extends Migration {

	public function up()
	{
		$prefix = $this->db->dbprefix;

		$fields = array(
			'id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'auto_increment' => TRUE,
			),
			'table' => array(
				'type' => 'VARCHAR',
				'constraint' => 255,
				
			),
			'row' => array(
				'type' => 'INT',
				'constraint' => 11,
				
			),
			'action' => array(
				'type' => 'VARCHAR',
				'constraint' => 255,
				
			),
			'old' => array(
				'type' => 'TEXT',
				
			),
			'new' => array(
				'type' => 'TEXT',
				
			),
			'user_id' => array(
				'type' => 'INT',
				'constraint' => 11,
				
			),
			'ip_address' => array(
				'type' => 'VARCHAR',
				'constraint' => 255,
				
			),
			'created_on' => array(
				'type' => 'datetime',
				'default' => '0000-00-00 00:00:00',
			)
		);
		$this->dbforge->add_field($fields);
		$this->dbforge->add_key('id', true);
		$this->dbforge->create_table('history');

	}

	//--------------------------------------------------------------------

	public function down()
	{
		$prefix = $this->db->dbprefix;

		$this->dbforge->drop_table('history');

	}

	//--------------------------------------------------------------------

}