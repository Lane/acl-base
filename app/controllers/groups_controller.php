<?php
class GroupsController extends AppController
{
	var $name = 'Groups';
	
	function beforeFilter()
	{
		parent::beforeFilter(); 
		$this->subnavItems = array(
			array(
				'restricted' => false,
				'label' => __("Create New Group", true),
				'action' => 'admin_add',
				'controller' => 'Groups',
				'crud' => 'create'
			)
		);
	}
	
	function admin_add()
	{
		if(!empty($this->data)) // form has been submitted
		{
			if($this->Group->save($this->data))
			{
				// save successful: set message and redirect
				$this->Session->setFlash('Group Created');
				$this->redirect(array('action' => 'index'));
			}
			else
			{
				// save failed
			}
		}
	}
	
	function admin_edit($id=null)
	{
		if(!empty($this->data)) // form has been submitted
		{
			if($this->Group->save($this->data))
			{
				// save successful: set message and redirect
				$this->Session->setFlash('Group Edited');
				$this->redirect(array('action' => 'index'));
			}
			else
			{
				// save failed
			}
		}
		else
		{
			$this->data = $this->Group->find('first', array('conditions' => array('Group.id' => $id)));
			if($id == null || $this->data == null)
			{
				$this->Session->setFlash('Invalid group');
				// redirect to error page
			}
			$this->render('admin_add');
		}
	}
	
	function admin_delete($id=null)
	{
		if(!empty($this->data))
		{
			if($this->data['Group']['delete'] == 1)
			{
				if($this->Group->delete($this->data['Group']['id']))
				{
					// group deleted, do something
					$this->Session->setFlash('Group deleted.');
					$this->redirect(array('action' => 'index'));
				}
				else
				{
					// problem deleting, do something else
				}
			}
		}
		$group = $this->Group->find('first', array('conditions' => array('Group.id' => $id)));
		$this->set(compact('group'));
		if($group == null || $id == null)
		{
			$this->Session->setFlash('Invalid group');
		}
	}
	
	function admin_view($id=null)
	{
		$group = $this->Group->find('first', array('conditions' => array('Group.id' => $id)));
		$this->set(compact('group'));
	}
	
	function admin_index($id=null)
	{
		$groups = $this->Group->find('all');
		$this->set(compact('groups'));
	}
}
?>