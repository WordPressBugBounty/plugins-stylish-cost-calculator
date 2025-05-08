<?php
/**
 * Cache plugins to exclude frontend javascript files
 */

namespace DF_SCC\Utils;

class CachePluginExclusionHook {

    public function __construct() {
        $this->register_hooks();
    }

    public function register_hooks() {
        add_filter( 'rocket_exclude_js', [ $this, 'exclude_js_by_array' ] );
        add_filter( 'litespeed_optimize_js_excludes', [ $this, 'exclude_js_by_array' ] );
        add_filter( 'flying_press_exclude_from_minify:js', [ $this, 'exclude_js_by_keyword_array' ] );
        add_filter( 'flying_press_exclude_from_defer:js', [ $this, 'exclude_js_by_keyword_array' ] );
        add_filter( 'flying_press_exclude_from_delay:js', [ $this, 'exclude_js_by_keyword_array' ] );
        add_filter( 'autoptimize_filter_js_exclude', [ $this, 'exclude_scc_js_autoptimize' ] );
        add_filter( 'w3tc_minify_js_do_tag_minification', [ $this, 'exclude_js_by_array' ] );
    }

    public function exclude_js_by_array( $excluded_files ) {
        $excluded_files = is_array($excluded_files) ? $excluded_files : [];

        return $excluded_files;
    }

    public function exclude_js_by_keyword_array( $excluded_files ) {
        $excluded_files[] = 'stylish-cost-calculator';

        return $excluded_files;
    }

    public function exclude_scc_js_autoptimize( $excluded_js ) {
        $excluded_js = array_merge( (array) $excluded_js, [ 'wp-content/plugins/stylish-cost-calculator/assets/js/scc-frontend.js' ] );

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
