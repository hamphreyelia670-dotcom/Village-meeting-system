<?php
session_start();
require_once "config/db.php";

$meetings = [];
$query = $conn->query(
	"SELECT Title, Description, Meetingdate, Meetingtime, Location
	 FROM meetings
	 WHERE Meetingdate >= CURDATE()
	 ORDER BY Meetingdate ASC, Meetingtime ASC
	 LIMIT 4"
);

if ($query) {
	while ($meeting = $query->fetch_assoc()) {
		$meetings[] = $meeting;
	}
}

$dashboard = "auth/login.php";
if (isset($_SESSION["role"])) {
	$dashboard = match ($_SESSION["role"]) {
		"admin" => "admin/dashboard.php",
		"chairman" => "chairman/dashboard.php",
		"citizen" => "citizen/dashboard.php",
		default => "auth/login.php"
	};
}
?>
<!DOCTYPE html>
<html lang="sw">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Umoja Village | Taarifa za Kijiji</title>
	<link rel="stylesheet" href="assets/style.css">
</head>
<body>
	<header class="site-header">
		<a class="brand" href="index.php"><span class="brand-mark">UV</span><span>Umoja Village</span></a>
		<nav class="site-nav" aria-label="Navigesheni kuu">
			<a href="#mikutano">Mikutano</a>
			<a href="#kuhusu">Kuhusu mfumo</a>
			<?php if (isset($_SESSION["userId"])): ?>
				<a class="nav-button" href="<?= htmlspecialchars($dashboard) ?>">Dashibodi</a>
			<?php else: ?>
				<a class="nav-button" href="auth/login.php">Ingia</a>
			<?php endif; ?>
		</nav>
	</header>

	<main>
		<section class="hero">
			<div class="hero-copy">
				<p class="eyebrow">TAARIFA ZA KIJIJI, KWA WAKATI</p>
				<h1>Ushiriki bora huanza na taarifa sahihi.</h1>
				<p class="hero-text">Pata ratiba za mikutano ya kijiji, matangazo na maamuzi muhimu katika sehemu moja iliyo wazi kwa kila mwananchi.</p>
				<div class="hero-actions">
					<a class="button button-primary" href="#mikutano">Angalia mikutano</a>
					<?php if (!isset($_SESSION["userId"])): ?><a class="button button-ghost" href="auth/register.php">Jisajili kama mwananchi</a><?php endif; ?>
				</div>
			</div>
			<div class="hero-note">
				<span class="note-label">Kituo cha taarifa</span>
				<strong>Habari za kijiji zinapatikana kwa uwazi na urahisi.</strong>
				<span class="note-line"></span>
				<small>Ratiba mpya huongezwa na viongozi wa kijiji mara inapothibitishwa.</small>
			</div>
		</section>

		<section class="section" id="mikutano">
			<div class="section-heading"><div><p class="eyebrow">RATIBA IJAYO</p><h2>Mikutano ya kijiji</h2></div><span class="section-count"><?= count($meetings) ?> ratiba</span></div>
			<?php if ($meetings): ?>
				<div class="meeting-grid">
					<?php foreach ($meetings as $meeting): ?>
						<article class="meeting-card">
							<div class="date-badge"><strong><?= date("d", strtotime($meeting["Meetingdate"])) ?></strong><span><?= strtoupper(date("M", strtotime($meeting["Meetingdate"]))) ?></span></div>
							<div><h3><?= htmlspecialchars($meeting["Title"]) ?></h3><p><?= htmlspecialchars($meeting["Description"] ?: "Mkutano wa kijiji kwa ajili ya ushirikishwaji wa wananchi.") ?></p><div class="meeting-meta"><span><?= htmlspecialchars(date("H:i", strtotime($meeting["Meetingtime"]))) ?></span><span><?= htmlspecialchars($meeting["Location"]) ?></span></div></div>
						</article>
					<?php endforeach; ?>
				</div>
			<?php else: ?><div class="empty-state">Hakuna mkutano uliopangwa kwa sasa. Tafadhali rudi tena baadaye.</div><?php endif; ?>
		</section>

		<section class="info-strip" id="kuhusu">
			<div><span class="strip-number">01</span><strong>Ratiba wazi</strong><p>Jua mkutano unaofuata, muda na eneo lake.</p></div>
			<div><span class="strip-number">02</span><strong>Ushiriki wa wote</strong><p>Wananchi wapate taarifa zinazowahusu kwa wakati.</p></div>
			<div><span class="strip-number">03</span><strong>Maamuzi yanayoonekana</strong><p>Jenga uaminifu kupitia taarifa zilizohifadhiwa.</p></div>
		</section>
	</main>
	<footer class="site-footer"><span>Umoja Village Management System</span><span>&copy; <?= date("Y") ?> &middot; Taarifa kwa jamii</span></footer>
</body>
</html>
