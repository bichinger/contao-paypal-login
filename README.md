# Contao Modul paypal login
Dieses Modul erlaubt es, einen bezahlten Mitgliederbereich auf Basis der Contao-Registrierung und Paypal zu realisieren.

## Anforderungen

* Contao 3.5.x
* PHP 5.6 mit curl, json und openssl Extensions

## Installationsanleitung

1. Lege das Modul unter TL_ROOT/system/modules/ ab.
2. Gehe in das Installtool von Contao http://meincontao.de/contao/install.php und aktualisiere die Datenbank.
3. Lege das Modul zur Registrierung an und füge es auf einer Seite oder im Seitenlayout deiner Seite hinzu.
4. Lege eine Mitgliedergruppe an in die Mitglieder verschoben werden sollen die erfolgreich bezahlt haben.
5. Lege im Backend Seiten an für:
    * den erfolgreichen Geldtransfer.
    * den fehlerhaften Geldtransfer.
    * den abgebrochenen Geldtransfer.
6. Öffne im linken Backend Modules Bereich Paypal-Login / Settings und fülle alle Felder aus.
