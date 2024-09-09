<?php

Header("Content-Security-Policy: default-src 'self';");

function show_html_header($title = "", $description = "", $noIndex = false) {
?><!DOCTYPE html>
<html lang="de">
<head>
<meta charset="utf-8">
<title><?php if (strlen($title) > 0) echo htmlspecialchars($title) . " &ndash; "; ?>Linux User Group Tübingen</title>
<link rel="stylesheet" href="/css/style.css">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php if ($noIndex) { ?><meta name="robots" content="noindex,noarchive"><?php } ?>
<?php if ($description != "") { ?><meta name="description" content="<?php echo htmlspecialchars($description); ?>"><?php } ?>
</head>
<body>
<div class="lug-logo">
<a href="/"><img src="/images/tux-vor-dem-hoelderlinturm.png" width="518" height="555" alt="Logo (Der Linux-Pinguin &quot;Tux&quot; vor dem Hölderlinturm.)"></a>
</div>
<div class="header">
<h1>Linux User Group Tübingen</h1>
</div>
<div class="menu">
<menu>
<li><a href="/">Übersicht</a></li>
<li><a href="/mailingliste">Mailingliste</a></li>
<li><a href="/umgebung">In der Umgebung</a></li>
<li><a href="/kontakt">Kontakt</a></li>
</menu>
</div>
<div class="contents">
<?php if (strlen($title) > 0) { ?>
<h2><?php echo htmlspecialchars($title); ?></h2>
<?php
}
}

function show_html_footer() {
?>
</div>
<div class="footer">
<p>Copyright &copy; <?php if (intval(date("Y"), 10) > 2024) { echo "2024-"; } echo htmlspecialchars(date("Y")) ?> Linux User Group Tübingen (<a href="/kontakt">Kontakt</a>)</p>
<p>Logo: &quot;Tux vor dem Hölderlin Turm&quot; von Chris Laule; Lizenz: <a href="https://creativecommons.org/licenses/by-sa/4.0/">CC BY-SA 4.0</a></p>
</div>
</body>
</html>
<?php
}

$parts = explode("/", substr($_SERVER["REQUEST_URI"], 1));
if (count($parts) != 1) {
    include(dirname(__FILE__) . "/data/404.php");
    exit(1);
}

$document = $parts[0];
if ($document === "" || $document === "index.php")
    $document = "index";

$document_file = dirname(__FILE__) . "/data/" . $document . ".inc.php";

if ($document == "404" || !file_exists($document_file)) {
    include(dirname(__FILE__) . "/data/404.php");
    exit(1);
}

include($document_file);
