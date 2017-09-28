<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class AcfPostTemplateManager {

    public function __construct() {
        add_filter('acf/location/rule_types', array( $this, 'expand_acf_dropdown' ));
        add_filter('acf/location/rule_values/cpt', array( $this, 'expand_acf_dropdown_values' ));
        add_filter('acf/location/rule_match/cpt', array( $this, 'show_acfs_when_editing_post' ), 10, 3);
    }

    public function expand_acf_dropdown( $choices ) {
        $choices['Post']['cpt'] = 'Post-Vorlage';
        return $choices;
    }

    public function expand_acf_dropdown_values( $choices ) {
        $templates = $this->get_post_templates();
        foreach ($templates as $k => $v) {
            $choices[$k] = $v;
        }
        return $choices;
    }

    public function show_acfs_when_editing_post( $match, $rule, $options ) {
       global $post;

       if (isset($options['cpt'])) {
           $current_post_template = $options['cpt'];
       } else {
           $current_post_template = get_post_meta( $post->ID, '_wp_page_template', true );
       }

       $selected_post_template = $rule['value'];

       if ($rule['operator'] == "==") {
           $match = ( $current_post_template == $selected_post_template );
       } elseif ($rule['operator'] == "!=") {
           $match = ( $current_post_template != $selected_post_template );
       }

       return $match;
   }

    private function get_post_templates() {
        $theme = wp_get_theme();
        $post_templates = array();
        $files = (array) $theme->get_files( 'php', 1 );

        foreach ( $files as $file => $full_path ) {
            $headers = get_file_data( $full_path, array( 'Template Name' => 'Template Name', 'Template Post Type' => 'Template Post Type' ) );
            if ( $headers['Template Post Type'] !== 'post' ) {
                continue;
            }
            $post_templates[ $file ] = $headers['Template Name'];
        }
        return $post_templates;
    }
}