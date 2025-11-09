<?php
class ModelExtensionModule extends Model {
	public function addModule($code, $data) {
        // Add to activity log
        $this->load->model('user/user');

        $activity_data = array(
            '%user_id' => $this->user->getId(),
            '%module_name' => $data['name'],
            '%name'        => $this->user->getFirstName() . ' ' . $this->user->getLastName()
        );
        $this->model_user_user->addActivity($this->user->getId(), 'add_module', $activity_data);

		$this->db->query("INSERT INTO `" . DB_PREFIX . "module` SET `name` = '" . $this->db->escape($data['name']) . "', `code` = '" . $this->db->escape($code) . "', `setting` = '" . $this->db->escape(serialize($data)) . "'");
	}
	
	public function editModule($module_id, $data) {
        // Add to activity log
        $this->load->model('user/user');

        $activity_data = array(
            '%user_id' => $this->user->getId(),
            '%module_id' => $module_id,
            '%module_name' => $data['name'],
            '%name'        => $this->user->getFirstName() . ' ' . $this->user->getLastName()
        );
        $this->model_user_user->addActivity($this->user->getId(), 'edit_module', $activity_data, $module_id);

		$this->db->query("UPDATE `" . DB_PREFIX . "module` SET `name` = '" . $this->db->escape($data['name']) . "', `setting` = '" . $this->db->escape(serialize($data)) . "' WHERE `module_id` = '" . (int)$module_id . "'");
	}

	public function deleteModule($module_id) {
        // Add to activity log
        $this->load->model('user/user');
        $module = $this->getModule($module_id);

        $activity_data = array(
            '%user_id' => $this->user->getId(),
            '%module_id' => $module_id,
            '%module_name' => $module['name'],
            '%name'        => $this->user->getFirstName() . ' ' . $this->user->getLastName()
        );
        $this->model_user_user->addActivity($this->user->getId(), 'delete_module', $activity_data, $module_id);

		$this->db->query("DELETE FROM `" . DB_PREFIX . "module` WHERE `module_id` = '" . (int)$module_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "layout_module` WHERE `code` LIKE '%." . (int)$module_id . "'");
	}
		
	public function getModule($module_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "module` WHERE `module_id` = '" . $this->db->escape($module_id) . "'");

		if ($query->row) {
			return unserialize($query->row['setting']);
		} else {
			return array();	
		}
	}
	
	public function getModules() {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "module` ORDER BY `code`");

		return $query->rows;
	}	
		
	public function getModulesByCode($code) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "module` WHERE `code` = '" . $this->db->escape($code) . "' ORDER BY `name`");

		return $query->rows;
	}	
	
	public function deleteModulesByCode($code) {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "module` WHERE `code` = '" . $this->db->escape($code) . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "layout_module` WHERE `code` LIKE '" . $this->db->escape($code) . "' OR `code` LIKE '" . $this->db->escape($code . '.%') . "'");
	}	
}