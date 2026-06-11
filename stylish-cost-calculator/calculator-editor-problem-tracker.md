# Calculator Editor Problem Tracker

This tracker lists confirmed calculator editor problems from the combined audits, ordered by fix priority.

Priority scale:
- P0 Critical: security/privacy exposure or serious access-control issue.
- P1 High: likely data loss, broken editor behavior, or high-impact stability issue.
- P2 Medium: performance, maintainability, or correctness issue with moderate impact.
- P3 Low: cleanup, minor notice risk, or low-risk code quality issue.

Current active-branch status: issues #1-#18 and #23 are fixed in this branch; #19-#22 and #24 remain tracked cleanup/low-risk items.

## 1. Stripe Private Key Is Rendered Into The DOM

- Priority: P0 Critical
- Source: Codex
- Status: Fixed in active branch
- Pages involved:
  - `admin/views/editCalcualtor.php`
  - `admin/views/extraSettings.php`
  - `assets/js/scc-backend.js`
- Functions/variables involved:
  - `$stripeConfig`
  - `$stripeDataAttr`
  - `data-priv-key`
  - `data-pub-key`
  - `toggleStripe()`
- Notes:
  - The Stripe private key is printed into a button data attribute when Stripe is configured.
  - Fixed in active branch: the editor renders only the Stripe public key and toggles Stripe status through a server-side AJAX action.
  - This should be fixed before broader editor cleanup.
  - Recommended fix: never render the private key into the browser. Use server-side storage and return only masked status or public key where needed.

## 2. Editor Page Uses `read` Capability

- Priority: P0 Critical
- Source: Codex
- Status: Fixed in active branch
- Pages involved:
  - `stylish-cost-calculator.php`
  - `admin/controllers/PageControllers/class-page-edit-calculator.php`
- Functions/variables involved:
  - `admin_menu()`
  - `add_submenu_page( '', 'Edit Calculator Form', ..., 'read', 'scc_edit_items', ... )`
  - `PageEditTabs::__construct()`
  - `$formC->readWithRelations( $_GET['id_form'] )`
- Notes:
  - The editor page is available to any logged-in user with `read`.
  - Fixed in active branch: the hidden edit page and calculator list submenu now require `manage_options`.
  - Editor AJAX writes are gated by `manage_options`, but the page itself can expose calculator data and editor markup.
  - Recommended fix: change the hidden edit submenu capability to `manage_options` and add an explicit capability check in the page controller.

## 3. Editor Router Case Uses A Boolean Expression

- Priority: P1 High
- Source: Codex
- Status: Fixed in active branch
- Pages involved:
  - `admin/controllers/dealer.php`
- Functions/variables involved:
  - `dealer::get()`
  - `case 'scc_edit_items' && isset( $_GET['id_form'] ):`
- Notes:
  - The `case` expression evaluates to a boolean, not the intended page slug.
  - This can route unexpected admin page requests with `id_form` into the editor controller.
  - Recommended fix: use `case 'scc_edit_items':` and check `isset( $_GET['id_form'] )` inside the case.

## 4. Raw `json_encode()` Output Is Embedded In Scripts

- Priority: P1 High
- Source: Codex
- Status: Fixed in active branch
- Pages involved:
  - `admin/views/editCalcualtor.php`
  - `admin/controllers/PageControllers/class-page-edit-calculator.php`
- Functions/variables involved:
  - `<script id="scc-data-schema">`
  - `window["woocommerceProducts"]`
  - `$f1->formstored`
  - `window["scc_currencies"]`
  - `json_encode()`
- Notes:
  - JSON is embedded into script contexts without hex-safe flags.
  - Data containing `</script>` can break script parsing and may create stored admin XSS risk.
  - Recommended fix: use `wp_json_encode()` with `JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT`, or `wp_add_inline_script()` with safe JSON.

## 5. Attribute Values Use `wp_kses()` Instead Of `esc_attr()`

- Priority: P1 High
- Source: Pasted audit
- Status: Fixed in active branch
- Pages involved:
  - `admin/models/editElementModel.php`
- Functions/variables involved:
  - `renderCheckboxSetupBody()`
  - `renderDropdownSetupBody()`
  - `$elit->name`
  - `$elit->description`
  - `stripslashes( wp_kses( ... ) )`
- Notes:
  - `wp_kses()` sanitizes HTML but does not escape for attribute context.
  - These values are printed inside `value="..."`.
  - Recommended fix: use `esc_attr( wp_unslash( ... ) )` or sanitize allowed HTML separately from plain input values.

## 6. Global Debounce Timers Can Drop Saves

- Priority: P1 High
- Source: Codex
- Status: Fixed in active branch
- Pages involved:
  - `admin/views/editCalcualtor.php`
