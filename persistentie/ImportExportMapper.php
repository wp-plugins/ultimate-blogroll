<?php
/**
 * Description of ImportExportMapper
 *
 * @author Jens
 */
class ImportExportMapper {
    public function GetBlogrollWordpress() {
        global $wpdb;
        $table_name = $wpdb->prefix . "links";
        return $wpdb->get_results('SELECT link_url, link_name, link_description, link_visible FROM '.$table_name, ARRAY_A);
    }
}
?>
