<h1>Click&amp;Meet - Anleitung</h1>
<h2>Installation und Einrichtung</h2>
<h3>1. Plugin installieren</h3>
<p>
    a. In Wordpress einloggen<br />
    b. Im Admin-Menü „Plugins“ wählen<br />
    c. „Installieren“ wählen.<br />
    d. Oben auf der Seite „Plugin hochladen“ wählen.<br />
    e. Auf „Durchsuchen“ klicken.<br />
    f. Das Plugin auf dem Computer suchen und auswählen.<br />
		g. Wordpress fragt, ob du das Plugin installieren möchten – oder falls es<br />
    bereits installiert ist, ob du das installierte Plugin aktualisieren<br />
    möchten.<br /><br/>
    Stelle sicher, dass du das Plugin aktualisieren möchtest. Achten darauf,<br />
    dass du kein Plugin überschreibst, dassevtl. zufällig unter dem selben<br />
    Namen abgelegt ist.<br />
</p>


<h3>2. Plugin aktivieren</h3>
<p>
    a. Im Plugin-Menü das Plugin heraussuchen.<br />
    b. Auf <strong>Aktivieren </strong>klicken.<br />
    c. Das Plugin wird aktiviert.<br />
</p>

<h3>3. Plugin-aufrufen</h3>
    Rufe das Plugin über den Menüpunkt „Click and Meet – Kalender“ auf.<br/>
    Es sollte der Bildschirm für die Erstinstallation der Datenbank angezeigt<br />
    werden.
</p>
<p>
    So lange die Installation der Datenbank noch nicht abgeschlossen ist, sind
    die weiteren Menüpunkte des Plugins auch noch nicht zu sehen.
</p>
<br clear="all"/>
<h3>4. Datenbank Erstinstallation</h3>
<p>
    Click&amp;Meet verwendet eigene Tabellen für die Kalenderdaten. Dadurch
    wird deine Wordpress Datenbank nicht mit den vielen Einträgen belastet, und
    Click&amp;Meet lässt sich dadurch bei Bedarf ganz einfach wieder restlos
    entfernen.
</p>
<h3>5. Plugin im Frontend einrichten</h3>
<p>
    Damit das Plugin auf der Webseite gesehen werden kann, muss ein Shortcode
    eingerichtet werden. Ein Shortcode ist ein kleiner Text-„Schnipsel“. Auf
    der Webseite wird der Shortcode automatisch durch das eigentliche Element
    ersetzt.
</p>
<p>
    Du kannst Shortcodes auf Seiten oder in Beiträgen hinterlegen. Wähle oder
    erstelle eine neue Seite oder einen Beitrag, und füge den Shortcode über
    den Editor ein.
</p>
<p>
    Wordpress hat für Shortcodes einen eigenen Block-Typen. Auch Templates wie
    Divi unterstützen den Shortcode Block.
</p>
<p>
    <strong>Shortcode:</strong>
</p>
<p>
    Das ist der Text, der im Shortcode eingefügt werden muss:
</p>
<code>
    [clickandmeet_cal /]
</code>
<br clear="all"/>
<p>
    <strong>Shortcode-Optionen:</strong>
</p>
<p>
    Um deine Ausgabe weiter zu individualisieren, kannst du eine dieser
    Shortcode-Optionen wählen. Die Optionen werden in den Shortcode
    hineingeschrieben. Möchtest du also bspw. keine Auswahl für Läden anzeigen,
    und eine Anzeige der Zeit „von bis“ im Kalender, kannst du den Shortcode so
    einstellen:
</p>
<code>
    [clickandmeet_cal ladenzeigen=“0“ time=“from_to“ /]
</code>
<p>
    Alle Optionen in der Übersicht:
