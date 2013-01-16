<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');


class ItemJugglerModule extends  System
{
	public function myLoadDataContainer($strName)
	{
	    if ($GLOBALS['ITEMJUGGLER_CONFIGMODE'])
            return;
        
	    $this->import("Database");
		
		$objDJCallback = $this->Database->prepare("SELECT dca_trigger_callback_list FROM tl_itemjuggler WHERE dca_trigger_callback=1")->execute();
		
		while ($objDJCallback->next())
		{
			$arrTrigger = deserialize($objDJCallback->dca_trigger_callback_list);
			
			if ((is_array($arrTrigger)) &&
				(count($arrTrigger)>0))
			{
				foreach ($arrTrigger as $trigger)
				{
					if ($trigger['tables']==$strName)
					{
						$GLOBALS['TL_DCA'][$trigger['tables']]['config'][$trigger['callbacks']][] = array('ItemJugglerModule','handleItemJuggler_'.$trigger['callbacks']);
						
					}	
				}	
			}	
		}
		
		
		$objDJCron = $this->Database->prepare("SELECT dca_trigger_cron_list FROM tl_itemjuggler WHERE dca_trigger_cron=1")->execute();
		$arrCronList = array();
		while ($objDJCron->next())
		{
			$arrTrigger = deserialize($objDJCron->dca_trigger_cron_list);
			
			if ((is_array($arrTrigger)) &&
				(count($arrTrigger)>0))
			{
				foreach ($arrTrigger as $trigger)
				{
					$arrCron = array('ItemJugglerModule','handleItemJuggler_cron_'.$trigger['cron_type']);
					
					if (($trigger['cron_type']) && (!in_array($arrCron,$GLOBALS['TL_CRON'][$trigger['cron_type']])))
					{
						$arrCronList[$trigger['cron_type']][] = $arrCron;
						
					}	
				}	
			}	
		}
		
		$this->Config->update("\$GLOBALS['ITEMJUGGLER_CRON']", serialize($arrCronList));
	}
	
	public function handleItemJuggler_ondelete_callback(DataContainer $dc)
	{
		$this->handleJob($dc,"ondelete_callback");
	}
	
	public function handleItemJuggler_onload_callback(DataContainer $dc)
	{
		$this->handleJob($dc,"onload_callback");
	}
	
	public function handleItemJuggler_onsubmit_callback(DataContainer $dc)
	{
		$this->handleJob($dc,"onsubmit_callback");
	}

	public function hhandleItemJuggler_oncut_callback(DataContainer $dc)
	{
		$this->handleJob($dc,"oncut_callback");
	}
	
	public function handleItemJuggler_oncopy_callback(DataContainer $dc)
	{
		$this->handleJob($dc,"oncopy_callback");
	}
	
	
	
	public function handleItemJuggler_cron_hourly(DataContainer $dc)
	{
		$this->handleCronJob($dc, 'hourly');
	}
	public function handleItemJuggler_cron_daily(DataContainer $dc)
	{
		$this->handleCronJob($dc, 'daily');
	}
	public function handleItemJuggler_cron_weekly(DataContainer $dc)
	{
		$this->handleCronJob($dc, 'weekly');
	}
	
	
	protected function handleCronJob(DataContainer $dc,$strCronType)
	{
		$this->import("Database");
		$objDJ = $this->Database->prepare("SELECT id,dca_trigger_cron_list,query_string,append_data FROM tl_itemjuggler WHERE dca_trigger_cron=1")->execute();
				
		while ($objDJ->next())
		{
			$arrTrigger = deserialize($objDJ->dca_trigger_cron_list);
			
			foreach ($arrTrigger as $trigger)
			{

				if ($trigger['cron_type']==$strCronType)
				{	
					
					
				}	
			}		
		}
		
	}
	
	protected function handleJob(DataContainer $dc,$strCallback)
	{
		$this->import("Database");
		$objDJ = $this->Database->prepare("SELECT * FROM tl_itemjuggler WHERE dca_trigger_callback=1")->execute();
				
		while ($objDJ->next())
		{
			$arrTrigger = deserialize($objDJ->dca_trigger_callback_list);
			
			foreach ($arrTrigger as $trigger)
			{

				if (($trigger['tables']==$dc->table) &&
					($trigger['callbacks'] == $strCallback))
				{
					$arrFilter = deserialize($objDJ->filterpart);
                    $arrWhere = array(); 
                    
                    foreach ($arrFilter  as $filter)
                    {

                        $strWhere = "";
                        switch ($filter['field_type'])
                        {
                            case "exactly" :    $strWhere ="%s='%s'";
                                                break;
                            case "startsWith" : $strWhere ="%s LIKE '%s%%'";
                                                break;
                            case "endsWith" : $strWhere ="%s LIKE '%%%s'";
                                                break;
                            case "like" : $strWhere ="%s LIKE '%%%s%%'";
                                                break;
                            case "isEmpty" : $strWhere ="%s=''";
                                                break; 
                           
                        }
                        
                        if ($strWhere!='')
                        {
                            if ($filter['negate'])
                                $strWhere = sprintf("!(%s)",$strWhere);
                            
                            $arrWhere[] = sprintf($strWhere,$objDJ->source_column,$filter['query']); 

                        }
                 
                    }
                    
                    $strWhereQuery = implode(" AND ",$arrWhere);
                    
                   
                    $arrActionRows = deserialize($objDJ->action);
                    
                    foreach ($arrActionRows as $arrAction)
                    {
                         $objQuery = $this->Database->prepare("SELECT * FROM ".$objDJ->source_table." WHERE ".$strWhereQuery)->execute();
                         
                        while ($objQuery->next())
                        {   
                            
                            $strUpdate='';
                            
                            $strSourceColumn = $objDJ->source_column;
    
                            switch ($arrAction['action_type'])
                            {
                                case 'prepend' : $strUpdate = sprintf("%s%s",$arrAction['replacement'],$objQuery->$strSourceColumn);
                                    break; 
                                case 'postpend' : $strUpdate = sprintf("%s%s",$objQuery->$strSourceColumn,$arrAction['replacement']);
                                    break;
                                
                                case 'rewriteTo' : $strUpdate = sprintf("%s",$arrAction['replacement']);
                                    break;
                                case 'purge' : $strUpdate = '';
                                    break;
                            }

                            if ($strUpdate!='')
                            {
                                $objUpdate = $this->Database->prepare("UPDATE ".$objDJ->source_table." SET ".$arrAction['dest_column']."=? WHERE id=?")->execute($strUpdate,$objQuery->id);
                                
                            
                            }
                        }
                    }
				}	
			}		
		}
		
	}
}