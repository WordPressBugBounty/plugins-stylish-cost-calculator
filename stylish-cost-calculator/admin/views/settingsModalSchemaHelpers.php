<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! function_exists( 'scc_modal_normalize_text' ) ) {
    function scc_modal_normalize_text( $text ) {
        $text = html_entity_decode( wp_strip_all_tags( (string) $text ), ENT_QUOTES | ENT_HTML5, 'UTF-8' );
        $text = preg_replace( '/\s+/u', ' ', $text );

        return trim( (string) $text );
    }
}

if ( ! function_exists( 'scc_modal_node_has_class' ) ) {
    function scc_modal_node_has_class( $node, $class_name ) {
        if ( ! ( $node instanceof DOMElement ) ) {
            return false;
        }

        $classes = preg_split( '/\s+/', trim( (string) $node->getAttribute( 'class' ) ) );

        return in_array( $class_name, $classes, true );
    }
}

if ( ! function_exists( 'scc_modal_is_hidden' ) ) {
    function scc_modal_is_hidden( $node, $root ) {
        while ( $node instanceof DOMElement ) {
            $class_attr = (string) $node->getAttribute( 'class' );
            $style_attr = strtolower( (string) $node->getAttribute( 'style' ) );

            if ( preg_match( '/(^|\s)(d-none|scc-hidden)(\s|$)/', $class_attr ) ) {
                return true;
            }

            if ( false !== strpos( $style_attr, 'display: none' ) || false !== strpos( $style_attr, 'visibility: hidden' ) ) {
                return true;
            }

            if ( $node->isSameNode( $root ) ) {
                break;
            }

            $node = $node->parentNode;
        }

        return false;
    }
}

if ( ! function_exists( 'scc_modal_get_row_node' ) ) {
    function scc_modal_get_row_node( $node, $root ) {
        while ( $node instanceof DOMElement ) {
            $class_attr = (string) $node->getAttribute( 'class' );

            if ( preg_match( '/(^|\s)(scc-vcenter|scc-cal-settings-row|settings-field-wrapper|row)(\s|$)/', $class_attr ) ) {
                return $node;
            }

            if ( $node->isSameNode( $root ) ) {
                break;
            }

            $node = $node->parentNode;
        }

        return null;
    }
}

if ( ! function_exists( 'scc_modal_get_field_identifier' ) ) {
    function scc_modal_get_field_identifier( DOMElement $field, $fallback_index ) {
        $name = (string) $field->getAttribute( 'name' );
        $id   = (string) $field->getAttribute( 'id' );

        if ( '' !== $name ) {
            return $name;
        }

        if ( '' !== $id ) {
            return $id;
        }

        return 'field_' . $fallback_index;
    }
}

if ( ! function_exists( 'scc_modal_get_callback_identifier' ) ) {
    function scc_modal_get_callback_identifier( DOMElement $field, $fallback_index ) {
        $id      = (string) $field->getAttribute( 'id' );
        $onclick = (string) $field->getAttribute( 'onclick' );
        $event   = (string) $field->getAttribute( 'data-event-type' );

        if ( '' !== $id ) {
            return $id;
        }

        if ( '' !== $event ) {
            $prefix = 'action';

            if ( scc_modal_node_has_class( $field, 'webhook-setup' ) ) {
                $prefix = 'webhook';
            } elseif ( scc_modal_node_has_class( $field, 'custom-js-setup' ) ) {
                $prefix = 'custom_js';
            } elseif ( scc_modal_node_has_class( $field, 'scc-custom-code-setup' ) ) {
                $prefix = 'custom_code';
            }

            return sanitize_key( $prefix . '_' . $event );
        }

        if ( preg_match( '/^\s*([a-zA-Z0-9_]+)/', $onclick, $matches ) ) {
            return sanitize_key( $matches[1] );
        }

        return 'callback_' . $fallback_index;
    }
}

if ( ! function_exists( 'scc_modal_get_field_type' ) ) {
    function scc_modal_get_field_type( DOMElement $field ) {
        if ( 'select' === $field->tagName ) {
            return $field->hasAttribute( 'multiple' ) ? 'select-multiple' : 'select-one';
        }

        $type = (string) $field->getAttribute( 'type' );

        return '' !== $type ? $type : $field->tagName;
    }
}