</p>
<table cellspacing="0" cellpadding="6" border="1">
    <tbody>
        <tr>
            <td width="201" valign="top">
                <p>
                    ladenzeigen=
                </p>
            </td>
            <td width="201" valign="top">
                <p>
                    1 oder 0
                    <br/>
                    (Standard: 1)
                </p>
            </td>
            <td width="201" valign="top">
                <p>
                    Legt fest, ob der Kalender ein Auswahlfeld für den Laden
                    anzeigt.
                </p>
            </td>
        </tr>
        <tr>
            <td width="201" valign="top">
                <p>
                    ladenid=
                </p>
            </td>
            <td width="201" valign="top">
                <p>
                    ID eines Ladens.
                    <br/>
                    (Standard: 0)
                </p>
            </td>
            <td width="201" valign="top">
                <p>
                    Damit kann eine Vorauswahl im Auswahlfeld getroffen werden.
                    Die ID finden Sie in der Liste der eingestellten Läden in
                    der ersten Spalte.
                </p>
            </td>
        </tr>
        <tr>
            <td width="201" valign="top">
                <p>
                    teamzeigen=
                </p>
            </td>
            <td width="201" valign="top">
                <p>
                    1 oder 0
                    <br/>
                    (Standard: 1)
                </p>
            </td>
            <td width="201" valign="top">
                <p>
                    Legt fest, ob der Kalender ein Auswahlfeld für das Team
                    anzeigt.
                </p>
            </td>
        </tr>
        <tr>
            <td width="201" valign="top">
                <p>
                    teamid=
                </p>
            </td>
            <td width="201" valign="top">
                <p>
                    ID eines Teams.
                    <br/>
                    (Standard: 0)
                </p>
            </td>
            <td width="201" valign="top">
                <p>
                    Damit kann eine Vorauswahl im Feld für Team/Mitarbeiter
                    getroffen werden.
                </p>
            </td>
        </tr>
        <tr>
            <td width="201" valign="top">
                <p>
                    selectlocationfirst=
                </p>
            </td>
            <td width="201" valign="top">
                <p>
                    1 oder 0
                </p>
            </td>
            <td width="201" valign="top">
                <p>
                    Legt fest, dass erst ein Laden ausgewählt werden muss,
                    bevor der Kalender angezeigt wird. (Kalender wird dann
                    automatisch eingeblendet).
                    <br/>
                    Ist selectteamfirst aktiviert, wird diese Einstellung
                    ignoriert!
                </p>
            </td>
        </tr>
        <tr>
            <td width="201" valign="top">
                <p>
                    selectteamfirst=
                </p>
            </td>
            <td width="201" valign="top">
                <p>
                    1 oder 0
                </p>
            </td>
            <td width="201" valign="top">
                <p>
                    Legt fest, dass erst ein Team ausgewählt erden muss, bevor
                    der Kalender angezeigt wird.
                </p>
            </td>
        </tr>
        <tr>
            <td width="201" valign="top">
                <p>
                    time
                </p>
            </td>
            <td width="201" valign="top">
                <p>
                    duration
                    <br/>
                    oder
                    <br/>
                    from_to
                </p>
            </td>
            <td width="201" valign="top">
                <p>
                    duration: Legt fest, dass die Termin-Dauer angezeigt wird,
                    in der Form: 3 Stunden 15 Minuten
                </p>
                <p>
                    from_to: Legt fest, dass die Zeit von-bis angezeigt wird,
                    also bspw. 10:00 – 12:00 Uhr
                    <br/>
                    <br/>
                    Ist time nicht angegeben, wird die Dauer gar nicht
                    angezeigt.
                </p>
            </td>
        </tr>
    </tbody>
</table>
<h3>6. Läden verwalten</h3>
<p>
    Mit Läden meinen wir den Ort der Veranstaltung. Das kann dein Laden sein,
    oder ein Kongresszentrum. Wo auch immer deine Kunden zum Zeitpunkt des
    Termines erscheinen.
</p>
<p>
    Bearbeite zunächst den voreingestellten Laden. Füge anschließend bei Bedarf
    weitere Läden hinzu.
    <br/>
    <br/>
    Du kannst hier außerdem einstellen, ob eine Informations-Mail über die
    Buchung an den Laden geschickt werden soll.
    <br/>
    Außerdem kannst du einen individuellen Kundentext je Laden einstellen, für
    die Bestätigungs-Mail die an den Kunden gesendet wird.
