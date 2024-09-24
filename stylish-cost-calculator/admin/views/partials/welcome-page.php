<?php
$scc_is_free_version = defined( 'STYLISH_COST_CALCULATOR_VERSION' );
?>
<!-- Main container -->
<div id="welcome-section" class="container-fluid my-5 mx-auto with-vh bg-white">
  <!-- Row for filters and templates -->
  <div class="row justify-content-center p-5">
    <div>
        <h1 class="text-center"><b>Welcome! &#128075;</b></h1>
        <p class="text-center mb-4"><b>Le's build a new calculator</b></p>
    </div>
    <div class="pb-4 d-flex scc-new-calculator-action-container scc-buttons-vertical">
        <button id="scc-start-setup-wizard-button" class="scc-new-calculator-action-btn scc-action-button-primary" type="button" data-btn-action="startSetupWizard"><span class="scc-icn-wrapper"><?php echo scc_get_kses_extended_ruleset( $this->scc_icons['ai-wizard'] ); ?></span> AI-Powered Setup</button>
        <button id="scc-start-with-template-button" class="scc-new-calculator-action-btn" type="button" data-btn-action="showTemplateSelector"><span class="scc-icn-wrapper"><?php echo scc_get_kses_extended_ruleset( $this->scc_icons['layout'] ); ?></span> Start With A Template</button>
        <button id="scc-start-from-scratch-button" class="scc-new-calculator-action-btn" type="button" data-btn-action="showNewCalcNameInput"><span class="scc-icn-wrapper"><?php echo scc_get_kses_extended_ruleset( $this->scc_icons['edit-2'] ); ?></span> Start From Scratch</button>
        <button id="scc-restore-backup-button" class="scc-new-calculator-action-btn <?php echo esc_attr( ( $scc_is_free_version ) ? 'scc-premium-badge' : '' ); ?>" type="button" data-btn-action="showRestorePrompt" <?php echo esc_attr( ( $scc_is_free_version ) ? 'disabled' : '' ); ?> ><span class="scc-icn-wrapper"><?php echo scc_get_kses_extended_ruleset( $this->scc_icons['rotate-ccw'] ); ?></span> Restore A Backup</button>
    </div>

  </div>
</div>