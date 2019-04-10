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
		//$this->RegisterTimer("Schalten ein", $this->ReadPropertyInteger("Schalten ein"), 'IPSDOS_SchaltenEin($_IPS[\'TARGET\']);');
		//$this->RegisterTimer("Schalten ein", 0, 'IPSDOS_SchaltenEin($_IPS[\'TARGET\']);');
		
		//$this->RegisterTimer("Schalten aus", $this->ReadPropertyInteger("Schalten aus"), 'IPSDOS_SchaltenAus($_IPS[\'TARGET\']);');
		//$this->RegisterTimer("Schalten aus", 0, 'IPSDOS_SchaltenAus($_IPS[\'TARGET\']);');
		
		//$this->RegisterTimer("Install", $this->ReadPropertyInteger("Install"), 'IPSDOS_Install($_IPS[\'TARGET\']);');
		//$this->RegisterTimer("Install", 0, 'IPSDOS_Install($_IPS[\'TARGET\']);');
		
		//Erstellen eines Variablenprofile für Typ Integer
		//$associations = [];
        //$associations[] = ['Wert' => 1, 'Name' => 'Anwesend'];
        //$associations[] = ['Wert' => 0, 'Name' => 'Abwesend'];
        //$this->CreateVarProfile('IPSDOS.Status', IPS_INTEGER, '', 0, 0, 0, 1, 'Heart', $associations);
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
		if ($name != '' && $maxTime != '' && $idSwitch !=) {			
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
	   
	   
		// Variable anlegen im Ipsymcon vom Typ Integer und vom Profil IPSDOS.Status wenn $ok1 true (Module IO) ist
		//$this->MaintainVariable("user1Active", $user1, IPS_INTEGER, "IPSDOS.Status", 0, $ok1);
		//$this->LoggingEnable($user1); //Logging für diese Variable einschalten
				
		// ab dem Device2 nur noch Variable löschen wenn nicht alles ausgefüllt Instanz bleibt aktiv
		//if ($device2 != '' && $user2 != '' && $macaddress2 != '') {
        //  $this->MaintainVariable("user2Active", $user2, IPS_INTEGER, "IPSDOS.Status", 0, true);        
		//  $this->LoggingEnable($user2);	//Logging für diese Variable einschalten
        //} 
		//else {
		//	$this->MaintainVariable("user2Active", $user2, IPS_INTEGER, "IPSDOS.Status", 0, false); 
        //}
		
		//..
	  }
	 }
		

         public function Install() {
			 

		}
  
				
}