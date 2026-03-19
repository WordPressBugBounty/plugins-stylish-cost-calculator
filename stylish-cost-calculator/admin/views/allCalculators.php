<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$calculator_amount = is_array( $forms ) ? count( $forms ) : 0;
$icon_search       = $scc_icons['search'] ?? '';
$icon_filter       = $scc_icons['filter'] ?? $scc_icons['filter_list'] ?? '';
$icon_sort         = $scc_icons['repeat'] ?? '';
$icon_menu         = $scc_icons['menu'] ?? $scc_icons['more-vertical'] ?? '';
$icon_edit         = $scc_icons['edit'] ?? $scc_icons['edit-2'] ?? '';
$icon_trash        = $scc_icons['trash'] ?? $scc_icons['trash-2'] ?? '';
$icon_link         = $scc_icons['link'] ?? '';
$icon_copy         = $scc_icons['copy'] ?? '';
$icon_download     = $scc_icons['download'] ?? $scc_icons['cloud-download'] ?? '';
$icon_plus         = $scc_icons['plus-circle'] ?? $scc_icons['plus'] ?? '';

wp_localize_script( 'scc-backend', 'pageAllCalculators', [ 'nonce' => wp_create_nonce( 'all-calculators-page' ) ] );
?>

<div class="row m-0 mt-4 scc-all-calculators-page">
	<div class="col-md-12 px-0">
		<div class="scc-all-calculators-toolbar">
			<label class="scc-search-field" for="scc-calculator-search">
				<span class="scc-icn-wrapper" aria-hidden="true"><?php echo scc_get_kses_extended_ruleset( $icon_search ); ?></span>
				<input id="scc-calculator-search" type="search" placeholder="Search calculators..." autocomplete="off">
			</label>
			<div class="scc-toolbar-actions">
				<div class="scc-toolbar-menu-wrap">
					<button type="button" class="scc-toolbar-button" data-toolbar-toggle="filter" aria-expanded="false">
						<span class="scc-icn-wrapper" aria-hidden="true"><?php echo scc_get_kses_extended_ruleset( $icon_filter ); ?></span>
						<span>Filter</span>
					</button>
					<div class="scc-toolbar-menu" data-toolbar-menu="filter" hidden>
						<button type="button" class="scc-toolbar-menu-item is-active" data-filter-value="all">All</button>
						<button type="button" class="scc-toolbar-menu-item" data-filter-value="published">Published</button>
						<button type="button" class="scc-toolbar-menu-item" data-filter-value="draft">Draft</button>
					</div>
				</div>
				<div class="scc-toolbar-menu-wrap">
					<button type="button" class="scc-toolbar-button" data-toolbar-toggle="sort" aria-expanded="false">
						<span class="scc-icn-wrapper" aria-hidden="true"><?php echo scc_get_kses_extended_ruleset( $icon_sort ); ?></span>
						<span>Sort</span>
					</button>
					<div class="scc-toolbar-menu" data-toolbar-menu="sort" hidden>
						<button type="button" class="scc-toolbar-menu-item is-active" data-sort-value="original-asc">By ID (First to Last)</button>
						<button type="button" class="scc-toolbar-menu-item" data-sort-value="original-desc">By ID (Last to First)</button>
						<button type="button" class="scc-toolbar-menu-item" data-sort-value="updated-desc">Recently Updated</button>
						<button type="button" class="scc-toolbar-menu-item" data-sort-value="updated-asc">Oldest Updated</button>
						<button type="button" class="scc-toolbar-menu-item" data-sort-value="name-asc">Name A-Z</button>
						<button type="button" class="scc-toolbar-menu-item" data-sort-value="name-desc">Name Z-A</button>
					</div>
				</div>
			</div>
		</div>

		<div id="text_no_calculator_" class="scc-empty-message" <?php echo $calculator_amount > 0 ? 'style="display:none;"' : ''; ?>>
			<?php echo wp_kses_post( 'You did not add any calculator yet, you must add a calculator first. Click <a href="' . esc_url( admin_url( 'admin.php?page=scc-tabs' ) ) . '">here</a>.' ); ?>
		</div>
		<div id="scc-no-search-results" class="scc-empty-message" style="display:none;">No calculators match your current search or filters.</div>

		<div class="row m-0 scc_container_forms scc-calculator-grid">
			<?php foreach ( $forms as $f ) : ?>
				<?php
				$form_id       = absint( $f->id );
				$url_stats_raw = is_string( $f->urlStatsArray ) ? $f->urlStatsArray : '';

				if ( empty( $url_stats_raw ) ) {
					$url_stats_raw = '{}';
				}

				$url_stats = json_decode( $url_stats_raw, true );
				if ( ! is_array( $url_stats ) ) {
					$url_stats = [];
				}

				$form_name = isset( $f->formname ) ? wp_unslash( $f->formname ) : '';
				$form_name = is_string( $form_name ) ? $form_name : '';

				$created_timestamp = 0;
				$created_at_text   = '';
				if ( ! empty( $f->created_at ) && '0000-00-00 00:00:00' !== $f->created_at ) {
					$created_at_text   = 'Created At: ' . mysql2date( 'Y/m/d H:i:s', $f->created_at );
					$created_timestamp = strtotime( $f->created_at );
				}

				$last_updated_timestamp = absint( get_option( 'scc_last_updated_calc_' . $form_id ) );
				$sort_timestamp         = $last_updated_timestamp ? $last_updated_timestamp : $created_timestamp;
				$updated_at_label       = $sort_timestamp ? date_i18n( 'm-d-Y H:i', $sort_timestamp ) : 'N/A';
				$total_urls   = count( $url_stats );
				$is_published = $total_urls > 0;
				$status_slug            = $is_published ? 'published' : 'draft';
				$status_label           = $is_published ? 'Published' : 'Draft';
				$shortcode              = sprintf( "[scc_calculator idvalue='%d']", $form_id );
				?>
				<div
					id="scc_calculator_<?php echo esc_attr( $form_id ); ?>"
					class="col-md-6 p-0 scc-calculator-card"
					data-calc-id="<?php echo esc_attr( $form_id ); ?>"
					data-status="<?php echo esc_attr( $status_slug ); ?>"
					data-form-name="<?php echo esc_attr( $form_name ); ?>"
					data-form-id="<?php echo esc_attr( $form_id ); ?>"
					data-original-order="<?php echo esc_attr( $form_id ); ?>"
					data-updated-ts="<?php echo esc_attr( $sort_timestamp ); ?>"
				>
					<div class="scc-card-shell">
						<div class="scc-card-top">
							<span class="scc-status-badge scc-status-badge-<?php echo esc_attr( $status_slug ); ?>"><?php echo esc_html( $status_label ); ?></span>
							<div class="scc-card-menu">
								<button type="button" class="scc-card-menu-toggle" aria-label="<?php echo esc_attr__( 'Calculator actions', 'df-scc' ); ?>" aria-expanded="false">
									<span class="scc-icn-wrapper" aria-hidden="true"><?php echo scc_get_kses_extended_ruleset( $icon_menu ); ?></span>
								</button>
								<div class="scc-card-actions" hidden>
									<a class="scc-card-action-item" href="<?php echo esc_url( admin_url( 'admin.php?page=scc_edit_items' ) . '&id_form=' . $form_id ); ?>">
										<span class="scc-icn-wrapper" aria-hidden="true"><?php echo scc_get_kses_extended_ruleset( $icon_edit ); ?></span>
										<span>Edit</span>
									</a>
									<button type="button" class="scc-card-action-item" onclick="deleteSCC(<?php echo esc_js( $form_id ); ?>)">
										<span class="scc-icn-wrapper" aria-hidden="true"><?php echo scc_get_kses_extended_ruleset( $icon_trash ); ?></span>
										<span>Delete</span>
									</button>
									<button type="button" class="scc-card-action-item scc-card-action-item-disabled use-tooltip" title="You need to purchase a premium license to use this feature." onclick="event.preventDefault(); return false;" aria-disabled="true">
										<span class="scc-icn-wrapper" aria-hidden="true"><?php echo scc_get_kses_extended_ruleset( $icon_copy ); ?></span>
										<span>Duplicate</span>
									</button>
									<button type="button" class="scc-card-action-item scc-card-action-item-disabled use-tooltip" title="You need to purchase a premium license to use this feature." onclick="event.preventDefault(); return false;" aria-disabled="true">
										<span class="scc-icn-wrapper" aria-hidden="true"><?php echo scc_get_kses_extended_ruleset( $icon_download ); ?></span>
										<span>Export</span>
									</button>
									<button type="button" class="scc-card-action-item" onclick="showUrlsPopup(<?php echo esc_js( $form_id ); ?>, this)">
										<span class="scc-icn-wrapper" aria-hidden="true"><?php echo scc_get_kses_extended_ruleset( $icon_link ); ?></span>
										<span>URLs</span>
									</button>
								</div>
							</div>
						</div>

						<h2 class="scc-calculator-title" title="<?php echo esc_attr( $created_at_text ); ?>">
							<a class="scc-calculator-title-link" href="<?php echo esc_url( admin_url( 'admin.php?page=scc_edit_items' ) . '&id_form=' . $form_id ); ?>">
								<span class="scc-calculator-title-text"><?php echo esc_html( $form_name ); ?></span>
							</a>
						</h2>
						<div class="scc-card-footer">
							<div class="scc-calc-update-info">
								<span class="scc-card-footer-label">Updated:</span>
								<span class="scc-calc-update-info-time"><?php echo esc_html( $updated_at_label ); ?></span>
							</div>
							<div class="scc-card-footer-actions">
								<button type="button" class="scc-shortcode-chip" data-shortcode="<?php echo esc_attr( $shortcode ); ?>">
									<span class="scc-shortcode-text"><?php echo esc_html( $shortcode ); ?></span>
									<span class="scc-icn-wrapper" aria-hidden="true"><?php echo scc_get_kses_extended_ruleset( $icon_copy ); ?></span>
								</button>
								<a class="scc-card-edit-button" href="<?php echo esc_url( admin_url( 'admin.php?page=scc_edit_items' ) . '&id_form=' . $form_id ); ?>">
									<span class="scc-icn-wrapper" aria-hidden="true"><?php echo scc_get_kses_extended_ruleset( $icon_edit ); ?></span>
									<span>Edit</span>
								</a>
							</div>
						</div>

						<script id="urlstats-<?php echo esc_attr( $form_id ); ?>" type="text/json"><?php echo wp_json_encode( $url_stats ); ?></script>
					</div>
				</div>
			<?php endforeach; ?>

			<div class="col-md-6 p-0 scc-calculator-create-card">
				<div class="scc-card-shell scc-card-shell-create">
					<a class="scc-create-card-link" href="<?php echo esc_url( admin_url( 'admin.php?page=scc-tabs' ) ); ?>">
						<span class="scc-icn-wrapper" aria-hidden="true"><?php echo scc_get_kses_extended_ruleset( $icon_plus ); ?></span>
						<span>Start New Calculator</span>
					</a>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal df-scc-modal fade in" role="dialog" id="show-calc-urls-container" style="padding-right: 15px;"></div>

