# IPSTimer

Modul für IP-Symcon ab Version 5.0

## Dokumentation

**Inhaltsverzeichnis**

1. [Funktionsumfang](#1-funktionsumfang)
2. [Voraussetzungen](#2-voraussetzungen)
3. [Installation](#3-installation)
4. [Konfiguration](#4-konfiguration)
5. [Anhang](#5-anhang)

## 1. Funktionsumfang




## 2. Voraussetzungen

 - IPS 5.x
 

## 3. Installation

### a. Laden des Moduls

Die IP-Symcon (min Ver. 5.x) Konsole öffnen. Im Objektbaum unter Kerninstanzen die Instanz __*Modules*__ durch einen doppelten Mausklick öffnen.

In der _Modules_ Instanz rechts oben auf den Button __*Hinzufügen*__ drücken.

In dem sich öffnenden Fenster folgende URL hinzufügen:

`https://github.com/AndreasWalder/IPSDeviceOnlineService.git`

und mit _OK_ bestätigen.

Anschließend erscheint ein Eintrag für das Modul in der Liste der Instanz _Modules_

### b. Einrichtung in IPS

In IP-Symcon nun Instanz hinzufügen_ (_CTRL+1_) auswählen unter der Kategorie, unter der man die Instanz hinzufügen will, und Hersteller _(sonstiges)_ und als Gerät _IPSDeviceOnlineService_ auswählen.

Dann muss man die IP-Adresse, den Namen des Gerätes und die MacAdresse für die genaue Bestimmung des Gerätes eintragen und das Gerät aktiv schalten.

Intervall Zeit kann geändert werden Standart 5 Min.


## 4. Konfiguration:

### Variablen

| Eigenschaft               | Typ      | Standardwert | Beschreibung |
| :-----------------------: | :-----:  | :----------: | :----------------------------------------------------------------------------------------------------------: |
| Device1                   | integer  |              | beliebiger Gerätenamen Namen für Statusanzeige |
| Device2                   | integer  |              | beliebiger Gerätenamen Namen für Statusanzeige |
| Device3                   | integer  |              | beliebiger Gerätenamen Namen für Statusanzeige |
| Device4                   | integer  |              | beliebiger Gerätenamen Namen für Statusanzeige |


### Schaltflächen

| Bezeichnung                  | Beschreibung |
| :--------------------------: | :------------------------------------------------: |
| Debug                        | zum Herausfinden der MacAdresse                    |
| Update                       | zum probieren des Instanz Modules                  |


## 5. Anhang

GUIDs
- Modul: `{CB5A1DFA-6A35-36EC-5B97-E836850A9B61}
- Instanzen:
  - TestAndreas: `{746E1F5C-80D0-41A9-60CF-56B0AEEFAC08}

