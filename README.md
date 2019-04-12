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

 - IPSTimer zum automatischen Abschalten von Geräten nach eingestellter Zeit
 - Ablaufzeit wird in der Webfront angezeigt
 - Timer kann aktiviert und deaktiviert werden


## 2. Voraussetzungen

 - IPS 5.x
 

## 3. Installation

### a. Laden des Moduls

Die IP-Symcon (min Ver. 5.x) Konsole öffnen. Im Objektbaum unter Kerninstanzen die Instanz __*Modules*__ durch einen doppelten Mausklick öffnen.

In der _Modules_ Instanz rechts oben auf den Button __*Hinzufügen*__ drücken.

In dem sich öffnenden Fenster folgende URL hinzufügen:

`https://github.com/AndreasWalder/IPSTimer.git`

und mit _OK_ bestätigen.

Anschließend erscheint ein Eintrag für das Modul in der Liste der Instanz _Modules_

### b. Einrichtung in IPS

In IP-Symcon nun Instanz hinzufügen_ (_CTRL+1_) auswählen unter der Kategorie, unter der man die Instanz hinzufügen will, und Hersteller _(sonstiges)_ und als Gerät _IPSTimer_ auswählen.

Achtung "Ereignisse kontrollieren und richtingen Wert setzen (TRUE/FALSE)"!!


## 4. Konfiguration:

### Variablen

| Eigenschaft               | Typ      | Standardwert | Beschreibung |
| :-----------------------: | :-----:  | :----------: | :----------------------------------------------------------------------------------------------------------: |
| Variable zum Schalten     | integer  |              | Wert des schaltenden Objekts                   |
| Dauer                     | integer  |      0 min   | Abschaltzeit in Minunten                       |



## 5. Anhang

GUIDs
- Modul: `{CB5A1DFA-6A35-36EC-5B97-E836850A9B61}