<style>
	.scc-all-calculators-page {
		--scc-surface: #ffffff;
		--scc-surface-alt: #f8fafc;
		--scc-border: #d7dee8;
		--scc-text: #17324d;
		--scc-text-soft: #7e93ab;
		--scc-shadow: 0 10px 28px rgba(15, 23, 42, 0.08);
		--scc-shadow-soft: 0 6px 18px rgba(15, 23, 42, 0.06);
		--scc-published-bg: #e7f7ef;
		--scc-published-text: #1c9f68;
		--scc-draft-bg: #fff1e2;
		--scc-draft-text: #d1791f;
		width: 100%;
		padding-left: 50px !important;
		padding-right: 50px !important;
	}

	.scc-all-calculators-page > .col-md-12 {
		width: 100%;
		max-width: 1300px;
		margin-left: auto;
		margin-right: auto;
	}

	.scc-all-calculators-toolbar {
		display: flex;
		align-items: center;
		justify-content: space-between;
		gap: 14px;
		padding: 14px 16px;
		margin-bottom: 24px;
		border: 1px solid var(--scc-border);
		border-radius: 12px;
		background: var(--scc-surface);
		box-shadow: var(--scc-shadow-soft);
	}

	.scc-search-field {
		display: flex;
		align-items: center;
		gap: 8px;
		flex: 1;
		max-width: 420px;
		margin: 0;
		padding: 0 14px;
		padding-right: 0;
		min-height: 40px;
		border: 1px solid var(--scc-border);
		border-radius: 8px;
		background: #fcfcfd;
	}

	.scc-all-calculators-toolbar .scc-search-field input[type="search"] {
		width: auto !important;
		height: auto !important;
		min-height: 0 !important;
		margin: 0 !important;
		padding: 0 !important;
		border: 0 !important;
		border-radius: 0 !important;
		outline: none !important;
		box-shadow: none !important;
		background: transparent !important;
		appearance: none !important;
		-webkit-appearance: none !important;
		-moz-appearance: none !important;
	}

	.scc-search-field .scc-icn-wrapper,
	.scc-toolbar-button .scc-icn-wrapper,
	.scc-card-action-item .scc-icn-wrapper,
	.scc-shortcode-chip .scc-icn-wrapper,
	.scc-card-edit-button .scc-icn-wrapper,
	.scc-create-card-link .scc-icn-wrapper,
	.scc-card-menu-toggle .scc-icn-wrapper {
		display: inline-flex;
		align-items: center;
		justify-content: center;
	}

	.scc-search-field .scc-icn-wrapper,
	.scc-toolbar-button .scc-icn-wrapper {
		color: var(--scc-text-soft);
	}

	.scc-search-field input {
		flex: 1;
		border: 0 !important;
		outline: none;
		box-shadow: none !important;
		background: transparent;
		font-size: 16px;
		color: var(--scc-text);
		padding: 0 !important;
	}

	.scc-search-field input::placeholder {
		color: #94a3b8;
	}

	.scc-toolbar-actions {
		display: flex;
		align-items: center;
		gap: 12px;
	}

	.scc-toolbar-menu-wrap {
		position: relative;
	}

	.scc-toolbar-button {
		display: inline-flex;
		align-items: center;
		gap: 7px;
		padding: 9px 14px;
		border: 1px solid var(--scc-border);
		border-radius: 8px;
		background: var(--scc-surface);
		color: var(--scc-text);
		font-size: 14px;
		font-weight: 500;
		box-shadow: none;
		white-space: nowrap;
	}

	.scc-toolbar-button:hover,
	.scc-toolbar-button:focus {
		background: var(--scc-surface-alt);
		color: var(--scc-text);
	}

	.scc-toolbar-menu {
		position: absolute;
		top: calc(100% + 8px);
		right: 0;
		min-width: 180px;
		padding: 6px;
		border: 1px solid var(--scc-border);
		border-radius: 10px;
		background: var(--scc-surface);
		box-shadow: var(--scc-shadow);
		z-index: 80;
	}

	.scc-toolbar-menu-item {
		display: block;
		width: 100%;
		padding: 10px 12px;
		border: 0;
		border-radius: 10px;
		background: transparent;
		color: var(--scc-text);
		text-align: left;
		font-size: 14px;
	}

	.scc-toolbar-menu-item:hover,
	.scc-toolbar-menu-item.is-active {
		background: #eef3f8;
	}

	.scc-empty-message {
		padding: 18px 20px;
		margin-bottom: 22px;
		border: 1px dashed var(--scc-border);
		border-radius: 14px;
		background: var(--scc-surface);
		color: var(--scc-text-soft);
	}

	.scc-calculator-grid {
		display: grid !important;
		grid-template-columns: repeat(2, minmax(0, 1fr));
		gap: 22px;
		align-items: stretch;
	}

	.scc-calculator-card,
	.scc-calculator-create-card {
		display: block;
		width: 100% !important;
		max-width: 100% !important;
		min-width: 0;
		flex: none;
		padding: 0 !important;
		overflow: visible;
	}

	.scc-card-shell {
		position: relative;
		display: flex;
		flex-direction: column;
		gap: 16px;
		height: 100%;
		padding: 18px 18px 16px;
		border: 1px solid var(--scc-border);
		border-radius: 12px;
		background: var(--scc-surface);
		box-shadow: var(--scc-shadow-soft);
		overflow: visible;
	}

	.scc-card-shell-create {
		align-items: center;
		justify-content: center;
		min-height: 100%;
		border-style: dashed;
		background: linear-gradient(180deg, #fafcff 0%, #f5f8fc 100%);
	}

	.scc-card-top {
		display: flex;
		align-items: flex-start;
		justify-content: space-between;
		gap: 12px;
	}

	.scc-status-badge {
		display: inline-flex;
		align-items: center;
		padding: 5px 10px;
		border-radius: 999px;
		font-size: 12px;
		font-weight: 700;
	}

	.scc-status-badge-published {
		background: var(--scc-published-bg);
		color: var(--scc-published-text);
	}

	.scc-status-badge-draft {
		background: var(--scc-draft-bg);
		color: var(--scc-draft-text);
	}

	.scc-card-menu {
		position: relative;
		z-index: 35;
	}

	.scc-card-menu.is-open {
		z-index: 60;
	}

	.scc-card-menu-toggle {
		display: inline-flex;
		align-items: center;
		justify-content: center;
		width: 34px;
		height: 34px;
		border: 0;
		border-radius: 8px;
		background: #f1f5f9;
		color: var(--scc-text-soft);
	}

	.scc-card-menu-toggle:hover,
	.scc-card-menu-toggle:focus {
		background: #e2e8f0;
		color: var(--scc-text);
	}

	.scc-card-actions {
		position: absolute;
		top: calc(100% + 8px);
		right: 0;
		display: flex;
		flex-direction: column;
		gap: 2px;
		min-width: 180px;
		padding: 8px;
		border: 1px solid var(--scc-border);
		border-radius: 10px;
		background: var(--scc-surface);
		box-shadow: var(--scc-shadow);
		z-index: 70;
	}

	.scc-card-action-item {
		display: flex;
		align-items: center;
		gap: 10px;
		width: 100%;
		padding: 10px 12px;
		border: 0;
		border-radius: 10px;
		background: transparent;
		color: #48627e;
		text-decoration: none;
		text-align: left;
	}

	.scc-card-action-item:hover,
	.scc-card-action-item:focus {
		background: #f8fafc;
		color: var(--scc-text);
		text-decoration: none;
	}

	.scc-card-action-item-disabled {
		opacity: 0.7;
		cursor: not-allowed;
	}

	.scc-card-action-item-disabled:hover,
	.scc-card-action-item-disabled:focus {
		background: #f8fafc;
		color: var(--scc-text);
	}

	.scc-card-action-item .scc-icn-wrapper svg,
	.scc-card-menu-toggle .scc-icn-wrapper svg,
	.scc-shortcode-chip .scc-icn-wrapper svg,
	.scc-card-edit-button .scc-icn-wrapper svg,
	.scc-create-card-link .scc-icn-wrapper svg,
	.scc-toolbar-button .scc-icn-wrapper svg,
	.scc-search-field .scc-icn-wrapper svg {
		width: 16px;
		height: 16px;
	}

	.scc-calculator-title {
		margin: 0;
		color: #1d3f66;
		font-size: 17px;
		font-weight: 700;
		line-height: 1.25;
	}

	.scc-calculator-title-link {
		display: inline-flex;
		align-items: center;
		gap: 10px;
		max-width: 100%;
		color: inherit;
		text-decoration: none;
	}

	.scc-calculator-title-text {
		overflow-wrap: anywhere;
	}

	.scc-calculator-title-link:hover,
	.scc-calculator-title-link:focus {
		color: #314af3;
		text-decoration: none;
	}
	.scc-card-footer {
		display: flex;
		align-items: center;
		justify-content: space-between;
		gap: 10px;
		padding-top: 14px;
		border-top: 1px solid #e5ebf2;
	}

	.scc-card-footer-actions {
		display: flex;
		align-items: center;
		gap: 10px;
		flex-wrap: nowrap;
		justify-content: flex-end;
		min-width: 0;
	}

	.scc-calc-update-info {
		display: flex;
		align-items: center;
		gap: 6px;
		color: #90a1b4;
		font-size: 13px;
		line-height: 1.5;
	}

	.scc-card-footer-label {
		font-weight: 700;
	}

	.scc-shortcode-chip {
		display: inline-flex;
		align-items: center;
		gap: 8px;
		padding: 7px 10px;
		border: 0;
		border-radius: 8px;
		background: #f2f5fa;
		color: #45617c;
		font-family: monospace;
		font-size: 12px;
		white-space: nowrap;
		flex: 0 1 auto;
		min-width: 0;
		max-width: 100%;
		overflow: hidden;
		text-overflow: ellipsis;
	}

	.scc-shortcode-chip:hover,
	.scc-shortcode-chip:focus {
		background: #e7edf4;
		color: #1d3f66;
	}

	.scc-shortcode-chip.is-copied {
		background: #e7f7ef;
		color: #1c9f68;
	}

	.scc-card-edit-button {
		display: inline-flex;
		align-items: center;
		gap: 8px;
		padding: 7px 12px;
		border: 0;
		border-radius: 8px;
		background: #314af3;
		color: #fff;
		font-size: 13px;
		font-weight: 700;
		text-decoration: none;
		white-space: nowrap;
		box-shadow: 0 10px 18px rgba(49, 74, 243, 0.18);
		flex: 0 0 auto;
	}

	.scc-card-edit-button:hover,
	.scc-card-edit-button:focus {
		background: #253cd1;
		color: #fff;
		text-decoration: none;
	}

	.scc-create-card-link {
		display: inline-flex;
		align-items: center;
		gap: 12px;
		padding: 15px 22px;
		border-radius: 999px;
		background: #314af3;
		color: #fff;
		font-size: 15px;
		font-weight: 700;
		text-decoration: none;
		box-shadow: 0 16px 28px rgba(49, 74, 243, 0.22);
	}

	.scc-create-card-link:hover,
	.scc-create-card-link:focus {
		background: #253cd1;
		color: #fff;
		text-decoration: none;
	}

	.scc-urls-table {
		width: 100%;
		border: 1px solid #dbe3ec;
		border-collapse: collapse;
	}

	.scc-urls-table thead {
		background-color: #314af3;
	}

	.scc-urls-table tr th {
		color: #f8f9ff;
	}

	.scc-urls-table tr th,
	.scc-urls-table tr td {
		padding: 10px 12px;
		border: 1px solid #dbe3ec;
	}

	.scc-urls-table tr td:nth-child(2n) {
		width: 120px;
		text-align: center;
	}

	.scc-all-calculators-page .scc-search-field #scc-calculator-search {
		border: none !important;
	}

	@media (max-width: 1490px) {
		.scc-calc-update-info {
			flex-direction: column;
			align-items: flex-start;
			gap: 2px;
		}
	}

	@media (max-width: 1240px) {
		.scc-search-field {
			max-width: none;
		}

		.scc-all-calculators-toolbar,
		.scc-card-footer {
			flex-direction: column;
			align-items: stretch;
		}

		.scc-card-footer-actions {
			justify-content: flex-start;
			flex-wrap: nowrap;
		}
	}

	@media (max-width: 767px) {
		.scc-calculator-grid {
			grid-template-columns: 1fr;
		}
		.scc-toolbar-actions {
			display: grid;
		}

		.scc-calculator-title {
			font-size: 16px;
		}
		.scc-shortcode-chip {
			justify-content: center;
		}
	}
