<?php

require_once 'Controller.php';

class EditorController extends Controller {

    protected $__workspaces = null;
    protected $__active_workspace = null;
    
    public function __construct() {
        parent::__construct();
        $this->__layout = null;
        $this->__select_environment(ENVIRONMENT_EDITOR);
        
        $this->__workspaces = $this->__get_workspaces();
        $this->__active_workspace = $this->__get_active_workspace();
        
        //$this->addToResponseData('workspaces', $this->__workspaces);
        $this->addToResponseData('active_workspace', $this->__active_workspace);
    }
    
    public function saveWorkspaces() {
        $this->__save_option('ws', json_encode($this->__workspaces, true));
    }
}