if ( ! function_exists( 'scc_modal_get_callback_name' ) ) {
    function scc_modal_get_callback_name( DOMElement $field ) {
        $onclick = (string) $field->getAttribute( 'onclick' );

        if ( preg_match( '/^\s*([a-zA-Z0-9_]+)/', $onclick, $matches ) ) {
            return $matches[1];
        }

        if (
            scc_modal_node_has_class( $field, 'webhook-setup' ) ||
            scc_modal_node_has_class( $field, 'custom-js-setup' ) ||
            scc_modal_node_has_class( $field, 'scc-custom-code-setup' )
        ) {
            return 'triggerLegacyControlClick';
        }

        return null;
    }
}

if ( ! function_exists( 'scc_modal_get_inline_section_title' ) ) {
    function scc_modal_get_inline_section_title( DOMElement $field, DOMXPath $xpath, DOMElement $root ) {
        $row_node = scc_modal_get_row_node( $field, $root );

        if ( ! $row_node || ! $row_node->parentNode instanceof DOMElement ) {
            return '';
        }

        $sibling = $row_node->previousSibling;

        while ( $sibling ) {
            if ( $sibling instanceof DOMElement ) {
                if ( scc_modal_node_has_class( $sibling, 'scc-calc-settings-title' ) ) {
                    return scc_modal_normalize_text( $sibling->textContent );
                }

                $titles = $xpath->query( './/*[contains(concat(" ", normalize-space(@class), " "), " scc-calc-settings-title ")]', $sibling );

                if ( $titles instanceof DOMNodeList && $titles->length > 0 ) {
                    return scc_modal_normalize_text( $titles->item( $titles->length - 1 )->textContent );
                }
            }

            $sibling = $sibling->previousSibling;
        }

        return '';
    }
}

if ( ! function_exists( 'scc_modal_get_section_name' ) ) {
    function scc_modal_get_section_name( DOMElement $field, DOMXPath $xpath, DOMElement $root ) {
        $inline_title = scc_modal_get_inline_section_title( $field, $xpath, $root );

        if ( '' !== $inline_title ) {
            return $inline_title;
        }

        $node = $field;

        while ( $node instanceof DOMElement ) {
            if ( scc_modal_node_has_class( $node, 'scc-setting-subsection' ) ) {
                $titles = $xpath->query( './/*[contains(concat(" ", normalize-space(@class), " "), " scc-calc-settings-title ")]', $node );

                if ( $titles instanceof DOMNodeList && $titles->length > 0 ) {
                    return scc_modal_normalize_text( $titles->item( 0 )->textContent );
                }
            }

            if ( scc_modal_node_has_class( $node, 'accordion-item' ) ) {
                $titles = $xpath->query( './/*[contains(concat(" ", normalize-space(@class), " "), " sccsubtitle ") or contains(concat(" ", normalize-space(@class), " "), " modal-title ")]', $node );

                if ( $titles instanceof DOMNodeList && $titles->length > 0 ) {
                    return scc_modal_normalize_text( $titles->item( 0 )->textContent );
                }
            }

            if ( $node->isSameNode( $root ) ) {
                break;
            }

            $node = $node->parentNode;
        }

        return 'General';
    }
}

if ( ! function_exists( 'scc_modal_get_parent_accordion_name' ) ) {
    function scc_modal_get_parent_accordion_name( DOMElement $field, DOMXPath $xpath, DOMElement $root ) {
        $node = $field;

        while ( $node instanceof DOMElement ) {
            if ( scc_modal_node_has_class( $node, 'accordion-item' ) ) {
                $titles = $xpath->query( './/*[contains(concat(" ", normalize-space(@class), " "), " sccsubtitle ") or contains(concat(" ", normalize-space(@class), " "), " modal-title ")]', $node );

                if ( $titles instanceof DOMNodeList && $titles->length > 0 ) {
                    return scc_modal_normalize_text( $titles->item( 0 )->textContent );
                }
            }

            if ( $node->isSameNode( $root ) ) {
                break;
            }

            $node = $node->parentNode;
        }

        return 'General';
    }
}

if ( ! function_exists( 'scc_modal_get_field_label' ) ) {
    function scc_modal_get_field_label( DOMElement $field, DOMXPath $xpath, DOMElement $root ) {
        $field_id = (string) $field->getAttribute( 'id' );

        if ( '' !== $field_id ) {
            $labels = $xpath->query( sprintf( '//label[@for="%s"]', $field_id ) );

            if ( $labels instanceof DOMNodeList && $labels->length > 0 ) {
                return scc_modal_normalize_text( $labels->item( 0 )->textContent );
            }
        }

        $row_node = scc_modal_get_row_node( $field, $root );

        if ( $row_node ) {
            $labels = $xpath->query( './/label[1]', $row_node );

            if ( $labels instanceof DOMNodeList && $labels->length > 0 ) {
                return scc_modal_normalize_text( $labels->item( 0 )->textContent );
            }
        }

        return '';
    }
}

