<?
    class IPSTimer extends IPSModule {
        public function Create()
        {
            //Never delete this line!
            parent::Create();
            $this->RegisterPropertyInteger("Duration", 1);
            $this->RegisterPropertyInteger("OutputID", 0);
            $this->RegisterTimer("OffTimer", 0, "TIMER_Stop(\$_IPS['TARGET']);");
            $this->RegisterVariableBoolean("Active", "IPSTimer aktiv", "~Switch");
			$this->RegisterVariableBoolean("InputTriggerID", "gesetzt", "~Switch");
            $this->EnableAction("Active");
        }
        public function ApplyChanges() {
            //Never delete this line!
            parent::ApplyChanges();
			$triggerID = $this->GetIDForIdent("InputTriggerID");
            $this->RegisterMessage($triggerID, 10603 /* VM_UPDATE */);
        }
		
		/*
        public function MessageSink ($TimeStamp, $SenderID, $Message, $Data) {
            $triggerID = $this->ReadPropertyInteger("InputTriggerID");
            if (($SenderID == $triggerID) && ($Message == 10603) && (boolval($Data[0]))) {
				if (GetValue($this->GetIDForIdent("gesetzt"))){
                $this->Stop();	
				SetValue($this->GetIDForIdent("gesetzt"), false);
				return;
                }		
			}


				if (!GetValue($this->GetIDForIdent("gesetzt"))){
                  $this->Start();
				  SetValue($this->GetIDForIdent("gesetzt"), true);
                }		
        }
		*/
		
		public function MessageSink ($TimeStamp, $SenderID, $Message, $Data) {
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
					
					$EreignisID = @IPS_GetEventIDByName("IPSTimerEvent", $this->GetIDForIdent("InputTriggerID"));
                    if ($EreignisID === false)
					{
					$eid = IPS_CreateEvent(0);                									  //Ausgelöstes Ereignis
					IPS_SetName($eid, "IPSTimerEvent");								              //Name dem Event zuordnen
					IPS_SetEventTrigger($eid, 4, $this->ReadPropertyInteger("OutputID"));         //Bei Änderung von Variable mit ID 15754
					IPS_SetEventTriggerValue($eid, true);		                                  //Nur auf TRUE Werte auslösen
					IPS_SetParent($eid, $this->GetIDForIdent("InputTriggerID"));                  //Ereigniss zuordnen zu Variable "gesetzt"     									  //Ereignis zuordnen
					// Füge eine Regel mit der ID 2 hinzu: Variable "gesetzt" == true
					IPS_SetEventCondition($eid, 0, 0, 0);
                    IPS_SetEventConditionVariableRule($eid, 0, 2, $this->GetIDForIdent("InputTriggerID"), 0, true);
					IPS_SetEventActive($eid, true);          								      //Ereignis aktivieren
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
        }
        public function Stop(){
			SetValue($this->GetIDForIdent("InputTriggerID"), false);
            $this->SwitchVariable(false);
            $this->SetTimerInterval("OffTimer", 0);
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