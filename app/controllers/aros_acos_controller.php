<?php
class ArosAcosController extends AppController
{
	var $name = 'ArosAcos';
	
	function admin_delete()
	{
		if(!empty($this->data))
		{
				if($this->ArosAco->delete($this->data['ArosAco']['id']))
				{
					// user deleted, do something
					$this->Session->setFlash('Permission removed');
					$this->redirect($this->referer());
				}
				else
				{
					// problem deleting, do something else
				}
		}
	}
	
}
?>