if ( ! function_exists( 'scc_modal_get_field_options' ) ) {
    function scc_modal_get_field_options( DOMElement $field, $label ) {
        $options = [];

        if ( 'select' === $field->tagName ) {
            $sort_order = 0;

            foreach ( $field->getElementsByTagName( 'option' ) as $child ) {
                if ( ! ( $child instanceof DOMElement ) ) {
                    continue;
                }

                $options[] = [
                    'label'     => scc_modal_normalize_text( $child->textContent ),
                    'value'     => (string) $child->getAttribute( 'value' ),
                    'selected'  => $child->hasAttribute( 'selected' ),
                    'disabled'  => $child->hasAttribute( 'disabled' ),
                    'sortOrder' => $sort_order,
                ];
                $sort_order++;
            }
        } elseif ( in_array( scc_modal_get_field_type( $field ), [ 'checkbox', 'radio' ], true ) ) {
            $options[] = [
                'label'     => $label,
                'value'     => (string) $field->getAttribute( 'value' ),
                'selected'  => $field->hasAttribute( 'checked' ),
                'disabled'  => $field->hasAttribute( 'disabled' ),
                'sortOrder' => 0,
            ];
        }

        return $options;
    }
}

if ( ! function_exists( 'scc_modal_get_selected_value' ) ) {
    function scc_modal_get_selected_value( DOMElement $field, DOMXPath $xpath ) {
        $type = scc_modal_get_field_type( $field );

        if ( 'select-multiple' === $type ) {
            $selected_values = [];

            foreach ( $field->getElementsByTagName( 'option' ) as $child ) {
                if ( $child instanceof DOMElement && $child->hasAttribute( 'selected' ) ) {
                    $selected_values[] = (string) $child->getAttribute( 'value' );
                }
            }

            return $selected_values;
        }

        if ( 'checkbox' === $type ) {
            return $field->hasAttribute( 'checked' );
        }

        if ( 'radio' === $type ) {
            $name = (string) $field->getAttribute( 'name' );

            if ( '' !== $name ) {
                $radios = $xpath->query( sprintf( '//input[@type="radio" and @name="%s"]', $name ) );

                if ( $radios instanceof DOMNodeList ) {
                    foreach ( $radios as $radio ) {
                        if ( $radio instanceof DOMElement && $radio->hasAttribute( 'checked' ) ) {
                            return (string) $radio->getAttribute( 'value' );
                        }
                    }
                }
            }

            return null;
        }

        if ( 'textarea' === $field->tagName ) {
            return $field->textContent;
        }

        if ( 'select-one' === $type ) {
            foreach ( $field->getElementsByTagName( 'option' ) as $child ) {
                if ( $child instanceof DOMElement && $child->hasAttribute( 'selected' ) ) {
                    return (string) $child->getAttribute( 'value' );
                }
            }
        }

        return (string) $field->getAttribute( 'value' );
    }
}

if ( ! function_exists( 'scc_modal_get_dataset' ) ) {
    function scc_modal_get_dataset( DOMElement $field ) {
        $dataset = [];

        foreach ( $field->attributes as $attribute ) {
            if ( 0 !== strpos( $attribute->nodeName, 'data-' ) ) {
                continue;
            }

            $dataset_key             = str_replace( '-', '_', substr( $attribute->nodeName, 5 ) );
            $dataset[ $dataset_key ] = $attribute->nodeValue;
        }

        return $dataset;
    }
}

if ( ! function_exists( 'scc_modal_is_disabled' ) ) {
    function scc_modal_is_disabled( DOMElement $field ) {
        return $field->hasAttribute( 'disabled' ) || scc_modal_node_has_class( $field, 'disabled' );
    }
}