</style>

<script>
	const sccAllCalculatorsState = {
		search: '',
		filter: 'all',
		sort: 'original-asc',
	};

	jQuery(document).ready(function() {
		initAllCalculatorsPage();
	});

	function initAllCalculatorsPage() {
		initCalculatorToolbar();
		initCalculatorMenus();
		initShortcodeCopy();
		applyCalculatorControls();
		jQuery('.scc-calculator-title, .use-tooltip').tooltip();
	}

	function initCalculatorToolbar() {
		const searchInput = document.getElementById('scc-calculator-search');

		if (searchInput) {
			searchInput.addEventListener('input', function(event) {
				sccAllCalculatorsState.search = event.target.value.trim().toLowerCase();
				applyCalculatorControls();
			});
		}

		document.querySelectorAll('[data-toolbar-toggle]').forEach(function(button) {
			button.addEventListener('click', function(event) {
				event.stopPropagation();
				const menuName = button.getAttribute('data-toolbar-toggle');
				const menu = document.querySelector('[data-toolbar-menu="' + menuName + '"]');
				const isExpanded = button.getAttribute('aria-expanded') === 'true';

				closeToolbarMenus();
				closeCalculatorMenus();

				if (!isExpanded && menu) {
					menu.hidden = false;
					menu.style.display = 'block';
					button.setAttribute('aria-expanded', 'true');
				}
			});
		});

		document.querySelectorAll('[data-filter-value]').forEach(function(button) {
			button.addEventListener('click', function() {
				sccAllCalculatorsState.filter = button.getAttribute('data-filter-value');
				document.querySelectorAll('[data-filter-value]').forEach(function(item) {
					item.classList.toggle('is-active', item === button);
				});
				closeToolbarMenus();
				applyCalculatorControls();
			});
		});

		document.querySelectorAll('[data-sort-value]').forEach(function(button) {
			button.addEventListener('click', function() {
				sccAllCalculatorsState.sort = button.getAttribute('data-sort-value');
				document.querySelectorAll('[data-sort-value]').forEach(function(item) {
					item.classList.toggle('is-active', item === button);
				});
				closeToolbarMenus();
				applyCalculatorControls();
			});
		});

		document.addEventListener('click', function(event) {
			if (!event.target.closest('.scc-toolbar-menu-wrap')) {
				closeToolbarMenus();
			}

			if (!event.target.closest('.scc-card-menu')) {
				closeCalculatorMenus();
			}
		});
	}

	function closeToolbarMenus() {
		document.querySelectorAll('[data-toolbar-menu]').forEach(function(menu) {
			menu.hidden = true;
			menu.style.display = 'none';
		});

		document.querySelectorAll('[data-toolbar-toggle]').forEach(function(button) {
			button.setAttribute('aria-expanded', 'false');
		});
	}

	function initCalculatorMenus() {
		document.querySelectorAll('.scc-card-menu-toggle').forEach(function(button) {
			button.addEventListener('click', function(event) {
				event.stopPropagation();
				const menuWrap = button.parentNode;
				const menu = menuWrap.querySelector('.scc-card-actions');
				const isExpanded = button.getAttribute('aria-expanded') === 'true';

				closeCalculatorMenus();
				closeToolbarMenus();

				if (!isExpanded && menu) {
					menu.hidden = false;
					menu.style.display = 'flex';
					menuWrap.classList.add('is-open');
					button.setAttribute('aria-expanded', 'true');
				}
			});
		});
	}

	function closeCalculatorMenus() {
		document.querySelectorAll('.scc-card-actions').forEach(function(menu) {
			menu.hidden = true;
			menu.style.display = 'none';
		});

		document.querySelectorAll('.scc-card-menu-toggle').forEach(function(button) {
			button.setAttribute('aria-expanded', 'false');
		});

		document.querySelectorAll('.scc-card-menu').forEach(function(menuWrap) {
			menuWrap.classList.remove('is-open');
		});
	}

	function initShortcodeCopy() {
		document.querySelectorAll('.scc-shortcode-chip').forEach(function(button) {
			button.addEventListener('click', function() {
				const shortcode = button.getAttribute('data-shortcode');

				if (!shortcode) {
					return;
				}

				if (navigator.clipboard && navigator.clipboard.writeText) {
					navigator.clipboard.writeText(shortcode).then(function() {
						flashCopiedState(button);
					});
					return;
				}

				const helper = document.createElement('textarea');
				helper.value = shortcode;
				document.body.appendChild(helper);
				helper.select();
				document.execCommand('copy');
				document.body.removeChild(helper);
				flashCopiedState(button);
			});
		});
	}

	function flashCopiedState(button) {
		const textNode = button.querySelector('.scc-shortcode-text');
		const originalText = textNode.textContent;
		button.classList.add('is-copied');
		textNode.textContent = 'Copied';

		window.setTimeout(function() {
			button.classList.remove('is-copied');
			textNode.textContent = originalText;
		}, 1400);
	}

	function applyCalculatorControls() {
		const cards = Array.from(document.querySelectorAll('.scc-calculator-card'));
		const grid = document.querySelector('.scc-calculator-grid');
		const createCard = document.querySelector('.scc-calculator-create-card');
		let visibleCount = 0;

		sortCalculatorCards(cards).forEach(function(card) {
			if (grid && createCard) {
				grid.insertBefore(card, createCard);
			}

			const cardName = (card.getAttribute('data-form-name') || '').toLowerCase();
			const cardStatus = card.getAttribute('data-status');
			const matchesSearch = !sccAllCalculatorsState.search || cardName.indexOf(sccAllCalculatorsState.search) !== -1;
			const matchesFilter = sccAllCalculatorsState.filter === 'all' || cardStatus === sccAllCalculatorsState.filter;
			const shouldShow = matchesSearch && matchesFilter;

			card.style.display = shouldShow ? '' : 'none';

			if (shouldShow) {
				visibleCount++;
			}
		});

		toggleCalculatorEmptyStates(cards.length, visibleCount);
	}

	function sortCalculatorCards(cards) {
		return cards.sort(function(cardA, cardB) {
			const nameA = (cardA.getAttribute('data-form-name') || '').toLowerCase();
			const nameB = (cardB.getAttribute('data-form-name') || '').toLowerCase();
			const originalA = parseInt(cardA.getAttribute('data-original-order') || '0', 10);
			const originalB = parseInt(cardB.getAttribute('data-original-order') || '0', 10);
			const updatedA = parseInt(cardA.getAttribute('data-updated-ts') || '0', 10);
			const updatedB = parseInt(cardB.getAttribute('data-updated-ts') || '0', 10);

			switch (sccAllCalculatorsState.sort) {
				case 'original-desc':
					return originalB - originalA;
				case 'updated-asc':
					return updatedA - updatedB;
				case 'name-asc':
					return nameA.localeCompare(nameB);
				case 'name-desc':
					return nameB.localeCompare(nameA);
				case 'updated-desc':
					return updatedB - updatedA;
				case 'original-asc':
				default:
					return originalA - originalB;
			}
		});
	}

	function toggleCalculatorEmptyStates(totalCount, visibleCount) {
		const noCalculatorMessage = document.getElementById('text_no_calculator_');
		const noResultsMessage = document.getElementById('scc-no-search-results');

		if (noCalculatorMessage) {
			noCalculatorMessage.style.display = totalCount === 0 ? '' : 'none';
		}

		if (noResultsMessage) {
			noResultsMessage.style.display = totalCount > 0 && visibleCount === 0 ? '' : 'none';
		}
	}

	function showUrlsPopup(calcId, trigger) {
		closeCalculatorMenus();
		const card = trigger.closest('.scc-calculator-card');
		const title = card ? card.querySelector('.scc-calculator-title').textContent.trim() : '';
		const jsonNode = document.getElementById('urlstats-' + calcId);
		const rows = jsonNode ? JSON.parse(jsonNode.textContent || '{}') : {};
		const popupTemplate = wp.template('show-calc-urls')({
			title: title,
			rows: rows
		});
		const calcUrlsModal = jQuery('#show-calc-urls-container');

		calcUrlsModal.html(popupTemplate);
		calcUrlsModal.find('[data-dismiss="modal"]').one('click', function() {
			calcUrlsModal.hide();
		});
		calcUrlsModal.show();
		['fade', 'in'].forEach(function(className) {
			calcUrlsModal[0].classList.remove(className);
		});
	}

	function deleteSCC(calculator_id) {
		closeCalculatorMenus();

		Swal.fire({
			title: 'Are you sure?',
			text: "You won't be able to revert this!",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#314AF3',
			cancelButtonColor: '#FF2F00',
			confirmButtonText: 'Yes, delete it!'
		}).then((result) => {
			if (result.isConfirmed) {
				showLoadingChanges();
				jQuery.ajax({
					url: ajaxurl,
					data: {
						action: 'sccCalculatorOp',
						op: 'del',
						id_form: calculator_id,
						nonce: pageAllCalculators.nonce
					},
					success: function(data) {
						const response = JSON.parse(data);
						if (response.passed === true) {
							jQuery('#scc_calculator_' + calculator_id).remove();
							applyCalculatorControls();
							showSweet(true, 'The calculator form was deleted.');
						} else {
							showSweet(false, 'An error occurred. Please refresh the page and try again');
						}
					}
				});
			}
		});
	}

	function showSweet(respuesta, message) {
		if (respuesta) {
			Swal.fire({
				toast: true,
				title: message,
				icon: 'success',
				showConfirmButton: false,
				background: 'white',
				timer: 3000,
				position: 'top-end',
			});
		} else {
			Swal.fire({
				toast: true,
				title: message,
				icon: 'error',
				showConfirmButton: false,
				background: 'white',
				timer: 3000,
				position: 'top-end',
			});
		}
	}
