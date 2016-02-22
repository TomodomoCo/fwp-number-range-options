<?php
/*
Plugin Name: Number Range Options Facet for FacetWP
Plugin URI: https://facetwp.com/
Description: Custom numeric range facet using pre-defined options
Version: 1.2.0
Author: Van Patten Media Inc.
Author URI: https://www.vanpattenmedia.com/
Text Domain: fwp_number_range_options
*/

class FwpNumberRangeOptions {

	function __construct() {
		$this->label = __( 'Number Range (Options)', 'fwp_number_range_options' );

		add_filter( 'facetwp_index_row', array( $this, 'index_row' ), 5, 2 );
	}


	function get_count( $params, $values ) {
		global $wpdb;

		$facet = $params['facet'];
		$where = '';

		// For dual ranges, find any overlap
		if ( ! empty( $facet['source_other'] ) ) {
			$start = empty( $values[0] ) ? -999999999999 : $values[0];
			$end = empty( $values[1] ) ? 999999999999 : $values[1];

			// http://stackoverflow.com/a/325964
			$where .= " AND (facet_value + 0) <= '$end'";
			$where .= " AND (facet_display_value + 0) >= '$start'";
		}
		// Otherwise, do a basic comparison
		else {
			if ( '' != $values[0] ) {
				$where .= " AND (facet_value + 0) >= '{$values[0]}'";
			}
			if ( '' != $values[1] ) {
				$where .= " AND (facet_display_value + 0) <= '{$values[1]}'";
			}
		}

		$sql = "
		SELECT DISTINCT post_id FROM {$wpdb->prefix}facetwp_index
		WHERE facet_name = '{$facet['name']}' $where";

		return count( $wpdb->get_col( $sql ) );
	}


    /**
     * Load the available choices
     */
    function load_values( $params ) {
		// Empty output array
		$output = array();

		// Get the pre-defined range option choices
		$facet_choices = explode( "\n", $params['facet']['choices'] );

		foreach ( $facet_choices as $choice ) {

			// Split the label from the range
			$choice = explode( ' | ', $choice );

			// Get the min/max
			$range_vals = explode( '-', $choice[1] );

			$output[] = array(
				'label' => $choice[0],
				'range' => $choice[1],
				'min'   => $range_vals[0],
				'max'   => $range_vals[1],
				'count' => $this->get_count( $params, $range_vals ),
			);
		}

        return $output;
    }


	/**
	 * Generate the facet HTML
	 */
	function render( $params ) {
		// Start the output
		$output = '';

		// Get the field values
        $choices = (array) $params['values'];

		// Get the current values
		$value = $params['selected_values'];
		$value = empty( $value ) ? '' : implode( '-', $value );

		// Loop through the choices
		foreach( $choices as $choice ) {

			// Determine whether or not to check the option
			if ( $choice['range'] == $value )
				$selected = ' checked';
			else
				$selected = '';

			// Add the option
			$output .= '<div class="facetwp-number-range-option-wrap"><label class="facetwp-number-range-option"><input type="radio" name="facetwp_' . $params['facet']['name'] . '" value="' . $choice['range'] . '" data-facetwp-min="' . $choice['min'] . '" data-facetwp-max="' . $choice['max'] . '"' . $selected . '> ' . $choice['label'] . ' (' . $choice['count'] . ')</label></div>';
		}

		// Return the options
		return $output;
	}


	/**
	 * Filter the query based on selected values
	 */
	function filter_posts( $params ) {
		global $wpdb;

		$facet  = $params['facet'];
		$values = $params['selected_values'];
		$where  = '';

		// For dual ranges, find any overlap
		if ( ! empty( $facet['source_other'] ) ) {
			$start = empty( $values[0] ) ? -999999999999 : $values[0];
			$end = empty( $values[1] ) ? 999999999999 : $values[1];

			// http://stackoverflow.com/a/325964
			$where .= " AND (facet_value + 0) <= '$end'";
			$where .= " AND (facet_display_value + 0) >= '$start'";
		}
		// Otherwise, do a basic comparison
		else {
			if ( '' != $values[0] ) {
				$where .= " AND (facet_value + 0) >= '{$values[0]}'";
			}
			if ( '' != $values[1] ) {
				$where .= " AND (facet_display_value + 0) <= '{$values[1]}'";
			}
		}

		$sql = "
		SELECT DISTINCT post_id FROM {$wpdb->prefix}facetwp_index
		WHERE facet_name = '{$facet['name']}' $where";
		return $wpdb->get_col( $sql );
	}


