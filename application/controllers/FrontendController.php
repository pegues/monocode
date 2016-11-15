<?php

require_once 'Controller.php';

class FrontendController extends Controller {

    protected $__page = null;

    public function __construct() {
        parent::__construct();
        $this->__select_environment(ENVIRONMENT_FRONTEND);
        $this->__layout = 'default';

        if (!$this->isAjax()) {
            $this->__load_pages();
        }
    }

    private function __load_pages() {
        $list = $this->____load_model('PageModel')->search();
        $pages = array();
        if (count($list) > 0) {
            foreach ($list as $page) {
                $pages[$page->link] = $page;
            }
        }
        $this->addToResponseData('pages', $pages);

        $link = $this->__fetch_link();
        $page = null;
        if (isset($pages[$link])) {
            $page = $pages[$link];
            $this->__set_page_title($page->title);
        } else {
            $page = new stdClass();
            $page->link = $link;
            $page->parent_link = null;
            $segments = explode('/', $link);
            while (count($segments) > 1) {
                $segments = array_slice($segments, 0, 1);
                $parent_link = implode('/', $segments);
                if ($parent_link == $link) {
                    break;
                }

                if (isset($pages[$parent_link])) {
                    $page->parent_link = $parent_link;
                    break;
                }
            }
        }

        $parent_link = $page->parent_link;
        $page->parents = array();
        while ($parent_link) {
            if (isset($pages[$parent_link])) {
                $parent = $pages[$parent_link];
                $page->parents = array_merge(array($parent), $page->parents);
                $parent_link = $parent->parent_link;
            }
        }

        $this->addToResponseData('current_page', $page);
        $this->__page = $page;
    }

    private function __fetch_link() {
        $segments = $this->uri->rsegments;

        if ($segments[1] == 'post') {   //for cms pages
            $link = str_replace(base_url(), '', current_url());
            $len = strlen($link);
            $index = '/index';
            $index_len = strlen($index);
            if ($len > $index_len && substr($link, -$index_len) == $index) {
                $link = substr($index, 0, $len - $index_len);
            }
            return $link;
        }

        if ($this->uri->rsegments[2] == 'index') {
            $segments = array_slice($this->uri->rsegments, 0, 1);
        } else {
            $segments = array_slice($this->uri->rsegments, 0, 2);
        }
        return implode('/', $segments);
    }

    protected function copyPage($page) {
        return $this->createPage($page->title, $page->link, $page->parent_link);
    }

    protected function createPage($title, $link, $parent_link = null) {
        $newpage = new stdClass();
        $newpage->title = $title;
        $newpage->link = $link;
        $newpage->parent_link = $parent_link;
        return $newpage;
    }

    protected function pushPage($page, $copy_parent_link = true) {
        $page->parents = $this->__page->parents;
        if ($copy_parent_link) {
            $page->parent_link = $this->__page->link;
        }
        $page->parents[] = $this->copyPage($this->__page);
        $this->__page = $page;
        $this->addToResponseData('current_page', $this->__page);
        $this->__set_page_title($page->title);
    }

    public function showErrorPage($error) {
        if ($error == 404) {
            $this->__set_page_title('Page Not Found');
            $this->view('errors/404');
        }
    }

}