</p>
<h3>7. Team/Mitarbeiter</h3>
<p>
    Click&amp;Meet lässt sich nicht nur nach Läden einstellen, sondern noch
    eine Ebene tiefer. Hast du ein Möbelhaus oder einen Baumarkt, und dort
    mehrere Abteilungen?
    <br/>
    Führst du mehrere Restaurants? Alles das lässt sich über mehrere Ebenen
    abbilden:
</p>
<p>
    Lege hier eine Mitarbeiter an, ein Team, eine Abteilung – oder im Falle
    eines Restaurants die einzelnen Tische, bei denen deine Besucher ihre
    Termine reservieren können.
</p>
<p>
    So behältst du die Übersicht, wo deine Besucher ihren Termin haben.
</p>
<p>
    Du kannst hier außerdem einstellen, ob eine Informations-Mail über die
    Buchung an das Team/den Mitarbeiter geschickt werden soll.
    <br/>
    Außerdem kannst du einen individuellen Kundentext je Team/Laden einstellen,
    für die Bestätigungs-Mail die an den Kunden gesendet wird. Diese
    Einstellung überschreibt den Text der Informationsmail aus der Einstellung
    Läden!
</p>
<br clear="all"/>
<h3>8. Termine anlegen</h3>
<p>
    a) Einzeltermine
</p>
<p>
    Möchtest du einzelne Termine anlegen, eignet sich dafür die
    Kalender-Übersicht.
    <br/>
    Wähle im Admin:
    <br/>
    <br/>
    Click&amp;Meet Kalender
    <br/>
    <br/>
    <br/>
    Hier werden alle Termine für den ausgewählten Monat oder Tag angezeigt.
    <br/>
    Dabei kannst du die Termine auch selektieren, nach die von Ihnen erfassten
    Standorte und auch für jeden Mitarbeiter oder Abteilung.
    <br/>
    Wenn Sie Daten vom Kunde erfasst haben, werden diese hier ebenfalls
    angezeigt.
</p>
<p>
    Einen neuen Einzel-Termin erfasst du im rechten Bereich der Ansicht.
    <br/>
    <br/>
    Wenn du den Termin-Status auf Offen stellst, kann der Termin auf deiner
    Webseite gebucht werden.
</p>
<br clear="all"/>
<p>
    b) Termin-Generierung für viele Einzeltermine
</p>
<p>
    Über den Menüpunkt „Termin-Assistent“ können sehr viele Termine auf einmal
    eingestellt werden.
</p>
<p>
    I) Wähle einen Laden
</p>
<p>
    II) Wähle ein Team/Mitarbeiter
</p>
<p>
    III) Wähle den Zeitraum, über den die Termine angelegt werden sollen.
    <br/>
    Urlaubszeiten kannst du hier schonmal aussparen – am Ende des Vorganges
    kannst du weitere Zeiträume mit den gleichen Uhrzeiten einstellen, wenn du
    den Assistenten nicht schließt.
</p>
<p>
    IV) Wähle die Tage, an denen Termine angelegt werden sollen, und die
    Uhrzeiten.
</p>
<p>
    V) Wähle die Dauer pro Termin. Der Assistent verteilt die freien Termine
    dann entsprechend der angegebenen Öffnungszeiten.
</p>
<p>
    VI)
</p>
<p>
    Der Assistent ermittelt beim Durchlauf alle Tage, die im angegebenen
    Zeitraum liegen, und stellt die Termine entsprechend der angegebenen
    Uhrzeiten ein in gewünschten Abständen ein.
</p>
<p>
    c) Lösch-Assistent
</p>
<p>
    Über den Lösch-Assistenten kannst du bequem viele Termine löschen.
