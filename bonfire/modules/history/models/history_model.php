<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class History_model extends BF_Model {

	protected $table		= "history";
	protected $key			= "id";
	protected $soft_deletes	= FALSE;
	protected $date_format	= "datetime";
	protected $set_created	= true;
	protected $set_modified = true;
	protected $created_field = "created_on";
	protected $modified_field = FALSE;

	protected $changes = array();

	protected function _pre_update_log($table, $where) {
		$this->changes = array();
		$records = $this->db->get_where($table, $where);
		if ($records->num_rows() > 0) {
			foreach ($records->result_array() as $record) {
				$this->changes["old"][$record["id"]] = $record;
			}
		}

	}// _pre_update_log

	protected function _post_update_log($table) {
		if (isset($this->changes["old"]) && !empty($this->changes["old"]) && is_array($this->changes["old"])) {
			foreach ($this->changes["old"] as $key => $value) {
				$records = $this->db->get_where($table, array("id" => $key));
				if ($records->num_rows > 0) {
					$record = $records->row_array();
					$data = array(
						"table" => $table,
						"row" => $key,
						"old" => json_encode(array_diff_assoc($this->changes["old"][$key], $record)),
						"new" => json_encode(array_diff_assoc($record, $this->changes["old"][$key])),
						"user_id"=>$this->auth->user_id(),
						"ip_address" => $this->input->ip_address(),
						"action" => "update"
					);
					
					$this->insert($data);
				}
			}
		}
		$this->changes = array();
	}// _post_update_log

	protected function _insert_log($table, $id) {
		$records = $this->db->get_where($table, array("id" => $id));
		if ($records->num_rows > 0) {
			$record = $records->row_array();
			$data = array(
				"table" => $table,
				"row" => $id,
				"old" => "",
				"new" => json_encode($record),
				"user_id"=>$this->auth->user_id(),
				"ip_address" => $this->input->ip_address(),
				"action" => "insert"
			);
			
			$this->insert($data);
		}
	}// _insert_log

	protected function _delete_log($table, $id) {
		$records = $this->db->get_where($table, array("id" => $id));
		if ($records->num_rows > 0) {
			$record = $records->row_array();
			$data = array(
				"table" => $table,
				"row" => $id,
				"old" => json_encode($record),
				"new" => "",
				"user_id"=>$this->auth->user_id(),
				"ip_address" => $this->input->ip_address(),
				"action" => "delete"
			);
			
			$this->insert($data);
		}
	}// _delete_log
}
