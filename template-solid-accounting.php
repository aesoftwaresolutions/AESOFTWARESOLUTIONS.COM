<?php
/**
 * Template Name: Solid Accounting
 * Template Post Type: page
 *
 * Marketing page for Solid — the open, self-hostable accounting platform
 * built by AE Software Solutions.
 *
 * Usage: Pages > Add New > Template: "Solid Accounting" > Publish.
 * Recommended slug: /solid/
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$solid_github  = 'https://github.com/aesoftwaresolutions/Solid';
$solid_contact = home_url( '/contact/' );

add_action( 'wp_head', function () use ( $solid_github ) {
	?>
<meta name="description" content="Solid by AE Software Solutions — double-entry accounting software with invoicing, bank reconciliation, sales tax, budgets, reporting, and an immutable audit trail. Self-hosted, multi-organization, and open to inspect on GitHub.">
<meta property="og:title" content="Solid — Accounting Software You Can Own | AE Software Solutions">
<meta property="og:description" content="Double-entry bookkeeping, invoicing, reconciliation, sales tax, budgets, and reporting with a hash-chained audit log — on your own infrastructure.">
<meta property="og:type" content="website">
<script type="application/ld+json">
<?php
echo wp_json_encode( array(
	'@context'         => 'https://schema.org',
	'@type'            => 'SoftwareApplication',
	'name'             => 'Solid',
	'applicationCategory' => 'BusinessApplication',
	'operatingSystem'  => 'Windows, macOS, Linux, Docker',
	'url'              => $solid_github,
	'description'      => 'Self-hosted double-entry accounting software with invoicing, bank reconciliation, sales tax, budgets, reporting, and a hash-chained immutable audit trail.',
	'offers'           => array(
		'@type' => 'Offer',
		'price' => '0',
		'priceCurrency' => 'USD',
	),
	'author'           => array(
		'@type' => 'Organization',
		'name'  => 'AE Software Solutions',
		'url'   => home_url( '/' ),
	),
), JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT );
?>
</script>
	<?php
}, 1 );

get_header();
?>

<style>
/* Styles are scoped to .solid-mkt so nothing else on the site is affected. */
.solid-mkt{font-family:inherit;color:#161616;--gold:#c9a227;--gold-bright:#e8c350;--ink:#0d0d0d;--paper:#ffffff;--grey:#f6f5f2;--line:#e6e2d6}
.solid-mkt a{text-decoration:none}
.solid-mkt .container{max-width:1120px;margin:0 auto;padding:0 24px}
.solid-mkt section{padding:72px 0}
.solid-mkt h1,.solid-mkt h2,.solid-mkt h3{color:var(--ink);line-height:1.15;margin:0 0 16px}
.solid-mkt h1{font-size:clamp(2rem,4.5vw,3.2rem);font-weight:800}
.solid-mkt h2{font-size:clamp(1.5rem,3vw,2.1rem);font-weight:750}
.solid-mkt p{font-size:1.05rem;line-height:1.65;color:#3d3d3a;margin:0 0 16px}
.solid-mkt .eyebrow{display:inline-block;font-size:.78rem;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:#8a6d14;border:1px solid var(--line);border-radius:999px;padding:6px 14px;background:#fff;margin-bottom:18px}
.solid-mkt .hero{background:radial-gradient(1200px 500px at 80% -10%,rgba(201,162,39,.18),transparent 60%),var(--ink);color:#f5f3ec}
.solid-mkt .hero h1,.solid-mkt .hero h2,.solid-mkt .hero p{color:#f5f3ec}
.solid-mkt .hero p{color:#c9c5b8;max-width:720px}
.solid-mkt .hero .eyebrow{background:transparent;border-color:#3a3a33;color:var(--gold-bright)}
.solid-mkt .actions{display:flex;gap:14px;flex-wrap:wrap;margin-top:26px}
.solid-btn{display:inline-block;background:var(--gold);color:var(--ink);font-weight:700;padding:13px 26px;border-radius:8px;border:1px solid var(--gold)}
.solid-btn:hover{background:var(--gold-bright)}
.solid-btn--ghost{background:transparent;color:#f5f3ec;border:1px solid #4a4a42}
.solid-btn--ghost:hover{border-color:var(--gold);color:var(--gold-bright);background:transparent}
.solid-mkt .badges{display:flex;gap:10px;flex-wrap:wrap;margin-top:34px}
.solid-mkt .badges span{font-size:.82rem;font-weight:600;color:#d9d4c4;border:1px solid #3a3a33;border-radius:999px;padding:7px 14px}
.solid-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(230px,1fr));gap:18px;margin-top:34px}
.solid-card{background:var(--paper);border:1px solid var(--line);border-radius:12px;padding:22px}
.solid-card h3{font-size:1.02rem;font-weight:700}
.solid-card p{font-size:.92rem;margin:0}
.solid-card .dot{width:9px;height:9px;border-radius:50%;background:var(--gold);display:inline-block;margin-right:8px}
.solid-mkt .alt{background:var(--grey)}
.solid-mkt .dark{background:var(--ink)}
.solid-mkt .dark h2,.solid-mkt .dark h3{color:#f5f3ec}
.solid-mkt .dark p{color:#c9c5b8}
.solid-table{width:100%;border-collapse:collapse;margin-top:28px;font-size:.95rem}
.solid-table th,.solid-table td{text-align:left;padding:13px 16px;border-bottom:1px solid #3a3a33;color:#e3dfd2}
.solid-table th{color:var(--gold-bright);font-size:.8rem;letter-spacing:.1em;text-transform:uppercase}
.solid-list{margin:22px 0 0;padding:0;list-style:none}
.solid-list li{position:relative;padding:7px 0 7px 28px;color:#3d3d3a;font-size:1rem;line-height:1.55}
.solid-list li::before{content:"";position:absolute;left:2px;top:15px;width:8px;height:8px;border-radius:50%;background:var(--gold)}
.solid-list--dark li{color:#c9c5b8}
.solid-faq details{border:1px solid var(--line);border-radius:10px;padding:16px 20px;margin-bottom:12px;background:#fff}
.solid-faq summary{font-weight:700;cursor:pointer;color:var(--ink)}
.solid-faq details p{margin:10px 0 0}
.solid-cta{background:linear-gradient(135deg,#15150f,#241f0e)}
.solid-cta h2,.solid-cta p{color:#f5f3ec}
.solid-cta p{color:#c9c5b8;max-width:680px}
@media(max-width:640px){.solid-mkt section{padding:52px 0}.solid-mkt .hero{text-align:left}}
</style>

<main id="primary" class="solid-mkt">

	<section class="hero">
		<div class="container">
			<span class="eyebrow">Built by AE Software Solutions</span>
			<h1>Solid — the accounting platform small businesses can actually own.</h1>
			<p>Double-entry bookkeeping, invoicing, bank reconciliation, sales tax, budgets, and reporting — backed by an immutable, hash-chained audit trail, on infrastructure you control. Self-hosted, multi-organization, and open to inspect on GitHub.</p>
			<div class="actions">
				<a class="solid-btn" href="<?php echo esc_url( add_query_arg( 'service', 'solid-accounting', $solid_contact ) ); ?>">Request a demo</a>
				<a class="solid-btn solid-btn--ghost" href="<?php echo esc_url( $solid_github ); ?>" target="_blank" rel="noopener">View the code on GitHub</a>
			</div>
			<div class="badges">
				<span>True double-entry ledger</span>
				<span>Hash-chained audit log</span>
				<span>Self-hosted &middot; Docker ready</span>
				<span>Multi-organization isolation</span>
				<span>Desktop app &amp; browser</span>
			</div>
		</div>
	</section>

	<section>
		<div class="container">
			<span class="eyebrow">The problem</span>
			<h2>Your books shouldn&rsquo;t live on someone else&rsquo;s subscription.</h2>
			<p>Most small-business accounting tools rent you access to your own financial history. Prices climb every year, your data sits behind a login you don&rsquo;t control, and when you leave, the export never really feels complete.</p>
			<p><strong>Solid takes the opposite approach.</strong> Your ledger, your documents, your audit trail — stored in a database you own, on hardware or hosting you choose. Stop paying for permission to see your own numbers.</p>
		</div>
	</section>

	<section class="alt">
		<div class="container">
			<span class="eyebrow">Everything you need</span>
			<h2>A complete accounting core — not a teaser tier.</h2>
			<p>Solid ships the full workflow: quote to invoice to payment, bank feed to reconciliation, journal to closed period.</p>
			<div class="solid-grid">
				<div class="solid-card"><h3><span class="dot"></span>Invoices &amp; quotes</h3><p>Professional documents with your branding, plus credit notes and recurring invoices for retainers and subscriptions.</p></div>
				<div class="solid-card"><h3><span class="dot"></span>Bank reconciliation</h3><p>Import statements, match transactions against the ledger, and close the month with confidence instead of spreadsheets.</p></div>
				<div class="solid-card"><h3><span class="dot"></span>True double-entry ledger</h3><p>Every posting is balanced and every entry is permanent. Corrections are reversals with a visible history — the way books are supposed to work.</p></div>
				<div class="solid-card"><h3><span class="dot"></span>Immutable audit trail</h3><p>A hash-chained event log records who changed what, when, and why — ready for accountants, lenders, and auditors.</p></div>
				<div class="solid-card"><h3><span class="dot"></span>Sales tax &amp; tax figures</h3><p>Jurisdiction-aware sales-tax rules on invoices and credit notes, with organized tax figures when reporting season arrives.</p></div>
				<div class="solid-card"><h3><span class="dot"></span>Budgets &amp; period close</h3><p>Plan spending by account and period, track actuals against budget, and run a disciplined month-end close.</p></div>
				<div class="solid-card"><h3><span class="dot"></span>Fixed assets &amp; deductions</h3><p>Track asset values and organize deductible expenses so nothing gets lost between purchases and year-end.</p></div>
				<div class="solid-card"><h3><span class="dot"></span>Chart-of-accounts templates</h3><p>Start from a structured COA instead of a blank spreadsheet and get a clean ledger from day one.</p></div>
				<div class="solid-card"><h3><span class="dot"></span>Reports &amp; exports</h3><p>Standard financial reports with clean exports your accountant can work with — no proprietary formats.</p></div>
				<div class="solid-card"><h3><span class="dot"></span>Documents &amp; search</h3><p>Attach receipts and documents to the records they support and find anything again in seconds.</p></div>
				<div class="solid-card"><h3><span class="dot"></span>Multi-organization</h3><p>Run several businesses or client books in one deployment with database-enforced isolation between organizations.</p></div>
				<div class="solid-card"><h3><span class="dot"></span>AI assistant (MCP)</h3><p>An optional AI assistant connects through the Model Context Protocol to answer questions and automate bookkeeping chores — on your terms.</p></div>
			</div>
		</div>
	</section>

	<section class="dark">
		<div class="container">
			<span class="eyebrow" style="background:transparent;border-color:#3a3a33;color:var(--gold-bright)">Why solid</span>
			<h2>Built like infrastructure, not a landing page.</h2>
			<p>Solid is engineered the way financial systems should be — PostgreSQL row-level security separating every organization, money stored precisely in integer minor units, and a posting model that never silently rewrites history.</p>
			<table class="solid-table">
				<thead>
					<tr><th></th><th>Solid</th><th>Typical cloud accounting</th></tr>
				</thead>
				<tbody>
					<tr><td>Where your data lives</td><td>Your database, your hosting</td><td>Their cloud, their terms</td></tr>
					<tr><td>History of changes</td><td>Hash-chained, immutable audit log</td><td>Often limited or plan-gated</td></tr>
					<tr><td>Additional businesses</td><td>Isolated organizations, same install</td><td>Usually another subscription</td></tr>
					<tr><td>Transparency</td><td>Source you can read and review</td><td>Closed black box</td></tr>
					<tr><td>Exit cost</td><td>It&rsquo;s your database — you already have it</td><td>Export and hope</td></tr>
				</tbody>
			</table>
		</div>
	</section>

	<section>
		<div class="container">
			<span class="eyebrow">Who it&rsquo;s for</span>
			<h2>Made for businesses that take their numbers seriously.</h2>
			<ul class="solid-list">
				<li><strong>Small service businesses</strong> that want professional invoicing and real books without a growing monthly bill.</li>
				<li><strong>Multi-entity owners</strong> who need clean separation between companies on a single deployment.</li>
				<li><strong>Bookkeepers and local firms</strong> looking for a self-hosted option they can offer and control.</li>
				<li><strong>Technical teams</strong> who want an open accounting core with an API and MCP integration to build on.</li>
			</ul>
		</div>
	</section>

	<section class="alt solid-faq">
		<div class="container">
			<span class="eyebrow">Questions</span>
			<h2>Common questions.</h2>
			<details>
				<summary>Do I have to host it myself?</summary>
				<p>No. Solid runs great self-hosted with Docker, but AE Software Solutions can deploy and manage it for you — your own instance, your own data, none of the maintenance.</p>
			</details>
			<details>
				<summary>Can my accountant still work with it?</summary>
				<p>Yes. Solid produces clean, standard financial reports and exports, and grants your outside accountant proper access instead of yet another emailed spreadsheet.</p>
			</details>
			<details>
				<summary>Can I see the code before committing to anything?</summary>
				<p>Yes — the full project, including the ledger, audit chain, and database design, is on GitHub. We&rsquo;d rather earn trust with source code than slogans.</p>
			</details>
			<details>
				<summary>What does it cost?</summary>
				<p>Solid is free to run yourself. If you&rsquo;d rather not manage servers, AE Software Solutions offers flat, predictable setup and hosting arrangements — no per-seat meter running.</p>
			</details>
		</div>
	</section>

	<section class="solid-cta">
		<div class="container">
			<h2>See your own books on Solid.</h2>
			<p>Tell us about your business and we&rsquo;ll walk you through a live instance — or point you at the self-hosted setup. Either way, the data ends the day exactly where it started: with you.</p>
			<div class="actions">
				<a class="solid-btn" href="<?php echo esc_url( add_query_arg( 'service', 'solid-accounting', $solid_contact ) ); ?>">Request a demo</a>
				<a class="solid-btn solid-btn--ghost" href="<?php echo esc_url( $solid_github ); ?>" target="_blank" rel="noopener">Explore the GitHub repository</a>
			</div>
		</div>
	</section>

</main>

<?php
get_footer();
