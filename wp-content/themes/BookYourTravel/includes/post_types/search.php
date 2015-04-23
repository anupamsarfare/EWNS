<?php

	function search_content_and_location_by_term($term, $post_type, $limit) {
	
		global $wpdb, $byt_multi_language_count;
		$search_string = "%" . $term . "%";
		$search_string = strtolower($search_string);
		
		$sql = "SELECT c.post_title FROM $wpdb->posts c ";
		
		if(defined('ICL_LANGUAGE_CODE') && (get_default_language() != ICL_LANGUAGE_CODE || $byt_multi_language_count > 1)) {
			$sql .= " INNER JOIN " . $wpdb->prefix . "icl_translations translations ON (translations.element_type = 'post_accommodation' OR translations.element_type = 'post_location') AND translations.language_code='" . ICL_LANGUAGE_CODE . "' AND translations.element_id = c.ID ";
			$sql .= " INNER JOIN " . $wpdb->prefix . "icl_translations translations_default ON (translations_default.element_type = 'post_accommodation' OR translations.element_type = 'post_location') AND translations_default.language_code='" . get_default_language() . "' AND translations_default.trid = translations.trid ";
		}
		
		$sql .= " WHERE 1=1 ";
		$sql .= $wpdb->prepare(" AND (c.post_type = %s OR c.post_type = 'location') AND c.post_status = 'publish' ", $post_type);
		$sql .= $wpdb->prepare(" AND (c.post_title LIKE '%s' OR c.post_content LIKE '%s' ) ", $search_string, $search_string);
				
		$sql .= $wpdb->prepare(' LIMIT 0, %d', $limit);
		
		return $wpdb->get_results($sql);
	}

?>