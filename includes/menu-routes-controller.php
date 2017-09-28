<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class MenuRoutesController {

    public function __construct() {
        $this->namespace = '/pschild-angular/v1';
        $this->resource_name = 'menu';
    }

    public function register_routes() {
        register_rest_route( $this->namespace, '/' . $this->resource_name . '/(?P<name>[a-zA-Z0-9_-]+)', array(
            array(
                'methods' => 'GET',
                'callback' => array( $this, 'get_menu_by_name' )
            )
        ) );
    }

    public function get_menu_by_name( $request ) {
        $params = $request->get_params();
        $name = $params['name'];
        $wp_menu_object = $name ? wp_get_nav_menu_object( $name ) : array();
        $wp_menu_items = $name ? wp_get_nav_menu_items( $name ) : array();

        $rest_menu = array();

        if ( $wp_menu_object ) {
            $menu = (array) $wp_menu_object;
            $rest_menu['ID'] = abs( $menu['term_id'] );
            $rest_menu['name'] = $menu['name'];
            $rest_menu['slug'] = $menu['slug'];
            $rest_menu['description'] = $menu['description'];
            $rest_menu['count'] = abs( $menu['count'] );

            $rest_menu_items = array();
            foreach ( $wp_menu_items as $item_object ) {
                $rest_menu_items[] = $this->format_menu_item( $item_object );
            }

            $rest_menu_items = $this->nested_menu_items($rest_menu_items, 0);

            $rest_menu['items'] = $rest_menu_items;
            $rest_menu['meta']['links']['collection'] = $rest_url;
            $rest_menu['meta']['links']['self'] = $rest_url . $id;

            return $rest_menu;
        }
    }

    public function format_menu_item( $menu_item, $children = false, $menu = array() ) {

        $item = (array) $menu_item;
        $menu_item = array(
            'id'          => abs( $item['ID'] ),
            'order'       => (int) $item['menu_order'],
            'parent'      => abs( $item['menu_item_parent'] ),
            'title'       => $item['title'],
            'url'         => $item['url'],
            'attr'        => $item['attr_title'],
            'target'      => $item['target'],
            'classes'     => implode( ' ', $item['classes'] ),
            'xfn'         => $item['xfn'],
            'description' => $item['description'],
            'object_id'   => abs( $item['object_id'] ),
            'object'      => $item['object'],
            'object_slug' => get_post( $item['object_id'] )->post_name,
            'type'        => $item['type'],
            'type_label'  => $item['type_label'],
        );

        if ( $children === true && ! empty( $menu ) ) {
            $menu_item['children'] = $this->get_nav_menu_item_children( $item['ID'], $menu );
        }

        return apply_filters( 'rest_menus_format_menu_item', $menu_item );
    }

    public function get_nav_menu_item_children( $parent_id, $nav_menu_items, $depth = true ) {
        $nav_menu_item_list = array();
        foreach ( (array) $nav_menu_items as $nav_menu_item ) {
            if ( $nav_menu_item->menu_item_parent == $parent_id ) {
                $nav_menu_item_list[] = $this->format_menu_item( $nav_menu_item, true, $nav_menu_items );
                if ( $depth ) {
                    if ( $children = $this->get_nav_menu_item_children( $nav_menu_item->ID, $nav_menu_items ) ) {
                        $nav_menu_item_list = array_merge( $nav_menu_item_list, $children );
                    }
                }
            }
        }

        return $nav_menu_item_list;
    }

    private function nested_menu_items( &$menu_items, $parent = null ) {

        $parents = array();
        $children = array();

        // Separate menu_items into parents & children.
        array_map( function( $i ) use ( $parent, &$children, &$parents ){
            if ( $i['id'] != $parent && $i['parent'] == $parent ) {
                $parents[] = $i;
            } else {
                $children[] = $i;
            }
        }, $menu_items );

        foreach ( $parents as &$parent ) {
            if ( $this->has_children( $children, $parent['id'] ) ) {
                $parent['children'] = $this->nested_menu_items( $children, $parent['id'] );
            }
        }

        return $parents;
    }

    private function has_children( $items, $id ) {
        return array_filter( $items, function( $i ) use ( $id ) {
            return $i['parent'] == $id;
        } );
    }
}