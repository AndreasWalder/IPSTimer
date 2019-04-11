<?
    class IPSTimer extends IPSModule {
        public function Create()
        {
            //Never delete this line!
            parent::Create();
            $this->RegisterPropertyInteger("Duration", 1);
            $this->RegisterPropertyInteger("OutputID", 0);
			
            $this->RegisterTimer("OffTimer", 0, "TIMER_Stop(\$_IPS['TARGET']);");
			$this->RegisterTimer("Update", 0, "TIMER_Update(\$_IPS['TARGET']);");
			
            $this->RegisterVariableBoolean("Active", "aktiv", "~Switch");
			$this->RegisterVariableBoolean("InputTriggerID", "gesetzt", "~Switch");
			
            $this->EnableAction("Active");
        }
		
		
		// Variablenprofile erstellen
		private function CreateVarProfile($Name, $ProfileType, $Suffix, $MinValue, $MaxValue, $StepSize, $Digits, $Icon, $Asscociations = '')
		{
			if (!IPS_VariableProfileExists($Name)) {
				IPS_CreateVariableProfile($Name, $ProfileType);
				IPS_SetVariableProfileText($Name, '', $Suffix);
				IPS_SetVariableProfileValues($Name, $MinValue, $MaxValue, $StepSize);
				IPS_SetVariableProfileDigits($Name, $Digits);
				IPS_SetVariableProfileIcon($Name, $Icon);
				if ($Asscociations != '') {
					foreach ($Asscociations as $a) {
						$w = isset($a['Wert']) ? $a['Wert'] : '';
						$n = isset($a['Name']) ? $a['Name'] : '';
						$i = isset($a['Icon']) ? $a['Icon'] : '';
						$f = isset($a['Farbe']) ? $a['Farbe'] : 0;
						IPS_SetVariableProfileAssociation($Name, $w, $n, $i, $f);
					}
				}
			}
			else {
			 IPS_SetVariableProfileText($Name, '', $Suffix);
			 IPS_SetVariableProfileValues($Name, $MinValue, $MaxValue, $StepSize);
			 IPS_SetVariableProfileDigits($Name, $Digits);
			 IPS_SetVariableProfileIcon($Name, $Icon);
			}
		}
		
		
        public function ApplyChanges() {
            //Never delete this line!
            parent::ApplyChanges();
			//Erstellen eines Variablenprofile für Typ Integer
			$associations = '';
			//$associations[] = ['Wert' => 1, 'Name' => 'Anwesend'];
			//$associations[] = ['Wert' => 0, 'Name' => 'Abwesend'];
			$this->CreateVarProfile('IPSTimer.Status', 1, ' min', 0, $this->ReadPropertyInteger("Duration"), 0, 1, 'Clock', $associations);			
			$this->RegisterVariableInteger("Ablaufzeit", "Ablaufzeit", "IPSTimer.Status");
			$triggerID = $this->GetIDForIdent("InputTriggerID");
            $this->RegisterMessage($triggerID, 10603 /* VM_UPDATE */);
			
			SetValue($this->GetIDForIdent("Ablaufzeit"), $this->ReadPropertyInteger("Duration"));
        }
		
		public function MessageSink ($TimeStamp, $SenderID, $Message, $Data) {
			
			if (!GetValue($this->GetIDForIdent("InputTriggerID"))) {
			   SetValue($this->GetIDForIdent("Ablaufzeit"), 0);
			}
			
            //$triggerID = $this->ReadVariableBoolean("InputTriggerID");
			$triggerID = $this->GetIDForIdent("InputTriggerID");
            if (($SenderID == $triggerID) && ($Message == 10603) && (boolval($Data[0]))) {
                $this->Start();
            }
        }
		
        public function RequestAction($Ident, $Value) {
            switch($Ident) {
                case "Active":
                    $this->SetActive($Value);
					
					$EreignisID = @IPS_GetEventIDByName("IPSTimerEventAn", $this->GetIDForIdent("InputTriggerID"));
                    if ($EreignisID === false)
					{
					$eidan = IPS_CreateEvent(0);                									  //Ausgelöstes Ereignis 		
					IPS_SetEventTrigger($eidan, 4, $this->ReadPropertyInteger("OutputID"));         //Bei Änderung von Variable mit ID 15754
					IPS_SetEventTriggerValue($eidan, true);		                                  //Nur auf TRUE Werte auslösen
					// Füge eine Regel mit der ID 2 hinzu: Variable "InputTriggerID" == true
					IPS_SetEventCondition($eidan, 0, 0, 0);
                    IPS_SetEventConditionVariableRule($eidan, 0, 1, $this->GetIDForIdent("InputTriggerID"), 0, false);
					IPS_SetEventConditionVariableRule($eidan, 0, 2, $this->GetIDForIdent("Active"), 0, true);
                    IPS_SetEventTriggerSubsequentExecution($eidan, true); 
					IPS_SetParent($eidan, $this->GetIDForIdent("InputTriggerID"));                  //Ereigniss zuordnen zu Variable "InputTriggerID"  
					IPS_SetIdent($eidan, "IPSTimerEventAn");
					IPS_SetName($eidan, "IPSTimerEventAn");								              //Name dem Event zuordnen
					IPS_SetEventActive($eidan, true);          								      //Ereignis aktivieren
					IPS_SetEventTriggerValue($eidan, true);		                                  //Nur auf TRUE Werte auslösen
					}
					
					$EreignisID = @IPS_GetEventIDByName("IPSTimerEventOFF", $this->GetIDForIdent("InputTriggerID"));
                    if ($EreignisID === false)
					{
					$eidaus = IPS_CreateEvent(0);                									  //Ausgelöstes Ereignis	
					IPS_SetEventTrigger($eidaus, 4, $this->ReadPropertyInteger("OutputID"));         //Bei Änderung von Variable mit ID 15754
					IPS_SetEventTriggerValue($eidaus, false);		                                  //Nur auf false Werte auslösen
					// Füge eine Regel mit der ID 2 hinzu: Variable "InputTriggerID" == true
					IPS_SetEventCondition($eidaus, 0, 0, 0);
                    IPS_SetEventConditionVariableRule($eidaus, 0, 1, $this->GetIDForIdent("InputTriggerID"), 0, true);
					IPS_SetEventConditionVariableRule($eidaus, 0, 2, $this->GetIDForIdent("Active"), 0, true);
                    IPS_SetEventTriggerSubsequentExecution($eidaus, true); 
					IPS_SetParent($eidaus, $this->GetIDForIdent("InputTriggerID"));                  //Ereigniss zuordnen zu Variable "InputTriggerID"  
					IPS_SetIdent($eidaus, "IPSTimerEventOFF");
					IPS_SetName($eidaus, "IPSTimerEventOFF");								              //Name dem Event zuordnen
					IPS_SetEventActive($eidaus, true);          								      //Ereignis aktivieren
					IPS_SetEventTriggerValue($eidaus, false);		                                  //Nur auf false Werte auslösen
					}
					
					
                    break;
                default:
                    throw new Exception("Invalid ident");
            }
        }
        
        public function SetActive(bool $Value) {
            SetValue($this->GetIDForIdent("Active"), $Value);
        }
        
        public function Start(){
            if (!GetValue($this->GetIDForIdent("Active"))){
                return;
            }
            $duration = $this->ReadPropertyInteger("Duration");
            $this->SwitchVariable(true);
            $this->SetTimerInterval("OffTimer", $duration * 60 * 1000);
			$this->SetTimerInterval("Update", 60 * 1000);
			SetValue($this->GetIDForIdent("Ablaufzeit"), $duration);
        }
		
        public function Stop(){
			SetValue($this->GetIDForIdent("InputTriggerID"), false);
            $this->SwitchVariable(false);
            $this->SetTimerInterval("OffTimer", 0);
			SetValue($this->GetIDForIdent("Ablaufzeit"), 0);
        }
		
		public function Update(){
			if (GetValue($this->GetIDForIdent("Ablaufzeit")) == 0) {
			   $this->SetTimerInterval("Update", 0);
			   return;
			}
			$UpdateTimer = GetValue($this->GetIDForIdent("Ablaufzeit"));
			$UpdateTimer = $UpdateTimer - 1;
            SetValue($this->GetIDForIdent("Ablaufzeit"), $UpdateTimer);
        }
		
        private function SwitchVariable(bool $Value){
            $outputID = $this->ReadPropertyInteger("OutputID");
            $object = IPS_GetObject($outputID);
            $variable = IPS_GetVariable($outputID);
            $actionID = $this->GetProfileAction($variable);
            //Quit if actionID is not a valid target
            if($actionID < 10000){
                echo $this->Translate("Die Ausgabevariable hat keine Variablenaktion! (Aktion hinzufügen)");
                return;
            }
            $profileName = $this->GetProfileName($variable);
            //If we somehow do not have a profile take care that we do not fail immediately
            if($profileName != "") {
                //If we are enabling analog devices we want to switch to the maximum value (e.g. 100%)
                if ($Value) {
                    $actionValue = IPS_GetVariableProfile($profileName)['MaxValue'];
                } else {
                    $actionValue = 0;
                }
                //Reduce to boolean if required
                if($variable['VariableType'] == 0) {
                    $actionValue = ($actionValue > 0);
                }
            } else {
                $actionValue = $Value;
            }
            if(IPS_InstanceExists($actionID)){
                IPS_RequestAction($actionID, $object['ObjectIdent'], $actionValue);
            } else if(IPS_ScriptExists($actionID)) {
                echo IPS_RunScriptWaitEx($actionID, Array("VARIABLE" => $outputID, "VALUE" => $actionValue));
            }
        }
        private function GetProfileName($variable){
            if($variable['VariableCustomProfile'] != ""){
                return $variable['VariableCustomProfile'];
            } else {
                return $variable['VariableProfile'];
            }
        }
        private function GetProfileAction($variable){
            if($variable['VariableCustomAction'] > 0){
                return $variable['VariableCustomAction'];
            } else {
                return $variable['VariableAction'];
            }
        }
    }
?>