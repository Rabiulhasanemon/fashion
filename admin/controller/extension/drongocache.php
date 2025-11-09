<?php
/**
 * Created by PhpStorm.
 * User: Sajid
 * Date: 12-11-15
 * Time: 23.08
 */
class ControllerExtensionDrongocache extends Controller {
    public function clearCategory() {
        $this->cacheManager->clearCache("html", "main_nav");
        $this->cacheManager->clearCache("html", "header_search_widget");
        $this->cache->delete('all.category.list.with.child');
        $this->cache->delete('category.tree');
    }

    public function clearFilter($filter_group_id) {
        $this->load->model("catalog/filter");
        $group_filter_profiles = $this->model_catalog_filter->getGroupFilterProfiles($filter_group_id);
        foreach ($group_filter_profiles as $profile_id) {
            $this->cacheManager->clearCache("html", "filter_profile_" . $profile_id);
        }
    }
}