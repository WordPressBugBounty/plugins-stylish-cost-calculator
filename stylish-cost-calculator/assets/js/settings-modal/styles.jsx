// eslint-disable-next-line no-unused-vars -- Required by esbuild's JSX factory.
import { createElement } from './elements';
import { SETTINGS_ROOT_ID } from './constants';

function SettingsStyles() {
	return (
		<style>{`
			#${ SETTINGS_ROOT_ID }{height:68vh;min-height:68vh}
			.scc-settings-shell{display:grid;grid-template-columns:260px minmax(0,1fr);gap:22px;height:68vh;min-height:68vh;overflow:hidden}
			.scc-settings-sidebar{height:100%;min-height:0;padding:10px 12px 10px 8px;overflow-y:auto;overflow-x:hidden;border-right:1px solid #e6ebf4}
			.scc-settings-nav,.scc-settings-group,.scc-settings-subnav,.scc-settings-content-stack{display:flex;flex-direction:column}
			.scc-settings-nav{gap:8px}
			.scc-settings-content-stack{gap:18px}
			.scc-settings-group{gap:8px}
			.scc-settings-group-button{display:flex;align-items:center;justify-content:space-between;width:100%;padding:8px 12px;border:1.5px solid transparent;border-radius:12px;background:#fff;color:#667085;text-align:left;transition:all .18s ease}
			.scc-settings-group-button:hover{background:#f7f9ff;color:#3555f5}
			.scc-settings-group-button.is-active{border-color:#3b57ff;background:#eef2ff;color:#2f4ff2;box-shadow:0 10px 25px rgba(47,79,242,.08)}
			.scc-settings-group-label{display:flex;align-items:center;gap:10px;min-width:0;font-size:1rem;font-weight:700;line-height:1.35}
			.scc-beta-icon{display:inline-block;width:18px;height:18px;flex-shrink:0;color:currentColor}
			.scc-settings-group-chevron{display:inline-block;width:16px;height:16px;flex-shrink:0;transition:transform .18s ease;color:currentColor}
			.scc-settings-group-chevron.is-open{transform:rotate(180deg)}
			.scc-settings-subnav{gap:8px;padding:0 6px 0 16px}
			.scc-settings-subnav-button{padding:0;border:0;background:transparent;color:#6d7890;font-size:.98rem;line-height:1.45;text-align:left}
			.scc-settings-subnav-button:hover,.scc-settings-subnav-button.is-active{color:#3152f2}
			.scc-settings-content{height:100%;min-height:0;padding:8px 8px 0 0;overflow-y:auto;overflow-x:hidden;scroll-behavior:smooth}
			.scc-settings-section-card{background:#fff;border:1px solid #dfe5f0;border-radius:18px;overflow:visible;scroll-margin-top:12px;transition:border-color .18s ease}
			.scc-settings-section-card.is-active{border-color:#3b57ff}
			.scc-settings-section-header{padding:24px 26px 12px}
			.scc-settings-section-eyebrow{display:inline-flex;align-items:center;gap:8px;margin-bottom:12px;padding:6px 10px;border-radius:999px;background:#eef2ff;color:#3152f2;font-size:.8rem;font-weight:700}
			.scc-settings-section-title{margin:0;color:#252b35;font-size:1.45rem;font-weight:700}
			.scc-settings-section-copy{margin:8px 0 0;color:#7a8598;font-size:.96rem;line-height:1.55}
			.scc-settings-section-body{padding:0 18px 6px}
			.scc-settings-row{display:flex;align-items:center;justify-content:space-between;gap:18px;padding:22px 8px;border-top:1px solid #e8edf5}
			.scc-settings-row.has-help{align-items:flex-start}
			.scc-settings-row-main{min-width:0;flex:1}
			.scc-settings-row-title{display:flex;align-items:center;gap:8px;margin:0;color:#2a313c;font-size:1.02rem;font-weight:700;line-height:1.4}
			.scc-settings-row-help{margin:8px 0 0;color:#7d879a;font-size:.94rem;line-height:1.55}
			.scc-settings-row-control{display:flex;align-items:center;justify-content:flex-end;gap:10px;flex-shrink:0;min-width:240px;max-width:390px;width:100%}
			.scc-settings-row-control-item{display:flex;align-items:center;justify-content:flex-end}
			.scc-settings-premium-trigger{display:inline-flex;align-items:center;justify-content:center;width:26px;height:26px;padding:0;border:1px solid rgba(245,158,11,.35);border-radius:999px;background:#fff8eb;color:#d97706;cursor:pointer;transition:background .18s ease,border-color .18s ease,color .18s ease,transform .18s ease}
			.scc-settings-premium-trigger:hover,.scc-settings-premium-trigger:focus{background:#ffefd0;border-color:#f59e0b;color:#b45309;outline:none;transform:translateY(-1px)}
			.scc-settings-gem-icon{width:16px;height:16px;display:block}
			.scc-settings-help-trigger{display:inline-flex;align-items:center;justify-content:center;width:22px;height:22px;color:#8b92a3;cursor:pointer;transition:color .18s ease}
			.scc-settings-help-trigger:hover,.scc-settings-help-trigger:focus{color:#596377;outline:none}
			.scc-settings-help-icon{width:20px;height:20px;display:block}
			.scc-settings-toggle{position:relative;width:44px;height:24px;padding:0;border:0;border-radius:999px;background:#e4e8f0;transition:background .18s ease}
			.scc-settings-toggle.is-on{background:#3756f4}
			.scc-settings-toggle:disabled{opacity:.55;cursor:not-allowed}
			.scc-settings-toggle span{position:absolute;top:2px;left:2px;width:20px;height:20px;border-radius:999px;background:#fff;box-shadow:0 1px 4px rgba(0,0,0,.14);transition:transform .18s ease}
			.scc-settings-toggle.is-on span{transform:translateX(20px)}
			.scc-settings-input-shell,.scc-settings-select-shell,.scc-settings-textarea-shell{position:relative;width:100%;border:1px solid #aeb7c7;border-radius:5px;background:#fff;box-shadow:0 1px 0 rgba(16,24,40,.02);transition:border-color .18s ease,box-shadow .18s ease,background-color .18s ease}
			.scc-settings-input-shell,.scc-settings-select-shell{min-height:50px}
			.scc-settings-input-surface{display:flex;align-items:center;min-height:48px;position:relative;border-radius:inherit;background:linear-gradient(180deg,#ffffff 0%,#fbfcff 100%);transition:background .18s ease}
			.scc-settings-textarea-shell .scc-settings-input-surface{align-items:stretch}
			.scc-settings-input,.scc-settings-select,.scc-settings-textarea{display:block;width:100%;min-height:48px;padding:11px 14px;border:0 !important;outline:0;background:transparent !important;color:#384252;font-size:1rem;font-weight:500;line-height:1.25;box-shadow:none !important;transition:none;appearance:none;-webkit-appearance:none;-moz-appearance:none}
			.scc-settings-input::placeholder,.scc-settings-textarea::placeholder{color:#98a2b3}
			.scc-settings-input-shell:hover,.scc-settings-select-shell:hover,.scc-settings-textarea-shell:hover{border-color:#8f9aae}
			.scc-settings-input:focus,.scc-settings-select:focus,.scc-settings-textarea:focus{outline:none;border:0 !important;box-shadow:none !important}
			.scc-settings-input-shell:focus-within,.scc-settings-select-shell:focus-within,.scc-settings-textarea-shell:focus-within{border-color:#3756f4;box-shadow:0 0 0 3px rgba(55,86,244,.10)}
			.scc-settings-input:disabled,.scc-settings-select:disabled,.scc-settings-textarea:disabled{color:#98a2b3;cursor:not-allowed}
			.scc-settings-input-shell.is-disabled,.scc-settings-select-shell.is-disabled,.scc-settings-textarea-shell.is-disabled{background:#f5f7fb}
			.scc-settings-input-shell.is-focused,.scc-settings-textarea-shell.is-focused{border-color:#3756f4;box-shadow:0 0 0 3px rgba(55,86,244,.10)}
			.scc-settings-input-shell.is-focused .scc-settings-input-surface,.scc-settings-textarea-shell.is-focused .scc-settings-input-surface{background:linear-gradient(180deg,#ffffff 0%,#f6f8ff 100%)}
			.scc-settings-input-shell.has-value,.scc-settings-textarea-shell.has-value{border-color:#95a0b4}
			.scc-settings-input-field{position:relative;z-index:1;margin:0}
			.scc-settings-select{appearance:none !important;-webkit-appearance:none !important;-moz-appearance:none !important;background-image:none !important;padding-right:44px;cursor:pointer}
			.scc-settings-select::-ms-expand{display:none}
			.scc-settings-select::-webkit-list-button{display:none}
			.scc-settings-select-icon{position:absolute;top:50%;right:14px;display:inline-flex;align-items:center;justify-content:center;width:16px;height:16px;transform:translateY(-50%);color:#5f6b7c;pointer-events:none}
			.scc-settings-select-icon .scc-settings-group-chevron{width:16px;height:16px;transform:none}
			.scc-settings-select-icon path{stroke-width:2}
			.scc-settings-custom-select-shell{position:relative}
			.scc-settings-custom-select-shell.is-open{border-color:#3756f4;box-shadow:0 0 0 3px rgba(55,86,244,.10)}
			.scc-settings-custom-select-trigger{display:flex;align-items:center;justify-content:space-between;gap:12px;width:100%;min-height:48px;padding:11px 14px;border:0;background:transparent;color:#384252;text-align:left;cursor:pointer}
			.scc-settings-custom-select-shell.is-disabled .scc-settings-custom-select-trigger{color:#98a2b3;cursor:not-allowed}
			.scc-settings-custom-select-value{display:block;min-width:0;padding-right:28px;color:#384252;font-size:1rem;font-weight:500;line-height:1.25;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
			.scc-settings-custom-select-value.is-placeholder{color:#98a2b3}
			.scc-settings-custom-select-dropdown{position:absolute;top:calc(100% + 10px);left:0;right:0;z-index:20;padding:12px;border:1px solid #dce3ef;border-radius:14px;background:#fff;box-shadow:0 22px 50px rgba(15,23,42,.12)}
			.scc-settings-custom-select-options{display:flex;flex-direction:column;gap:6px;max-height:220px;overflow-y:auto}
			.scc-settings-custom-select-option{display:flex;align-items:center;justify-content:flex-start;width:100%;padding:10px 12px;border:0;border-radius:10px;background:#fff;color:#344054;font-size:.95rem;text-align:left;transition:background .18s ease,color .18s ease}
			.scc-settings-custom-select-option:hover{background:#f4f7ff;color:#3152f2}
			.scc-settings-custom-select-option.is-selected{background:#eef2ff;color:#3152f2;font-weight:600}
			.scc-settings-custom-select-option:disabled{opacity:.45;cursor:not-allowed}
			.scc-settings-custom-select-empty{padding:12px;color:#8a94a6;font-size:.92rem}
			.scc-settings-action{display:inline-flex;align-items:center;justify-content:center;min-width:40px;width:40px;height:40px;padding:0;border:1.5px solid #3c59f4;border-radius:10px;background:#fff;color:#3152f2;font-size:1rem;font-weight:700;transition:all .18s ease}
			.scc-settings-action:hover{background:#eef2ff}
			.scc-settings-action:disabled{opacity:.45;cursor:not-allowed}
			.scc-settings-edit-icon{width:20px;height:20px;display:block}
			.scc-settings-multiselect-shell{position:relative;width:100%}
			.scc-settings-multiselect-trigger{display:flex;align-items:center;justify-content:space-between;gap:12px;width:100%;min-height:48px;padding:11px 14px;border:0;background:transparent;color:#384252;text-align:left}
			.scc-settings-multiselect-shell.is-open{border-color:#3756f4;box-shadow:0 0 0 3px rgba(55,86,244,.10)}
			.scc-settings-multiselect-shell.is-disabled .scc-settings-multiselect-trigger{opacity:.55;cursor:not-allowed}
			.scc-settings-multiselect-values{display:flex;flex-wrap:wrap;align-items:center;gap:8px;min-width:0;flex:1;padding-right:28px}
			.scc-settings-multiselect-placeholder{color:#98a2b3;font-size:1rem;font-weight:500;line-height:1.25}
			.scc-settings-multiselect-chip{display:inline-flex;align-items:center;gap:6px;max-width:100%;padding:5px 10px;border-radius:999px;background:#eef2ff;color:#3152f2;font-size:.9rem;font-weight:600}
			.scc-settings-multiselect-chip span{overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
			.scc-settings-multiselect-chip-remove{display:inline-flex;align-items:center;justify-content:center;width:18px;height:18px;padding:0;border:0;border-radius:999px;background:rgba(49,82,242,.12);color:#3152f2;font-size:.95rem;line-height:1}
			.scc-settings-multiselect-icon{right:14px}
			.scc-settings-multiselect-icon .scc-settings-group-chevron{width:16px;height:16px;transform:none}
			.scc-settings-multiselect-dropdown{position:absolute;top:calc(100% + 10px);left:0;right:0;z-index:20;padding:12px;border:1px solid #dce3ef;border-radius:14px;background:#fff;box-shadow:0 22px 50px rgba(15,23,42,.12)}
			.scc-settings-multiselect-search{margin-bottom:10px}
			.scc-settings-multiselect-search-input{width:100%;min-height:42px;padding:9px 12px;border:1.5px solid #dce3ef;border-radius:10px;background:#f9fbff;color:#2b313b;font-size:.96rem}
			.scc-settings-multiselect-search-input:focus{border-color:#3c59f4;outline:none;box-shadow:0 0 0 2px rgba(60,89,244,.12);background:#fff}
			.scc-settings-multiselect-options{display:flex;flex-direction:column;gap:6px;max-height:220px;overflow-y:auto}
			.scc-settings-multiselect-option{display:flex;align-items:center;justify-content:flex-start;width:100%;padding:10px 12px;border:0;border-radius:10px;background:#fff;color:#344054;font-size:.95rem;text-align:left;transition:background .18s ease,color .18s ease}
			.scc-settings-multiselect-option:hover{background:#f4f7ff;color:#3152f2}
			.scc-settings-multiselect-empty{padding:12px;color:#8a94a6;font-size:.92rem}
			.scc-settings-textarea{min-height:120px;resize:vertical}
			.scc-settings-input[type=number]::-webkit-outer-spin-button,.scc-settings-input[type=number]::-webkit-inner-spin-button{-webkit-appearance:none;margin:0}
			.scc-settings-input[type=number]{-moz-appearance:textfield}
			.scc-settings-empty{padding:28px;border:1px dashed #d7deea;border-radius:16px;background:#fff;color:#738096}
			.scc-settings-badge{display:inline-flex;align-items:center;justify-content:center;min-width:24px;height:24px;padding:0 8px;border-radius:999px;background:#f0f3f9;color:#667085;font-size:.8rem;font-weight:700}
			@media (max-width:1100px){.scc-settings-shell{grid-template-columns:1fr}.scc-settings-sidebar{border-right:0;border-bottom:1px solid #e6ebf4;padding-right:0}.scc-settings-content{padding-right:0}}
			@media (max-width:767px){.scc-settings-row{flex-direction:column;align-items:flex-start}.scc-settings-row-control{max-width:none;min-width:0}}
		`}</style>
	);
}

export { SettingsStyles };