- Functions/variables involved:
  - `changeTitleSection()`
  - `changeDescriptionSection()`
  - `clickedTitleElement()`
  - `changeNameElementItem()`
  - `changeDescriptionElementItem()`
  - `changePriceElementItem()`
  - `changeValue2()`
  - `changeValue3()`
  - `changeValue4()`
  - `timeChangeTitleSection`
  - `timechangeDescriptionSection`
  - `timeTitledropdown`
  - `titmeNameElementItem`
  - `timeDescriptionElementItem`
  - `timePriceElementItem`
  - `timeElementValue2`
  - `timeElementValue3`
  - `timeElementValue4`
- Notes:
  - A single global timer is reused for each field type.
  - Editing a second element before the first timer fires can cancel the first pending save.
  - Recommended fix: store debounce timers per element and field key, or move to a shared debounced save queue keyed by record and column.

## 7. Missing AJAX Error Handlers Can Leave Save UI Stuck

- Priority: P1 High
- Source: Pasted audit
- Status: Fixed in active branch
- Pages involved:
  - `admin/views/editCalcualtor.php`
  - `assets/js/scc-backend.js`
- Functions/variables involved:
  - `sccBackendUtils.disableSaveBtnAjax()`
  - `clickedTitleElement()`
  - `changeNameElementItem()`
  - `changeDescriptionElementItem()`
  - `changePriceElementItem()`
  - `changeValue2()`
  - `changeValue3()`
  - `changeValue4()`
  - `handleElementCopy()`
- Notes:
  - Many AJAX calls re-enable the save UI only in `success`.
  - HTTP errors, timeouts, or server notices can leave the save button disabled and spinner visible.
  - Recommended fix: use `.always()` or `complete` to restore UI, and `error` or `.fail()` for user-facing failure messages.

## 8. Add Element Defaults Are Rendered But Not Persisted

- Priority: P1 High
- Source: Codex
- Status: Fixed in active branch
- Pages involved:
  - `stylish-cost-ajax.php`
  - `admin/models/editElementModel.php`
- Functions/variables involved:
  - `scc_addElementFileUpload()`
  - `scc_addElementCommentBox()`
  - `scc_addElementQuantityBox()`
  - `$eli['value1']`
  - `$eli['value2']`
  - `$eli['value3']`
  - `$eli['element_id']`
- Notes:
  - Several handlers build `$eli` defaults for the rendered response but never persist an element item row.
  - Users can see defaults immediately after adding an element, then lose them after reload.
  - Recommended fix: align rendered defaults with the persisted database shape for each element type.

## 9. Add Element Handlers Use Undefined `$elementItem_id`

- Priority: P1 High
- Source: Codex
- Status: Fixed in active branch
- Pages involved:
  - `stylish-cost-ajax.php`
  - `admin/models/editElementModel.php`
- Functions/variables involved:
  - `ajaxRequest::scc_addElementFileUpload()`
  - `ajaxRequest::scc_addElementCommentBox()`
  - `ajaxRequest::scc_addElementQuantityBox()`
  - `$elementItem_id`
  - `renderFileUploadSetupBody2()`
  - `renderCommentBoxSetupBody2()`
  - `renderQuantityBoxSetupBody2()`
- Notes:
  - The handlers pass `$elementItem_id` into render helpers without defining it.
  - This can create PHP notices and inconsistent editor markup.
  - Recommended fix: either create the needed element item before rendering, or stop rendering item IDs for element types that do not use persisted item rows.

## 10. Unsanitized `$_GET['id_form']` In Editor Controller

- Priority: P1 High
- Source: Pasted audit
- Status: Fixed in active branch
- Pages involved:
  - `admin/controllers/PageControllers/class-page-edit-calculator.php`
- Functions/variables involved:
  - `PageEditTabs::__construct()`
  - `$formC->readWithRelations( $_GET['id_form'] )`
  - `show_eror()`
- Notes:
  - The method typehint reduces SQL injection risk, but missing or array values can raise notices or `TypeError`.
  - Recommended fix: require `isset`, use `absint`, and handle invalid IDs before calling `readWithRelations()`.

## 11. Every Save Can Trigger A Full Calculator Schema Refresh

- Priority: P2 Medium
- Source: Codex
- Status: Fixed in active branch
- Pages involved:
  - `assets/js/scc-backend.js`
  - `assets/js/scc-ai-utils.js`
  - `stylish-cost-ajax.php`
  - `admin/controllers/formController.php`
- Functions/variables involved:
  - `sccBackendUtils.handleSavingAlert()`
  - `sccAiUtils.updateCalculatorDataSchema()`
  - `ajaxRequest::scc_update_calculator_data_schema()`
  - `formController::readWithRelations()`
