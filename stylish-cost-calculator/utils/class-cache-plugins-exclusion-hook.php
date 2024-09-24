<?php
/**
 * Cache plugins to exclude frontend javascript files
 */

namespace DF_SCC\Utils;

class CachePluginExclusionHook {

    public function register_hooks() {
        add_filter( 'rocket_exclude_js', [ $this, 'exclude_js_by_array' ] );
        add_filter( 'litespeed_optm_js_excludes', [ $this, 'exclude_js_by_array' ] );
        add_filter( 'flying_press_js_excludes', [ $this, 'exclude_js_by_array' ] );
        add_filter( 'autoptimize_filter_js_exclude', [ $this, 'exclude_scc_js_autoptimize' ] );
    }

    public function exclude_js_by_array( $excluded_files ) {
        $excluded_files = array_merge( $excluded_files, [
            'wp-content/plugins/stylish-cost-calculator/lib/translate/jquery.translate.js',
            'wp-content/plugins/stylish-cost-calculator/lib/tom-select/tom-select.base.min.js',
            'wp-content/plugins/stylish-cost-calculator/lib/nouislider/nouislider.min.js',
            'wp-content/plugins/stylish-cost-calculator/assets/js/scc-frontend.js',
        ] );

        return $excluded_files;
    }

    public function exclude_scc_js_autoptimize( $excluded_js ) {
        $excluded_js .= ', wp-content/plugins/stylish-cost-calculator/lib/translate/jquery.translate.js';
        $excluded_js .= ', wp-content/plugins/stylish-cost-calculator/lib/tom-select/tom-select.base.min.js';
        $excluded_js .= ', wp-content/plugins/stylish-cost-calculator/lib/nouislider/nouislider.min.js';
        $excluded_js .= ', wp-content/plugins/stylish-cost-calculator/assets/js/scc-frontend.js';

        return $excluded_js;
    }

    public function exclude_sg_optimizer() {
        $exclusions   = get_option( 'sgo_javascript_exclude', [] );
        $exclusions   = array_merge( $exclusions, [
            'wp-content/plugins/stylish-cost-calculator/lib/translate/jquery.translate.js',
            'wp-content/plugins/stylish-cost-calculator/lib/tom-select/tom-select.base.min.js',
            'wp-content/plugins/stylish-cost-calculator/lib/nouislider/nouislider.min.js',
            'wp-content/plugins/stylish-cost-calculator/assets/js/scc-frontend.js',
        ] );
        update_option( 'sgo_javascript_exclude', $exclusions );
    }
}
