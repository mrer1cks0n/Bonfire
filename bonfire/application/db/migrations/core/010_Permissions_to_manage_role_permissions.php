<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Permissions_to_manage_role_permissions extends Migration {
	
	public function up() 
	{
		$prefix = $this->db->dbprefix;
		
		// name field in permissions table is too short bump it up to 50
		$sql = "ALTER TABLE `{$prefix}permissions` CHANGE `name` `name` VARCHAR(50) NULL";
		$this->db->query($sql);	
		
		$roles = $this->role_model->find_all();
		if (isset($roles) && is_array($roles) && count($roles)) {
			foreach ($roles as $role) {
				// add the permission
				$this->db->query("INSERT INTO {$prefix}permissions(name, description) VALUES('Permissions.".$role->role_name.".Manage','To manage the access control permissions for the ".$role->role_name." role.')");
				// give administrators full right to manage permissions
				$this->db->query("INSERT INTO {$prefix}role_permissions VALUES(1,".$this->db->insert_id().")");
			}
		}		
	}
	
	//--------------------------------------------------------------------
	
	public function down() 
	{
		$prefix = $this->db->dbprefix;
		
		$roles = $this->role_model->find_all();
		if (isset($roles) && is_array($roles) && count($roles)) {
			foreach ($roles as $role) {
				// delete any but that has any of these permissions from the role_permissions table
				$query = $this->db->query("SELECT permission_id FROM {$prefix}permissions WHERE name = 'Permissions.".$role->role_name.".Manage'");
				foreach ($query->result_array() as $row)
				{
					$permission_id = $row['permission_id'];
					$this->db->query("DELETE FROM {$prefix}role_permissions WHERE permission_id='$permission_id';");
				}
				//delete the role
				$this->db->query("DELETE FROM {$prefix}permissions WHERE (name = 'Permissions.".$role->role_name.".Manage')");
			}
		}
		
		// restore the shorter table field size back to 30
		$sql = "ALTER TABLE `{$prefix}permissions` CHANGE `name` `name` VARCHAR(30) NULL";
		$this->db->query($sql);	
	}
	
	//--------------------------------------------------------------------
	
}