- Notes:
  - `handleSavingAlert()` calls `sccAiUtils.updateCalculatorDataSchema()` after saves.
  - Fixed in active branch: schema refresh is queued/debounced and skipped on explicit failed save responses.
  - The endpoint reloads the full calculator relation tree.
  - Since writes flush relation cache, normal editing can repeatedly rebuild the full tree.
  - Recommended fix: refresh schema only when AI/sidebar features need it, debounce globally, or patch the local schema incrementally after known saves.

## 12. WooCommerce Editor Load Fetches All Products And Variations

- Priority: P2 Medium
- Source: Codex
- Status: Fixed in active branch
- Pages involved:
  - `admin/views/editCalcualtor.php`
- Functions/variables involved:
  - `WP_Query`
  - `'posts_per_page' => -1`
  - `$woo_commerce_products`
  - `$woocommerce_products_array`
  - `$product->get_available_variations()`
  - `window["woocommerceProducts"]`
- Notes:
  - WooCommerce-enabled editors load all products and variations on page load.
  - Fixed in active branch: editor page load no longer queries the full WooCommerce catalog; product choices are fetched lazily through async search with a capped AJAX endpoint.
  - Large stores can see slow loads and high memory usage.
  - Recommended fix: use lazy AJAX search/select for product assignment instead of embedding the full catalog.

## 13. `readWithRelations()` Uses N+1 Queries

- Priority: P2 Medium
- Source: Both audits
- Status: Fixed in active branch
- Pages involved:
  - `admin/controllers/formController.php`
- Functions/variables involved:
  - `formController::readWithRelations()`
  - `$sections`
  - `$subsection`
  - `$elements`
  - `$condition`
  - `$el->elementitems`
- Notes:
  - The function queries inside nested loops for sections, subsections, elements, conditions, condition lookup records, and element items.
  - Fixed in active branch: relation loading batches child records with `WHERE IN` queries and maps them back in PHP.
  - Existing relation caching helps repeated reads, but writes flush the cache.
  - Recommended fix: merge duplicate element loops first, then batch child records with `WHERE IN` queries.

## 14. Icon Picker Reads Local JSON Through A URL

- Priority: P2 Medium
- Source: Codex
- Status: Fixed in active branch
- Pages involved:
  - `stylish-cost-ajax.php`
  - `assets/js/scc-backend.js`
- Functions/variables involved:
  - `ajaxRequest::scc_get_icon_list()`
  - `sccGetIconList()`
  - `file_get_contents( SCC_URL . 'assets/scc_icons/font-awesome-solid.json' )`
  - `file_get_contents( SCC_URL . 'assets/scc_icons/material-icons-outlined.json' )`
- Notes:
  - The endpoint reads bundled JSON through the public plugin URL.
  - Fixed in active branch: icon JSON is read from `SCC_DIR`, with readable-file and JSON-shape checks.
  - This can fail on hosts with remote URL fopen disabled or blocked loopback requests.
  - Recommended fix: read from `SCC_DIR . '/assets/scc_icons/...'`.

## 15. Duplicate `showLoadingChanges()` Uses Conflicting Timers

- Priority: P2 Medium
- Source: Pasted audit
- Status: Fixed in active branch
- Pages involved:
  - `assets/js/scc-backend.js`
- Functions/variables involved:
  - `showLoadingChanges()`
  - `timer: 7500`
  - `timer: 75000`
- Notes:
  - The function is declared twice at top level.
  - Fixed in active branch: only one `showLoadingChanges()` function remains, and the legacy copy no longer overrides it.
  - JavaScript hoisting means the later 75 second version wins.
  - Recommended fix: keep one implementation and use the intended timeout.

## 16. `focusout` Handlers Are Rebound On Every Keyup

- Priority: P2 Medium
- Source: Pasted audit
- Status: Fixed in active branch
- Pages involved:
  - `admin/views/editCalcualtor.php`
  - `assets/js/scc-backend.js`
- Functions/variables involved:
  - `changeTitleSection()`
  - `changeDescriptionSection()`
  - `clickedTitleElement()`
  - `changeNameElementItem()`
  - `changeDescriptionElementItem()`
  - `changePriceElementItem()`
  - `changeValue2()`
  - `changeValue3()`
  - `changeValue4()`
  - `changeValue6()`
- Notes:
  - Each keyup attaches another `focusout` listener.
  - Fixed in active branch: no `focusout(` handlers remain in the checked editor JS files.
  - The listener only resets the timer variable and does not clear the pending timeout.
  - Recommended fix: remove the repeated binding or bind once with a namespaced event.

## 17. Implicit JavaScript Globals

- Priority: P2 Medium
- Source: Pasted audit
- Status: Fixed in active branch
- Pages involved:
  - `admin/views/editCalcualtor.php`
