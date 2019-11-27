<?php

require_once(__DIR__ . '/' . 'SearchReplacer.php');

/**
 * Search and replace strings in the database.
 *
 * @package wp-cli
 */
class Learn_Med_Search_Replace_Command extends WP_CLI_Command {

	/**
	 * Search/replace strings in the database.
	 *
	 * ## DESCRIPTION
	 *
	 * This command will go through all rows in all tables and will replace all
	 * appearances of the old string with the new one.
	 *
	 * It will correctly handle serialized values, and will not change primary key values.
	 *
	 * ## OPTIONS
	 *
	 * <old>
	 * : The old string.
	 *
	 * <new>
	 * : The new string.
	 *
	 * [<table>...]
	 * : List of database tables to restrict the replacement to.
	 *
	 * [--network]
	 * : Search/replace through all the tables in a multisite install.
	 *
	 * [--skip-columns=<columns>]
	 * : Do not perform the replacement in the comma-separated columns.
	 *
	 * [--dry-run]
	 * : Show report, but don't perform the changes.
	 *
	 * [--exact-match]
	 * : Only replace values that are an exact match
	 *
   * [--case-insensitive]
	 * : Case insensitive search
	 *
	 * [--recurse-objects]
	 * : Enable recursing into objects to replace strings
	 *
	 * ## EXAMPLES
	 *
	 *     wp search-replace 'http://example.dev' 'http://example.com' --skip-columns=guid
	 *
	 *     wp search-replace 'foo' 'bar' wp_posts wp_postmeta wp_terms --dry-run
	 *
	 *     # Turn your production database into a local database
	 *     wp search-replace --url=example.com example.com example.dev
	 */
	public function __invoke( $args, $assoc_args ) {
		global $wpdb;
		$old = array_shift( $args );
		$new = array_shift( $args );
		$total = 0;
		$report = array();
		$dry_run = isset( $assoc_args['dry-run'] );
		$exact_match = isset( $assoc_args['exact-match'] );
    $case_insensitive = isset( $assoc_args['case-insensitive'] );
		$recurse_objects = isset( $assoc_args['recurse-objects'] );

		if ( isset( $assoc_args['skip-columns'] ) )
			$skip_columns = explode( ',', $assoc_args['skip-columns'] );
		else
			$skip_columns = array();

		// never mess with hashed passwords
		$skip_columns[] = 'user_pass';

		$tables = self::get_table_list( $args, isset( $assoc_args['network'] ) );

		foreach ( $tables as $table ) {
			list( $primary_keys, $columns ) = self::get_columns( $table );

			// since we'll be updating one row at a time,
			// we need a primary key to identify the row
			if ( empty( $primary_keys ) ) {
				$report[] = array( $table, '', 'skipped' );
				continue;
			}

			foreach ( $columns as $col ) {
				if ( in_array( $col, $skip_columns ) ) {
					continue;
				}

				$type = 'PHP';
				$count = self::handle_col( $col, $primary_keys, $table, $old, $new, $dry_run, $recurse_objects, $exact_match, $case_insensitive);

				$report[] = array( $table, $col, $count, $type );

				$total += $count;
			}
		}

		if ( ! WP_CLI::get_config( 'quiet' ) ) {

			$table = new \cli\Table();
			$table->setHeaders( array( 'Table', 'Column', 'Replacements', 'Type' ) );
			$table->setRows( $report );
			$table->display();

			if ( !$dry_run )
				WP_CLI::success( "Made $total replacements." );

		}
	}

	private static function get_table_list( $args, $network ) {
		global $wpdb;

		if ( !empty( $args ) )
			return $args;

		$prefix = $network ? $wpdb->base_prefix : $wpdb->prefix;
		$matching_tables = $wpdb->get_col( $wpdb->prepare( "SHOW TABLES LIKE %s", $prefix . '%' ) );

		$allowed_tables = array();
		$allowed_table_types = array( 'tables', 'global_tables' );
		if ( $network ) {
			$allowed_table_types[] = 'ms_global_tables';
		}
		foreach( $allowed_table_types as $table_type ) {
			foreach( $wpdb->$table_type as $table ) {
				$allowed_tables[] = $prefix . $table;
			}
		}

		// Given our matching tables, also allow site-specific tables on the network
		foreach( $matching_tables as $key => $matched_table ) {

			if ( in_array( $matched_table, $allowed_tables ) ) {
				continue;
			}

			if ( $network ) {
				$valid_table = false;
				foreach( array_merge( $wpdb->tables, $wpdb->old_tables ) as $maybe_site_table ) {
					if ( preg_match( "#{$prefix}([\d]+)_{$maybe_site_table}#", $matched_table ) ) {
						$valid_table = true;
					}
				}
				if ( $valid_table ) {
					continue;
				}
			}

			unset( $matching_tables[ $key ] );

		}

		return array_values( $matching_tables );

	}

	private static function handle_col( $col, $primary_keys, $table, $old, $new, $dry_run, $recurse_objects, $exact_match, $case_insensitive ) {
		global $wpdb;

		// We don't want to have to generate thousands of rows when running the test suite
		$chunk_size = getenv( 'BEHAT_RUN' ) ? 10 : 1000;

		$fields = $primary_keys;
		$fields[] = $col;

		$args = array(
			'table' => $table,
			'fields' => $fields,
			'where' => "`$col`" . ' LIKE "%' . self::esc_like( $old ) . '%"',
			'chunk_size' => $chunk_size
		);

		$it = new \WP_CLI\Iterators\Table( $args );

		$count = 0;

		$replacer = new \WP_CLI\LearnMed\SearchReplacer( $old, $new, $recurse_objects, $exact_match, $case_insensitive );

		foreach ( $it as $row ) {
			if ( '' === $row->$col )
				continue;

			$value = $replacer->run( $row->$col );

			if ( $dry_run ) {
				if ( $value != $row->$col )
					$count++;
			} else {
				$where = array();
				foreach ( $primary_keys as $primary_key ) {
					$where[ $primary_key ] = $row->$primary_key;
				}

				$count += $wpdb->update( $table, array( $col => $value ), $where );
			}
		}

		return $count;
	}

	private static function get_columns( $table ) {
		global $wpdb;

		$primary_keys = array();

		$columns = array();

		foreach ( $wpdb->get_results( "DESCRIBE $table" ) as $col ) {
			if ( 'PRI' === $col->Key ) {
				$primary_keys[] = $col->Field;
				continue;
			}

			if ( !self::is_text_col( $col->Type ) )
				continue;

			$columns[] = $col->Field;
		}

		return array( $primary_keys, $columns );
	}

	private static function is_text_col( $type ) {
		foreach ( array( 'text', 'varchar' ) as $token ) {
			if ( false !== strpos( $type, $token ) )
				return true;
		}

		return false;
	}

	private static function esc_like( $old ) {
		global $wpdb;

		// Remove notices in 4.0 and support backwards compatibility
		if( method_exists( $wpdb, 'esc_like' ) ) {
			// 4.0
			$old = $wpdb->esc_like( $old );
		} else {
			// 3.9 or less
			$old = like_escape( esc_sql( $old ) );
		}

		return $old;
	}
}

WP_CLI::add_command( 'learn-med search-replace', 'Learn_Med_Search_Replace_Command' );
