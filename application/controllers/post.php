<?php

require_once 'FrontendController.php';

class Post extends FrontendController {

    public function __construct() {
        $this->__need_authentication = false;
        parent::__construct();
        $this->__load_model('CmsModel');
    }

    public function index() {
        if (!$this->__page) {
            return $this->showErrorPage(404);
        }
        $cms = $this->__model->get_by_link($this->__page->link);
        if (!$cms) {
            return $this->showErrorPage(404);
        }
        if ($cms->layout == 'basic') {
            $cms->layout = '';
        } else if ($cms->layout == '' || $cms->layout == null) {
            $cms->layout = null;
            $this->__layout = null;
        }

        $this->view('post', array('cms_content' => $cms->cms_content), $cms->layout);
    }
}

?>