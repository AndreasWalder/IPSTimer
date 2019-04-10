<?php
set_time_limit(180);
// Constants will be defined with IP-Symcon 5.0 and newer
if (!defined('KR_READY')) {
    define('KR_READY', 10103);
}
if (!defined('IPS_BOOLEAN')) {
    define('IPS_BOOLEAN', 0);
}
if (!defined('IPS_INTEGER')) {
    define('IPS_INTEGER', 1);
}
if (!defined('IPS_FLOAT')) {
    define('IPS_FLOAT', 2);
}
if (!defined('IPS_STRING')) {
    define('IPS_STRING', 3);
}



class IPSTimer extends IPSModule
 {
	// Wird beim Setup vom Modul aufgerufen (ganz am Anfang)
    public function Create()
    {
		
        parent::Create();
		
		//Erstellen von Verlinkungen zum Modul
        $this->RegisterPropertyString('name', '');
		$this->RegisterPropertyString('maxTime', '');
		$this->RegisterPropertyString('idSwitch', '');	
        $this->RegisterPropertyBoolean('active', 'false');
		
		//Timer erstellen und zum durchreichen der Schaltflächen im Modul 
		$this->RegisterTimer("SchaltenEin", 0, 'IPSDOS_SchaltenEin($_IPS[\'TARGET\']);');	
		$this->RegisterTimer("SchaltenAus", 0, 'IPSDOS_SchaltenAus($_IPS[\'TARGET\']);');
		$this->RegisterTimer("Install", 0, 'IPSDOS_Install($_IPS[\'TARGET\']);');
		
		//Erstellen eines Variablenprofile für Typ Integer
		//$associations = [];
        //$associations[] = ['Wert' => 1, 'Name' => 'Anwesend'];
        //$associations[] = ['Wert' => 0, 'Name' => 'Abwesend'];
        //$this->CreateVarProfile('IPSDOS.Status', IPS_INTEGER, '', 0, 0, 0, 1, 'Heart', $associations);
    }
		
    // Wird aufgerufen wenn im Modul was verändert wird
    public function ApplyChanges()
    {
        parent::ApplyChanges();
		
		//Werte der Variablen laden
        $name = $this->ReadPropertyString('name');
        $maxTime = $this->ReadPropertyString('maxTime');
        $idSwitch = $this->ReadPropertyString('idSwitch');
        $active = $this->ReadPropertyBoolean('active');
		
		//Timer Interval setzen für Update Function
		//$this->SetTimerInterval("Install", $this->ReadPropertyInteger("Install")*1000*60);

		$ok1 = false;	
	
	   	
		// Instanz Status setzen (aktiv -> inaktiv)
		if ($name != '' && $maxTime != '' && $idSwitch != '' && $active == true) {			
			   // Zeigt Info neben der Instanz
			   $this->SetSummary("Status OK");			   
               $ok1 = true;		
               // setzt Instanz Status auf aktiv			   
               $this->SetStatus(102);	   
        } 
		else {
			 $this->SetSummary("Fehler");
			 $ok1 = false;		 
             $this->SetStatus(104);
        }
	   
	  
		if ($ok1 == true)
	  {	
	    $this->Install($name, $maxTime, $idSwitch);
	  }
	}
		

   public function SchaltenEin() {
		echo "SchaltenEin";	 	 
        return;
	}
	
	public function SchaltenAus() {
			echo "SchaltenAus";	  
            return;
    }

    public function Install(string $name, string $maxTime, string $idSwitch) {
		
			echo "Install Modul";
			
			$vpn = "Modul".$name.".".$maxTime; 
			
		   //Dummy anlegen
		    //$DummyObjektID = IPS_GetParent($_IPS['SELF']);
            //IPS_SetName ($DummyObjektID, $name);  //Dummy Instance umbenennen 

			//IPS_SetParent($_IPS['SELF'], $DummyObjektID);
			//IPS_SetHidden($_IPS['SELF'], true); //Objekt verstecken
			
			 if(IPS_VariableProfileExists($vpn)) { 
				IPS_DeleteVariableProfile($vpn);
			}
			//Timer Variable anlegen
			$this->IPS_CreateVariableProfile($vpn, 1); 
			$this->IPS_SetVariableProfileValues($vpn, 0, $maxTime, 0); 
			$this->IPS_SetVariableProfileIcon($vpn, "Hourglass"); 
			$this->IPS_SetVariableProfileAssociation($vpn, -3, "Aus", "", 0xFF0000); 
			$this->IPS_SetVariableProfileAssociation($vpn, 0, "%d ".$suffix, "", 0x00FF00); 
			$this->IPS_SetVariableProfileAssociation($vpn, $maxTime+2, "+".$maxTime." Ein", "", -1); 
			$vid = $this->CreateVariableByName($IPS_SELF, "Timer", 1); 
			$this->IPS_SetVariableCustomProfile($vid, $vpn); 
			$this->IPS_SetVariableCustomAction($vid, $_IPS['SELF']); 
			//Anfangswert setzen vom Timer
			$this->SetValue($vid, -3); 

			//Aktiv Variable Anlegen
			$vidAktive = $this->CreateVariableByName($IPS_SELF, "Aktive", 0); 
			$this->IPS_SetVariableCustomProfile($vidAktive, "~Switch"); 
			
			//Action Script für Aktive anlegen und mit Aktive verknüpfen
			$ScriptID = $this->IPS_CreateScript(0);
			$this->IPS_SetName($ScriptID, "Action Script");
			$this->IPS_SetParent($ScriptID, $vidAktive);
     
			$data ="<? \n SetValue(\$_IPS['VARIABLE'],"; 
			$data .=" \$_IPS['VALUE']);"; 
			$data .="\n?>"; 

			$this->IPS_SetScriptContent($ScriptID, $data);
			$this->IPS_SetVariableCustomAction($vidAktive, $ScriptID); 
			$this->SetValue($vidAktive, false); 

			//Aktiv Variable Anlegen
			$vidZeit = $this->CreateVariableByName($IPS_SELF, "Zeit max", 3); 
			$this->IPS_SetVariableCustomProfile($vidZeit, "Text"); 
			$ScriptIDZeit = $this->IPS_CreateScript(0);
			$this->IPS_SetName($ScriptIDZeit, "Action Script");
			$this->IPS_SetParent($ScriptIDZeit, $vidZeit);
			$dataZeit ="<? \n SetValue(\$_IPS['VARIABLE'],"; 
			$dataZeit .=" \$_IPS['VALUE']);"; 
			$dataZeit .="\n?>"; 
			$this->IPS_SetScriptContent($ScriptIDZeit, $dataZeit);
			$this->IPS_SetVariableCustomAction($vidZeit, $ScriptIDZeit); 
			$this->SetValueString($vidZeit, $max); 
			
			return;

    }
  
 }			