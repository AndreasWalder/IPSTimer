<?
    class IPSTimer extends IPSModule {
        public function Create()
        {
            //Never delete this line!
            parent::Create();
            $this->RegisterPropertyInteger("OutputID", 0);
            $this->RegisterPropertyInteger("Duration", 1);
            $this->RegisterTimer("OffTimer", 0, "TIMER_Stop(\$_IPS['TARGET']);");
            $this->RegisterVariableBoolean("Active", "IPSTimer aktiv", "~Switch");
			$this->RegisterVariableBoolean("Gesetzt", "IPSTimer gesetzt", "~Switch");
            $this->EnableAction("Active");
        }
        public function ApplyChanges() {
            //Never delete this line!
            parent::ApplyChanges();			
            $triggerID = $this->GetIDForIdent("Gesetzt");
            $this->RegisterMessage($triggerID, 10603 /* VM_UPDATE */);
        }
        public function MessageSink ($TimeStamp, $SenderID, $Message, $Data) {
            $triggerID = $this->GetIDForIdent("Gesetzt");
            if (($SenderID == $triggerID) && ($Message == 10603) && (boolval($Data[0]))) {
                $this->Start();
            }
        }
        public function RequestAction($Ident, $Value) {
            switch($Ident) {
                case "Active":
                    $this->SetActive($Value);
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
            $this->SwitchVariable(false);
            $this->SetTimerInterval("OffTimer", 0);
        }
        private function SwitchVariable(bool $Value){
            $outputID = $this->ReadPropertyInteger("OutputID");
            $object = IPS_GetObject($outputID);
            $variable = IPS_GetVariable($outputID);
            $actionID = $this->GetProfileAction($variable);
			SetValue($this->GetIDForIdent("Gesetzt"), $Value);
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