if ( ! function_exists( 'scc_modal_get_setting_tooltip_type' ) ) {
    /**
     * Find the legacy setting tooltip key associated with a field.
     *
     * @param DOMElement $field field node
     * @param DOMXPath   $xpath XPath helper
     * @param DOMElement $root  root element
     *
     * @return string
     */
    function scc_modal_get_setting_tooltip_type( DOMElement $field, DOMXPath $xpath, DOMElement $root ) {
        if ( $field->hasAttribute( 'data-setting-tooltip-type' ) ) {
            return (string) $field->getAttribute( 'data-setting-tooltip-type' );
        }

        $field_id = (string) $field->getAttribute( 'id' );

        if ( '' !== $field_id ) {
            $labels = $xpath->query( sprintf( '//label[@for="%s"]//*[@data-setting-tooltip-type]', $field_id ) );

            if ( $labels instanceof DOMNodeList && $labels->length > 0 ) {
                return (string) $labels->item( 0 )->getAttribute( 'data-setting-tooltip-type' );
            }
        }

        $row_node = scc_modal_get_row_node( $field, $root );

        if ( $row_node ) {
            $tooltip_nodes = $xpath->query( './/*[@data-setting-tooltip-type]', $row_node );

            if ( $tooltip_nodes instanceof DOMNodeList && $tooltip_nodes->length > 0 ) {
                return (string) $tooltip_nodes->item( 0 )->getAttribute( 'data-setting-tooltip-type' );
            }
        }

        if ( $field->parentNode instanceof DOMElement ) {
            $tooltip_nodes = $xpath->query( './/*[@data-setting-tooltip-type]', $field->parentNode );

            if ( $tooltip_nodes instanceof DOMNodeList && $tooltip_nodes->length > 0 ) {
                return (string) $tooltip_nodes->item( 0 )->getAttribute( 'data-setting-tooltip-type' );
            }
        }

        return '';
    }
}