</p>
<p>
    Bitte sei hier äußerst behutsam, damit du nicht aus Versehen wichtige Daten
    löschst. Lege ggf. vorher ein Backup an. Die Daten lassen sich, beinahe
    analog zur Termin-Generierung über verschiedene Parameter löschen.
</p>
<h3>9. Weitere Einstellungsmöglichkeiten</h3>
<p>
    <strong>
        Termin-Status
        <br/>
    </strong>
    Eine Übersicht über die verfügbaren Termin-Stati. Bei Bedarf kannst du hier
    weitere Stati hinzufügen.
</p>
<p>
    Hinweis: Die voreingestellten Stati werden vom System benötigt, und können
    deswegen nicht verändert oder gelöscht werden.
</p>
<p>
    <strong>
        Farben
        <br/>
    </strong>
    Über dieses Menü kannst du das Erscheinungsbild des Kalenders ändern. Das
    kommt insbesondere Nutzern ohne tiefere CSS Kenntnisse sehr entgegenkommt.
    Lediglich die hier verwendeten Farbcodes sollten im CSS-Format vergeben
    werden.
</p>
<p>
    <strong>Texte</strong>
</p>
<p>
    Über dieses Menü kannst du die einzelnen Texte des Kalenders verändern.
</p>
<p>
    <strong>
        Einstellungen (Formulare)
        <br/>
    </strong>
    Hier kannst du festlegen, welche Felder des Formulares für die
    Termin-Buchung vom Kunden ausgefüllt werden sollten.
</p>
<p>
    <strong>
        E-Mail Texte
        <br/>
    </strong>
    Über dieses Menü kannst du die E-Mail Texte verändern. Aktuell versendet
    das System nur entweder Klartext oder HTML E-Mail Texte. In einer
    zukünftigen Version ist eine Multipart E-Mail vorgesehen.
    <br/>
    Du kannst im Wordpress-Admin unter Einstellungen -&gt; Click and Meet
    festlegen, ob du lieber die Klartext oder HTML-Version versenden möchtest.
    <br/>
    Die Texte werden, wenn unter „Läden“ oder „Team/Mitarbeiter“ ein Text
    hinterlegt ist – nicht ausgespielt, und stattdessen von den dort
    eingestellten Texten ersetzt – wobei Team/Mitarbeiter die höchste Priorität
    hat.
    <br/>
    Dadurch kannst du die Texte je Laden oder Abteilung komplett
    individualisieren – also bspw. eigene Header und Footer hinzufügen.
</p>
<p>
    Die E-Mail Texte verfügen über ein Text-Variablen System. Folgende
    Variablen stehen zur Verfügung:
</p>
<table cellspacing="0" cellpadding="6" border="1">
    <tbody>
        <tr>
            <td width="307" valign="top">
                <p>
                    <strong>Variable</strong>
                </p>
            </td>
            <td width="307" valign="top">
                <p>
                    <strong>Erklärung</strong>
                </p>
            </td>
        </tr>
        <tr>
            <td width="307" valign="top">
                <p>
                    <strong>%date%</strong>
                </p>
            </td>
            <td width="307" valign="top">
                <p>
                    Datum des Termines
                </p>
            </td>
        </tr>
        <tr>
            <td width="307" valign="top">
                <p>
                    <strong>%time%</strong>
                </p>
            </td>
            <td width="307" valign="top">
                <p>
                    Zeitpunkt des Termines
                </p>
            </td>
        </tr>
        <tr>
            <td width="307" valign="top">
                <p>
                    <strong>%time_to%</strong>
                </p>
            </td>
            <td width="307" valign="top">
                <p>
                    Wenn im Shortcode eingestellt, wird hier die Dauer oder die
                    Endzeit des Termines dargestellt.
                </p>
            </td>
        </tr>
        <tr>
            <td width="307" valign="top">
                <p>
                    <strong>%betriebsstaette%</strong>
                </p>
            </td>
            <td width="307" valign="top">
                <p>
                    Laden
                </p>
            </td>
        </tr>
        <tr>
            <td width="307" valign="top">
                <p>
                    <strong>%abteilung%</strong>
                </p>
            </td>
            <td width="307" valign="top">
                <p>
                    Team/Mitarbeiter
                </p>
            </td>
        </tr>
        <tr>
            <td width="307" valign="top">
                <p>
                    <strong>%firstname%</strong>
                </p>
            </td>
            <td width="307" valign="top">
                <p>
                    Vorname des Kunden
                </p>
            </td>
        </tr>
        <tr>
            <td width="307" valign="top">
                <p>
                    <strong>%lastname%</strong>
                </p>
            </td>
            <td width="307" valign="top">
                <p>
                    Nachname des Kunden
                </p>
            </td>
        </tr>
        <tr>
            <td width="307" valign="top">
                <p>
                    <strong>%email_address%</strong>
                </p>
            </td>
            <td width="307" valign="top">
                <p>
                    E-Mail Adresse des Kunden
                </p>
            </td>
        </tr>
        <tr>
            <td width="307" valign="top">
                <p>
                    <strong>%reminder_yes_no%</strong>
                </p>
            </td>
            <td width="307" valign="top">
                <p>
                    Ob der Kunde noch einmal informiert werden darf, bevor der
                    Termin stattfindet.
                </p>
            </td>
        </tr>
        <tr>
            <td width="307" valign="top">
                <p>
                    <strong>%phone%</strong>
                </p>
            </td>
            <td width="307" valign="top">
                <p>
                    Telefonnummer, die der Kunde angegeben hat
                </p>
            </td>
        </tr>
        <tr>
            <td width="307" valign="top">
                <p>
                    <strong>%street%</strong>
                </p>
            </td>
            <td width="307" valign="top">
                <p>
                    Straße, die der Kunde angegeben hat
                </p>
            </td>
        </tr>
        <tr>
            <td width="307" valign="top">
                <p>
                    <strong>%plz%</strong>
                </p>
            </td>
            <td width="307" valign="top">
                <p>
                    Postleitzahl, die der Kunde angegeben hat
                </p>
            </td>
        </tr>
        <tr>
            <td width="307" valign="top">
                <p>
                    <strong>%city%</strong>
                </p>
            </td>
            <td width="307" valign="top">
                <p>
                    Ort, den der Kunde angegeben hat
                </p>
            </td>
        </tr>
        <tr>
            <td width="307" valign="top">
                <p>
                    <strong>%comment%</strong>
                </p>
            </td>
            <td width="307" valign="top">
                <p>
                    Kommentar, den der Kunde eingegeben hat
                </p>
            </td>
        </tr>
        
        <tr>
            <td width="307" valign="top">
                <p>
                    <strong>%custom_form_dropdown%</strong>
                </p>
            </td>
            <td width="307" valign="top">
                <p>
                    Wird durch den Wert ersetzt, der vom Kunden im individuellen Dropdown gewählt wurde.
                </p>
            </td>
        </tr>
    </tbody>
</table>

<p><strong>Wichtig:</strong><br />
Damit Ihre E-Mails nicht als Spam gekennzeichnete werden, müssen Sie entweder Ihren Server korrekt einstellen, oder ein Plugin verwenden, welches das Versenden von E-Mails über SMTP erlaubt. Ein Plugin mit dem das möglich ist, ist bspw. Easy WP SMTP.
</p>


<h2>
    Click and Meet – Daten löschen
</h2>
<p>
    Mit deinem Plugin wurde ein zweites Plugin ausgeliefert. Dieses Plugin
    kannst du verwenden, wenn du irgendwann einmal deine Datenbank bereinigen
    möchtest. Wenn du es installierst, aktivierst, und anschließend alle
    Tabellen löschst, werden alle Daten aus Wordpress entfernt, die dieses
    Plugin betreffen.
</p>
<p>
    Wir haben dieses Plugin bewusst separiert, damit eine versehentliche
    Löschung deiner Daten möglichst gut verhindert wird.
    <br/>
    <br/>
    <br/>
</p>