- Functions/variables involved:
  - `cal = getElmtsNCndns(datacal)`
  - `changeValue3()`
  - `id_element = el_container.find(...)`
- Notes:
  - Assignments without `let`, `const`, or `var` leak globals.
  - Fixed in active branch: the remaining `cal` assignment is explicitly scoped; the prior `id_element` leak was already fixed.
  - This can cause hard-to-trace cross-handler state bugs.
  - Recommended fix: declare variables explicitly and enable a basic JS lint rule for no implicit globals.

## 18. `$_GET['default']` Is Read Without `isset`

- Priority: P2 Medium
- Source: Pasted audit
- Status: Fixed in active branch
- Pages involved:
  - `stylish-cost-ajax.php`
- Functions/variables involved:
  - `ajaxRequest::scc_upElementItemSwichoption()`
  - `$_GET['default']`
  - `$eli['opt_default']`
- Notes:
  - The code checks `isset( $_GET['id_element'] ) && $_GET['default'] == 1`.
  - Fixed in active branch: `default` is read once behind an `isset()` guard before the branch checks it.
  - If `id_element` is present but `default` is absent, PHP can emit a notice.
  - Recommended fix: include `isset( $_GET['default'] )` in the condition.

## 19. `getLastId()` Compares SQL NULL To String `'null'`

- Priority: P3 Low
- Source: Pasted audit
- Status: Confirmed
- Pages involved:
  - `admin/controllers/formController.php`
- Functions/variables involved:
  - `formController::getLastId()`
  - `$result->lastId`
  - `$result->lastId == 'null'`
- Notes:
  - SQL `MAX(id)` returns `NULL`, not the string `'null'`.
  - The current create flow works mostly because `intval( null ) + 1` becomes `1`.
  - Recommended fix: check `null === $result->lastId`.

## 20. Usage Stat Starts At Zero

- Priority: P3 Low
- Source: Pasted audit
- Status: Confirmed
- Pages involved:
  - `admin/controllers/PageControllers/class-page-edit-calculator.php`
- Functions/variables involved:
  - `PageEditTabs::updateCalculatorUsageStat()`
  - `df-scc-save-count`
- Notes:
  - First initialization sets the option to `0` instead of `1`.
  - This undercounts by one.
  - Recommended fix: initialize to `1` on first use, if the option is meant to count editor loads/saves.

## 21. Debug Red Background Side Effect In `changeValue2()`

- Priority: P3 Low
- Source: Pasted audit
- Status: Confirmed
- Pages involved:
  - `admin/views/editCalcualtor.php`
- Functions/variables involved:
  - `changeValue2()`
  - `.input_id_element`
  - `.css("background-color", "red")`
- Notes:
  - This appears to be leftover debug code.
  - The target input is hidden, so the visual impact is probably low, but it should be removed.

## 22. Live Console Debug Statements

- Priority: P3 Low
- Source: Pasted audit
- Status: Confirmed
- Pages involved:
  - `admin/views/editCalcualtor.php`
  - `assets/js/scc-ai-utils.js`
- Functions/variables involved:
  - `addDropdownMenuElement()`
  - `console.log(subsectionContainer)`
  - `console.warn(true, "the value has changed")`
  - `console.warn(false, "There was an error, please try again")`
- Notes:
  - Live debug statements remain in editor paths.
  - Some are harmless but noisy; the slider warnings also create inconsistent UX because other saves use UI feedback.
  - Recommended fix: remove debug logging or replace relevant cases with consistent user-facing save feedback.

## 23. Dead Stripe Import

- Priority: P3 Low
- Source: Pasted audit
- Status: Fixed in active branch
- Pages involved:
  - `admin/controllers/PageControllers/class-page-edit-calculator.php`
- Functions/variables involved:
  - `use Stripe\Terminal\Location;`
- Notes:
  - The import is unused.
  - Fixed in active branch: removed the unused import while adding the explicit editor capability check.
  - Recommended fix: remove it.

## 24. Minor Controller Inefficiencies And Oddities

- Priority: P3 Low
- Source: Pasted audit
- Status: Confirmed
- Pages involved:
  - `admin/controllers/elementController.php`
  - `admin/controllers/elementitemController.php`
  - `admin/controllers/sectionController.php`
  - `admin/controllers/subsectionController.php`
  - `stylish-cost-ajax.php`
- Functions/variables involved:
  - `$showInputBoxSlider = 00`
  - `new self()` inside `update()`
  - `ssc_loadExample()`
  - nested named functions `scc_insert_db_()` and `insert_calculator()`
- Notes:
  - These are lower severity than the editor auth, save, and load issues.
  - `00` is equivalent to zero but confusing.
  - `new self()` is unnecessary when `$this->read()` would work.
  - Named functions inside `ssc_loadExample()` could fatal if that method is executed twice in one request.
