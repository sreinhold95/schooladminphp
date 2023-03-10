# Schulverwaltung

## Lizenz

Die Software steht unter der GPLv3 Lizenz.
## Beschreibung

Dies ist ein in PHP und JavaScript geschrieben Webanwendung, die ein Identitätsmanagement für Schulen bereit stellt.
Durch dieses Programm kann man Lehrer und Schülerdaten verwalten.
Es gibt verschieden Export/Import Möglichkeiten.
- iServ (Lehrer und Schüler Export)
- Webuntis (Import von Schüler Stammdaten)
- Schulportal Hessen (SPH-PAEDNET, Export der Schüler, Bereitstellung aktueller Flags, wie abgegangen und die aktuelle Mail der SuS)
- Schulportal Hessen (SPH-PaedORG Export von Lerngruppen)
- Untis (Import der GPU002.txt für Bidlung von Lerngruppen/Klassengruppen)
Es wird ein Rechte System abgebildet.
- Einschulung
- Klassenlehrer
- Administration (Sekretaritat/Schulleitung
- Abteilungsleitung (erweiterte Schulleitung)
- Administrator
Das Programm hat eine REST API als Schnittstelle zwischen Frontend(Javascript) und Backend(PHP, MySQL/MariaDB) dient.
Es wird pro SuS ein Stammbogen zur verfügung gestellt der als PDF exportierbar ist.Dort werden acuh die Erst-Passwörter auf der 2ten Seite ausgegeben.