</script>

<script type="text/html" id="tmpl-show-calc-urls">
	<div class="df-scc-euiOverlayMask df-scc-euiOverlayMask--aboveHeader">
		<div class="df-scc-euiModal df-scc-euiModal--maxWidth-default df-scc-euiModal--confirmation" style="max-width:80%; max-height:90%; min-height:50%; min-width:25%">
			<button class="df-scc-euiButtonIcon df-scc-euiButtonIcon--text df-scc-euiModal__closeIcon" type="button" data-dismiss="modal">
				<svg width="16" height="16" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg" class="df-scc-euiIcon df-scc-euiIcon--medium df-scc-euiButtonIcon__icon" focusable="false" role="img" aria-hidden="true">
					<path d="M7.293 8L3.146 3.854a.5.5 0 11.708-.708L8 7.293l4.146-4.147a.5.5 0 01.708.708L8.707 8l4.147 4.146a.5.5 0 01-.708.708L8 8.707l-4.146 4.147a.5.5 0 01-.708-.708L7.293 8z"></path>
				</svg>
			</button>
			<div class="df-scc-euiModal__flex">
				<div class="df-scc-euiModalHeader">
					<div class="df-scc-euiModalHeader__title">{{ data.title }}</div>
				</div>
				<div class="df-scc-euiModalBody">
					<div class="df-scc-euiModalBody__overflow">
						<div class="df-scc-euiText df-scc-euiText--medium">
							<div id="table-data-container">
								<# if (Object.keys(data.rows).length) { #>
									<table class="scc-urls-table">
										<thead>
											<tr>
												<th>URL</th>
												<th>Visits</th>
											</tr>
										</thead>
										<tbody>
											<# Object.entries(data.rows).forEach(function(entry) { #>
												<tr>
													<td><a target="_blank" rel="noreferrer" style="white-space: nowrap" href="{{ entry[0] }}">{{ entry[0] }}</a></td>
													<td>{{ entry[1] }}</td>
												</tr>
											<# }); #>
										</tbody>
									</table>
								<# } else { #>
									<p style="text-align: center; color: #45617c;">No links found</p>
								<# } #>
							</div>
							<p class="trn text-danger" style="display:none;">There has been an error. Try again</p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</script>
