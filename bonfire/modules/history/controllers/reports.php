<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class reports extends Admin_Controller {

	//--------------------------------------------------------------------


	public function __construct()
	{
		parent::__construct();

		$this->auth->restrict('History.Reports.View');
		$this->load->model('history_model', null, true);
		$this->lang->load('history');
		
		Template::set_block('sub_nav', 'reports/_sub_nav');
	}

	//--------------------------------------------------------------------



	/*
		Method: index()

		Displays a list of form data.
	*/
	public function index()
	{

		// Deleting anything?
		if ($this->input->post('delete'))
		{
			$checked = $this->input->post('checked');

			if (is_array($checked) && count($checked))
			{
				$result = FALSE;
				foreach ($checked as $pid)
				{
					$result = $this->history_model->delete($pid);
				}

				if ($result)
				{
					Template::set_message(count($checked) .' '. lang('history_delete_success'), 'success');
				}
				else
				{
					Template::set_message(lang('history_delete_failure') . $this->history_model->error, 'error');
				}
			}
		}

		$records = $this->history_model->find_all();

		Template::set('records', $records);
		Template::set('toolbar_title', 'Manage History');
		Template::render();
	}

	//--------------------------------------------------------------------


	//--------------------------------------------------------------------
	// !PRIVATE METHODS
	//--------------------------------------------------------------------

	/*
		Method: save_history()

		Does the actual validation and saving of form data.

		Parameters:
			$type	- Either "insert" or "update"
			$id		- The ID of the record to update. Not needed for inserts.

		Returns:
			An INT id for successful inserts. If updating, returns TRUE on success.
			Otherwise, returns FALSE.
	*/
	private function save_history($type='insert', $id=0)
	{
		if ($type == 'update') {
			$_POST['id'] = $id;
		}

		
		$this->form_validation->set_rules('table','Table','required|trim|max_length[255]');
		$this->form_validation->set_rules('row','Row','required|trim|integer|max_length[11]');
		$this->form_validation->set_rules('old','Old','required|trim');
		$this->form_validation->set_rules('new','New','required|trim');
		$this->form_validation->set_rules('user_id','User','required|trim|integer|max_length[11]');

		if ($this->form_validation->run() === FALSE)
		{
			return FALSE;
		}

		// make sure we only pass in the fields we want
		
		$data = array();
		$data['table']        = $this->input->post('table');
		$data['row']        = $this->input->post('row');
		$data['old']        = $this->input->post('old');
		$data['new']        = $this->input->post('new');
		$data['user_id']        = $this->input->post('user_id');

		if ($type == 'insert')
		{
			$id = $this->history_model->insert($data);

			if (is_numeric($id))
			{
				$return = $id;
			} else
			{
				$return = FALSE;
			}
		}
		else if ($type == 'update')
		{
			$return = $this->history_model->update($id, $data);
		}

		return $return;
	}

	//--------------------------------------------------------------------



}