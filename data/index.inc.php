<?php
/* Configuration */
$occasions = [
    ["day" => "third Tuesday", "time" => "8pm"],
];
$showMaxMeetings = 6;

/* Output formatting for dates. */
$months_de = [
    1 => "Januar",
    2 => "Februar",
    3 => "März",
    4 => "April",
    5 => "Mai",
    6 => "Juni",
    7 => "Juli",
    8 => "August",
    9 => "September",
    10 => "Oktober",
    11 => "November",
    12 => "Dezember",
];

function format_date($date)
{
    return htmlspecialchars($date->format("d.m.Y"));
}

/* Calculate the next even, as well as the next couple of events */
$timezone = new DateTimeZone("Europe/Berlin");
$today = new DateTimeImmutable("now midnight", $timezone);

$meetings = [];
$monthIterator = $today;
for ($i = 0; $i < ($showMaxMeetings + 2); ++$i) {
    $ym = $monthIterator->format("Y-m");
    for ($j = 0; $j < count($occasions); ++$j) {
        $occasion = $occasions[$j];
        $possible = new DateTimeImmutable($occasion["day"] . " of " . $ym . " " . $occasion["time"], $timezone);
        if ($possible < $today)
            continue;
        $meetings[] = ["date" => $possible, "index" => $j];
    }
    $monthIterator = new DateTimeImmutable("first day of " . $ym);
    $monthIterator = $monthIterator->add(new DateInterval("P1M"));
}

if (count($meetings) > $showMaxMeetings)
    $meetings = array_slice($meetings, 0, $showMaxMeetings);

$next = $meetings[0]["date"];

$events = [];
foreach ($meetings as $meeting) {
    $month = intval($meeting["date"]->format("m"), 10);
    $year = intval($meeting["date"]->format("Y"), 10);
    if (count($occasions) > 1) {
        $event = ["what" => sprintf("Stammtisch %s %04d Nr. %d", htmlspecialchars($months_de[$month]), $year, $meeting["index"] + 1),
                  "when" => $meeting["date"]];
    } else {
        $event = ["what" => sprintf("Stammtisch %s %04d", htmlspecialchars($months_de[$month]), $year),
                  "when" => $meeting["date"]];
    }
    $events[] = $event;
}

foreach (glob(dirname(__FILE__) . "/../events/*.event") as $eventFile) {
    $eventStruct = parse_ini_file($eventFile, true);
    if (!array_key_exists("event", $eventStruct))
        continue;
    $eventStruct = $eventStruct["event"];
    if (!array_key_exists("date", $eventStruct)
        || !array_key_exists("title", $eventStruct))
        continue;
    $eventDate = new DateTimeImmutable($eventStruct["date"], $timezone);
    if ($eventDate < $today)
        continue;
    if (array_key_exists("link", $eventStruct))
        $what = sprintf("<a href=\"%s\">%s</a>",
                        htmlspecialchars($eventStruct["link"]),
                        htmlspecialchars($eventStruct["title"]));
    else
        $what = htmlspecialchars($eventStruct["title"]);
    $events[] = ["what" => $what, "when" => $eventDate];
}

usort($events, function($a, $b) {
    if ($a["when"] == $b["when"])
        return 0;
    else if ($a["when"] < $b["when"])
        return -1;
    else
        return 1;
});

show_html_header("", "Übersicht über die Linux User Group Tübingen");
?>
<p>Die LUG Tübingen ist ein loser Zusammenschluss von Linux-Enthusiasten aus dem Raum Tübingen. Wir treffen uns einmal im Monat zu einem Stammtisch, und tauschen uns auf einer <a href="/mailingliste">Mailingliste</a> aus.</p>
<h2 id="stammtisch">Stammtisch</h2>
<p>Der Stammtisch findet <strong>jeden dritten Dienstag im Monat</strong> abends im <a href="https://coyote.de/filialen/tuebingen/">Coyote Cafe Tübingen</a> direkt am Westbahnhof statt. Die meisten Leute trudeln immer zwischen 19:00 und 20:00 ein. In den wärmeren Monaten sind wir im Biergarten draußen.</p>
<p>Mit dem ÖPNV ist der <a href="https://www.naldo.de/fahrplan/app/trip?formik=destination%3Dde%253A08416%253A12041&amp;lng=de&amp;trip=multiModalitySelected%3Dpt">Westbahnhof</a> entweder mit dem Zug (RB 63 zwischen Herrenberg und Tübingen), oder dem Bus (Linien 11 und 12) zu erreichen. Die Haltestelle heißt Tübingen West, manchmal auch Tübingen Westbahnhof.</p>
<p>Vor dem Westbahnhof gibt es abends Parkmöglichkeiten für Autos.</p>
<?php
if ($next->diff($today)->d == 0) {
?>Der nächste Stammtisch ist <strong>heute</strong>, am <span class="nextdate"><?php echo format_date($next); ?></span>.<?php
} else if ($next->diff($today)->d == 1) {
?>Der nächste Stammtisch ist <strong>morgen</strong>, am <span class="nextdate"><?php echo format_date($next); ?></span>.<?php
} else if ($next->diff($today)->d == 2) {
?>Der nächste Stammtisch ist <strong>übermorgen</strong>, am <span class="nextdate"><?php echo format_date($next); ?></span>.<?php
} else {
?>Der nächste Stammtisch ist am <span class="nextdate"><?php echo format_date($next); ?></span>.<?php
}
?>
<h2>Nächste Termine</h2>
<p>Zusätzlich zu den Terminen der nächsten Stammtische sind auch noch weitere Termine aufgeführt, die für Linux-Nutzer aus der Umgebung interessant sein könnten.</p>
<table>
<thead>
<tr>
  <th>Veranstaltung</th>
  <th>Datum</th>
</tr>
</thead>
<tbody>
<?php
foreach ($events as $event) {
    printf("<tr>\n  <td>%s</td>\n  <td>%s</td>\n</tr>\n", $event["what"], format_date($event["when"]));
}
?>
</tbody>
</table>
<?php show_html_footer(); ?>
