<?php

require_once 'Model.php';

class FeatureModel extends Model {

    public function __construct() {
        parent::__construct();
        $this->__config(ENTITY_NAME_FEATURE, 'feature_id');
    }
}

?>
