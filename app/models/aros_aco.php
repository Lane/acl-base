<?php
class ArosAco extends AppModel
{
	var $name = 'ArosAco';
	 
	var $belongsTo = array('Aco', 'Aro');
	
	function setPermissions($acos, $aro)
	{
		foreach($acos as $k => $aco)
		{
			$perm = $this->getPermissions($aco, $aro, $acos);
			if($aco['Aco']['id'] != $perm['ArosAco']['aco_id'])
			{
				$perm['ArosAco']['parent'] = true;
			}
			else
			{
				$perm['ArosAco']['parent'] = false;
			}
			$acos[$k]['Aco']['ArosAco'] = $perm['ArosAco'];
		}
		return $acos;
	}
	 
	function getPermissions($acos, $aros, $acoList)
	{
		$conditions = array("ArosAco.aco_id" => $acos['Aco']['id'], 'ArosAco.aro_id' => $aros['Aro']['id']);
		$result = $this->find($conditions);
		 
		if(empty($result))
		{
			if($acos['Aco']['parent_id'] != null)
			{
				foreach($acoList as $a)
				{
					if($a['Aco']['id'] == $acos['Aco']['parent_id'])
					{
						$acos = $a;
						break;
					}
				}
				return $this->getPermissions($acos, $aros, $acoList);
			}
			else
			{
				return null;
			}
		}
		else
		{
			return $result;
		}
	}
	 
	function findUsingAroAco($aro, $aco)
	{
		$this->recursive = -1;
		return $this->find(
			'first',
			array(
				'conditions' => array(
					'aro_id' => $aro,
					'aco_id' => $aco
				)
			)
		);
	}
	 
	function savePermissions($permissions, $aro_id)
	{
		foreach($permissions as $key => $arosaco)
		{
			if($key != 'Group' && $key != 'User' && isset($arosaco['ArosAco']))
			{
				$pm = array('_create', '_read', '_update', '_delete');
				foreach($pm as $vm)
				{
					if($arosaco['ArosAco'][$vm] == 0)
					{
						$arosaco['ArosAco'][$vm] = -1;
					}
				}
				$arosaco['ArosAco']['aro_id'] = $aro_id;
				$arac = $this->findUsingAroAco($arosaco['ArosAco']['aro_id'], $arosaco['ArosAco']['aco_id']);
				$arac_id = null;
				
				if(!empty($arac))
				{
					$arac_id = $arac['ArosAco']['id'];
				}
				// if same as parent, and a record exists, delete it
				if($arosaco['ArosAco']['parent'] == 1 && isset($arac_id))
				{
					$this->id = $arac_id;
					if(!$this->delete($arac_id))
					{
						return false;
					}
				}
				// not the same as parent, but record exists, update it
				else if($arosaco['ArosAco']['parent'] == 0 && isset($arac_id))
				{
					$this->id = $arac_id;
					if(!$this->save($arosaco))
					{
						return false;
					}
				}
				// not the same as parent, record does not exist, create it
				else if($arosaco['ArosAco']['parent'] == 0 && !isset($arac_id))
				{
					$this->create();
					if(!$this->save($arosaco))
					{
						return false;
					}
				}
			}
		}
		return true;
	}
}
?>