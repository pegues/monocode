<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require_once 'EditorController.php';

class Workspace extends EditorController {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $this->__list();
    }

    public function __list() {
        $this->ajaxResponse(array('workspaces' => $this->__get_workspaces(), 'limit' => $this->__get_feature('work_space')));
    }

    public function create() {
        if ($this->isPost()) {
            $name = $this->input->post('name');
            $type = $this->input->post('type');

            if ($workspace = $this->__add_workspace($name, $type)) {
                $this->addToResponseData('ws', $workspace);
                $this->addSuccessMessage("A new workspace has been created successfully.");
                if ($this->isAjax()) {
                    $this->ajaxResponse();
                } else {
                    $this->redirect(current_url());
                }
            }
            $this->addToResponseData(array('name' => $name, 'type' => $type));
        }

        if ($this->isAjax()) {
            $this->ajaxResponse();
        } else {
            $this->view('workspace');
        }
    }

    public function edit() {
        $workspaces = $this->__get_workspaces();
        $id = $this->uri->segment(3);
        if (!($workspaces && isset($workspaces[$id]) && $workspace = $workspaces[$id])) {
            $this->addErrorMessage("Can't find the workspace.");
            $this->view('workspace');
            return;
        }

        if ($this->isPost()) {
            $workspace['ws_name'] = $this->input->post('name');
            $workspace['ws_type'] = $this->input->post('type');
            if ($this->isStorageAWS() && isset($workspace['ws_domain']) && $workspace['ws_domain']) {
                if ($domain = $this->getSandboxServer()->rename($workspace['ws_domain'], $this->__account->username, $workspace['ws_name'])) {
                    $workspace['ws_domain'] = $domain;
                } else {
                    $this->addErrorMessage('Failed to rename sandbox domain for the workspace.');
                    $this->processSandboxServerError();
                }
            } else {
                if ($domain = $this->getSandboxServer()->create($this->__account->username, $workspace['ws_name'], $this->__account->workshop, $workspace['ws_directory'])) {
                    $workspace['ws_domain'] = $domain;
                } else {
                    $this->addErrorMessage('Failed to create sandbox domain for the workspace.');
                    $this->processSandboxServerError();
                }
            }
            $workspaces[$id] = $workspace;
            $this->__save_option('ws', json_encode($workspaces, true));
            $this->addToResponseData(array('ws' => $workspace));
            $this->addSuccessMessage("The workspace has been updated successfully.");
            $this->redirect(current_url());
        }

        $this->addToResponseData(array(
            'name' => $workspace['ws_name'],
            'type' => isset($workspace['ws_type']) ? $workspace['ws_type'] : 'php',
        ));
        $this->view('workspace');
    }

    function delete() {
        $workspace = $this->input->post('workspace');
        if ($this->__remove_workspaces($workspace) > 0) {
            $this->addSuccessMessage('The workspace has been deleted successfully.');
        } else {
            $this->addErrorMessage("Can't find the workspace.");
        }

        $this->ajaxResponse();
    }

    function activate() {
        $ws_directory = $this->input->post('name');
        if (!$this->__workspaces || !isset($this->__workspaces[$ws_directory])) {
            $this->addErrorMessage("Can't find workspace.");
            return $this->ajaxResponse();
        }

        foreach ($this->__workspaces as $key => $ws) {
            $this->__workspaces[$key]['ws_active'] = false;
        }
        $workspace = $this->__workspaces[$ws_directory];
        $workspace['ws_active'] = true;
        $this->__workspaces[$ws_directory] = $workspace;
        $this->saveWorkspaces();

        if ($this->isStorageLocal()) {
            $this->mkdir($this->getActiveWorkspace());
        }

        $this->ajaxResponse(array('ws' => $workspace));
    }

    public function export() {
        $this->sendMailUsingTemplate($this->__account, 'workspace-backup-requested');
        $name = md5($this->__get_workshop() . '-' . $this->__active_workspace['ws_directory'] . '-' . date("Y-m-d"));
        $this->load->library('Zipper', null, 'zipper');
        $zipFile = 'backups/' . $name . '.zip';
        if ($this->isStorageLocal()) {
            $this->zipper->create($zipFile, $this->getActiveWorkspace());
        } else {
            $this->zipper->createFromAWS($zipFile, $this->getActiveWorkspace(), $this->getAWSFileList($this->getActiveWorkspace()), $this->getAWSServer(), $this->getTempDir());
        }

        $backups = isset($this->__options['ws_backups']) ? json_decode($this->__options['ws_backups'], true) : array();
        $backups[] = array('file' => $zipFile, 'name' => $this->__active_workspace['ws_name']);
        $this->__save_option('ws_backups', json_encode($backups, true));

        if ($this->sendMailUsingTemplate($this->__account, 'workspace-backup-created', array('backup_url' => base_url() . "backups/" . $name . '.zip'))) {
            $this->addSuccessMessage("We'll email you at your address on file once the process has finished. The email will include a link so you can download your archive.");
        }
        $this->ajaxResponse();
    }

    public function import() {
        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            $workspace = $this->input->post('workspace');
            if ($workspace != null && $workspace != '') {
                $filename = $this->__get_temp_file_name('zip');
                $config['upload_path'] = $this->getTempDir();
                $config['allowed_types'] = 'zip';
                $config['file_name'] = $filename;
                $config['file_type'] = 'application/zip';

                $this->load->library('upload', $config);

                if (!$this->upload->do_upload('upload')) {
                    $this->addErrorMessage($this->upload->display_errors());
                } else {
                    $path = $this->getTempDir() . $filename;
                    $this->load->library('Zipper', null, 'zipper');
                    $workspace .= '/';
                    $info = $this->zipper->getFileInfo($path);
                    if ($this->checkFileCount($info['count']) && $this->checkDiskUsage($info['size'])) {
                        if ($this->isStorageLocal()) {
                            $this->zipper->extract($path, $this->getWorkshop() . $workspace);
                        } else {
                            $this->zipper->extractToAWS($path, $this->getWorkshop() . $workspace, $this->getAWSServer(), $this->getAWSFileModel(), $this->getTempDir(), $this->__account->id);
                        }

                        $this->addSuccessMessage("Successfully imported the ZIP file into the workspace.");
                    }
                }
            } else {
                $this->addErrorMessage("Sorry. We couldn't import the ZIP file into the workspace.");
            }
        }

        $this->view('workspace_import');
    }

}