	/**
	 * Output any admin scripts
	 */
	function admin_scripts() {
?>
<script>
(function($) {
	wp.hooks.addAction('facetwp/load/number_range_options', function($this, obj) {
		$this.find('.facet-source').val(obj.source);
		$this.find('.facet-source-other').val(obj.source_other);
		$this.find('.facet-choices').val(obj.choices);
	});

	wp.hooks.addFilter('facetwp/save/number_range_options', function($this, obj) {
		obj['source']       = $this.find('.facet-source').val();
		obj['source_other'] = $this.find('.facet-source-other').val();
		obj['choices']      = $this.find('.facet-choices').val();
		return obj;
	});
})(jQuery);
</script>
<?php
	}


	/**
	 * Output any front-end scripts
	 */
	function front_scripts() {
?>
<script>
(function($) {
	wp.hooks.addAction('facetwp/refresh/number_range_options', function($this, facet_name) {
		var min = $this.find('.facetwp-number-range-option input:checked').attr('data-facetwp-min') || '';
		var max = $this.find('.facetwp-number-range-option input:checked').attr('data-facetwp-max') || '';
		FWP.facets[facet_name] = ('' != min || '' != max) ? [min, max] : [];
	});

	wp.hooks.addFilter('facetwp/selections/number_range_options', function(output, params) {
		return params.selected_values[0] + ' - ' + params.selected_values[1];
	});

	wp.hooks.addAction('facetwp/ready', function() {
		$(document).on('change', '.facetwp-number-range-option input', function() {
			FWP.autoload();
		});
	});
})(jQuery);
</script>
<?php
	}


	/**
	 * (Admin) Output settings HTML
	 */
	function settings_html() {
		$sources = FWP()->helper->get_data_sources();
?>
		<tr>
			<td>
				<?php _e('Other data source', 'fwp'); ?>:
				<div class="facetwp-tooltip">
					<span class="icon-question">?</span>
					<div class="facetwp-tooltip-content"><?php _e( 'Use a separate value for the upper limit?', 'fwp' ); ?></div>
				</div>
			</td>
			<td>
				<select class="facet-source-other">
					<option value=""><?php _e( 'None', 'fwp' ); ?></option>
					<?php foreach ( $sources as $group ) : ?>
					<optgroup label="<?php echo $group['label']; ?>">
						<?php foreach ( $group['choices'] as $val => $label ) : ?>
						<option value="<?php echo esc_attr( $val ); ?>"><?php echo esc_html( $label ); ?></option>
						<?php endforeach; ?>
					</optgroup>
					<?php endforeach; ?>
				</select>
			</td>
		</tr>
		<tr>
			<td>
				<?php _e('Choices', 'fwp'); ?>:
				<div class="facetwp-tooltip">
					<span class="icon-question">?</span>
					<div class="facetwp-tooltip-content"><?php _e( 'Enter the available choices (one per line)', 'fwp' ); ?></div>
				</div>
			</td>
			<td><textarea class="facet-choices"></textarea></td>
		</tr>
<?php
	}


	/**
	 * Index the 2nd data source
	 * @since 2.1.1
	 */
	function index_row( $params, $class ) {
		if ( $class->is_overridden ) {
			return $params;
		}

		$facet = FWP()->helper->get_facet_by_name( $params['facet_name'] );

		if ( 'number_range' == $facet['type'] && ! empty( $facet['source_other'] ) ) {
			$other_params = $params;
			$other_params['facet_source'] = $facet['source_other'];
			$rows = $class->get_row_data( $other_params );
			$params['facet_display_value'] = $rows[0]['facet_display_value'];
		}

		return $params;
	}

}

add_filter( 'facetwp_facet_types', function( $types ) {
	$types['number_range_options'] = new FwpNumberRangeOptions;
	return $types;
} );
