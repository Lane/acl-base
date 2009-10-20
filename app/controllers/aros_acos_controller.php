<?php
class ArosAcosController extends AppController
{
	var $name = 'ArosAcos';
	
	function admin_delete()
	{
		if(!empty($this->data))
		{
			if(!$this->ArosAco->delete($this->data['ArosAco']['id']))
			{
				// user could not be deleted
				$this->Session->setFlash(
					__("Error deleting permissions", true), 'default', array('class' => 'message-error')
				);
			}
			else
			{
				$this->Session->setFlash(
					__("Permissions deleted", true), 'default', array('class' => 'message-success')
				);
			}
			$this->redirect($this->referer());
		}
	}
	
	function admin_add()
	{
		if(!empty($this->data)) // form has been submitted
		{
			if(!$this->ArosAco->save($this->data))
			{
				// save failed
				$this->Session->setFlash(
					__("Error setting permissions", true), 'default', array('class' => 'message-error')
				);
			}
			else
			{
				// save successful
				$this->Session->setFlash(
					__("Permissions saved", true), 'default', array('class' => 'message-success')
				);
			}
			$this->redirect($this->referer());
		}
	}
}
?>