if ( ! function_exists( 'scc_generate_modal_settings_schema' ) ) {
    function scc_generate_modal_settings_schema( $modal_markup ) {
        $dom = new DOMDocument( '1.0', 'UTF-8' );

        libxml_use_internal_errors( true );
        $dom->loadHTML( '<?xml encoding="utf-8" ?><div id="scc-modal-schema-root">' . $modal_markup . '</div>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD );
        libxml_clear_errors();

        $root = $dom->getElementById( 'scc-modal-schema-root' );

        if ( ! $root instanceof DOMElement ) {
            return [
                'generatedAt'   => gmdate( 'c' ),
                'modalId'       => 'settingsModal1',
                'totalControls' => 0,
                'sections'      => [],
                'controls'      => [],
            ];
        }

        $xpath          = new DOMXPath( $dom );
        $fields         = $xpath->query( './/input[@name or @id] | .//select[@name or @id] | .//textarea[@name or @id] | .//*[@role="button" and @onclick] | .//i[contains(concat(" ", normalize-space(@class), " "), " material-icons ") and (contains(concat(" ", normalize-space(@class), " "), " webhook-setup ") or contains(concat(" ", normalize-space(@class), " "), " custom-js-setup ") or contains(concat(" ", normalize-space(@class), " "), " scc-custom-code-setup "))]', $root );
        $controls       = [];
        $section_counts = [];
        $section_meta   = [];
        $index          = 0;

        if ( $fields instanceof DOMNodeList ) {
            foreach ( $fields as $field ) {
                if ( ! ( $field instanceof DOMElement ) ) {
                    continue;
                }

                $field_id    = (string) $field->getAttribute( 'id' );
                $is_callback = ! in_array( $field->tagName, [ 'input', 'select', 'textarea' ], true ) && (
                    ( $field->hasAttribute( 'onclick' ) && 'button' === (string) $field->getAttribute( 'role' ) ) ||
                    scc_modal_node_has_class( $field, 'webhook-setup' ) ||
                    scc_modal_node_has_class( $field, 'custom-js-setup' ) ||
                    scc_modal_node_has_class( $field, 'scc-custom-code-setup' )
                );

                if ( 'scc-settings-react-root' === $field_id || 'scc-settings-modal-schema' === $field_id ) {
                    continue;
                }

                $section          = scc_modal_get_section_name( $field, $xpath, $root );
                $parent_accordion = scc_modal_get_parent_accordion_name( $field, $xpath, $root );

                if ( ! isset( $section_counts[ $section ] ) ) {
                    $section_counts[ $section ] = 0;
                }

                if ( ! isset( $section_meta[ $section ] ) ) {
                    $section_meta[ $section ] = [
                        'name'            => $section,
                        'subsection'      => $section,
                        'parentAccordion' => $parent_accordion,
                        'group'           => $parent_accordion,
                    ];
                }

                $key            = $is_callback ? scc_modal_get_callback_identifier( $field, $index ) : scc_modal_get_field_identifier( $field, $index );
                $label          = scc_modal_get_field_label( $field, $xpath, $root );
                $type           = $is_callback ? 'action' : scc_modal_get_field_type( $field );
                $options        = $is_callback ? [] : scc_modal_get_field_options( $field, $label );
                $selected_value = $is_callback ? null : scc_modal_get_selected_value( $field, $xpath );
                $row_node       = scc_modal_get_row_node( $field, $root );
                $value          = $is_callback ? null : ( 'textarea' === $field->tagName ? $field->textContent : (string) $field->getAttribute( 'value' ) );
                $callback_name  = $is_callback ? scc_modal_get_callback_name( $field ) : null;
                $dataset        = scc_modal_get_dataset( $field );
                $tooltip_type   = scc_modal_get_setting_tooltip_type( $field, $xpath, $root );

                if ( '' !== $tooltip_type && ! isset( $dataset['setting_tooltip_type'] ) ) {
                    $dataset['setting_tooltip_type'] = $tooltip_type;
                }

                $metadata = [
                    'key'                => $key,
                    'id'                 => '' !== $field_id ? $field_id : null,
                    'name'               => $is_callback ? $callback_name : ( '' !== (string) $field->getAttribute( 'name' ) ? (string) $field->getAttribute( 'name' ) : null ),
                    'tagName'            => $field->tagName,
                    'type'               => $type,
                    'controlType'        => $is_callback ? 'callback' : 'field',
                    'label'              => $label,
                    'subsection'         => $section,
                    'section'            => $section,
                    'group'              => $parent_accordion,
                    'parentAccordion'    => $parent_accordion,
                    'sortOrder'          => $index,
                    'sortOrderInSection' => $section_counts[ $section ],
                    'placeholder'        => (string) $field->getAttribute( 'placeholder' ),
                    'value'              => 'password' === $type ? '' : $value,
                    'selectedValue'      => $selected_value,
                    'defaultValue'       => $is_callback ? null : ( 'select' === $field->tagName ? null : $value ),
                    'checked'            => ( ! $is_callback && in_array( $type, [ 'checkbox', 'radio' ], true ) ) ? $field->hasAttribute( 'checked' ) : null,
                    'required'           => $field->hasAttribute( 'required' ),
                    'disabled'           => scc_modal_is_disabled( $field ),
                    'visible'            => ! scc_modal_is_hidden( $field, $root ),
                    'options'            => $options,
                    'optionCount'        => count( $options ),
                    'dataset'            => $dataset,
                    'callback'           => $callback_name,
                    'onclick'            => $is_callback ? (string) $field->getAttribute( 'onclick' ) : null,
                    'aria'               => [
                        'label'       => $field->hasAttribute( 'aria-label' ) ? (string) $field->getAttribute( 'aria-label' ) : null,
                        'labelledby'  => $field->hasAttribute( 'aria-labelledby' ) ? (string) $field->getAttribute( 'aria-labelledby' ) : null,
                        'describedby' => $field->hasAttribute( 'aria-describedby' ) ? (string) $field->getAttribute( 'aria-describedby' ) : null,
                    ],
                    'classes'            => (string) $field->getAttribute( 'class' ),
                    'containerClasses'   => $field->parentNode instanceof DOMElement ? (string) $field->parentNode->getAttribute( 'class' ) : '',
                    'rowClasses'         => $row_node ? (string) $row_node->getAttribute( 'class' ) : '',
                ];

                if ( isset( $controls[ $key ] ) ) {
                    if ( ! is_array( $controls[ $key ] ) || ! isset( $controls[ $key ][0] ) ) {
                        $controls[ $key ] = [ $controls[ $key ] ];
                    }

                    $controls[ $key ][] = $metadata;
                } else {
                    $controls[ $key ] = $metadata;
                }

                $section_counts[ $section ]++;
                $index++;
            }
        }

        $sections   = [];
        $sort_order = 0;

        foreach ( $section_counts as $section_name => $field_count ) {
            $sections[] = [
                'label'           => $section_name,
                'name'            => $section_name,
                'subsection'      => $section_name,
                'group'           => isset( $section_meta[ $section_name ]['group'] ) ? $section_meta[ $section_name ]['group'] : 'General',
                'parentAccordion' => isset( $section_meta[ $section_name ]['parentAccordion'] ) ? $section_meta[ $section_name ]['parentAccordion'] : 'General',
                'sortOrder'       => $sort_order,
                'fieldCount'      => $field_count,
            ];
            $sort_order++;
        }

        return [
            'generatedAt'   => gmdate( 'c' ),
            'modalId'       => 'settingsModal1',
            'totalControls' => $index,
            'sections'      => $sections,
            'controls'      => $controls,
        ];
    }
}
