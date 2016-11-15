<?php

require_once 'Controller.php';

class Image extends Controller {

    public function Load() {
        $this->load->view('editor/options');

        /*
          elseif($ext== '3d' || '3df' || '3dm' || '3ds' || 'ai' || 'amf' || 'apng' || 'blend' || 'cd2' || 'cd5' || 'cdr' || 'cgm' || 'deep' || 'dwf' || 'dwg' || 'dxf' || 'ecw' || 'emf' || 'eps' || 'fits' || 'gem' || 'hsf' || 'hvif' || 'iges' || 'ilbm' || 'img' || 'imml' || 'ipa' || 'jt' || 'mng' || 'odg' || 'pam' || 'pbm' || 'pcx' || 'pgm' || 'pict' || 'plbm' || 'pnm' || 'ppm' || 'ppt' || 'prc' || 'psb' || 'psd' || 'psp' || 'raw' || 'sid' || 'sgi' || 'skp' || 'step' || 'stl' || 'swf' || 'tga' || 'tif' || 'tiff' || 'xbm' || 'xcf' || 'u3d' || 'vml' || 'vrml' || 'wbmp' || 'webp' || 'wmf' || 'x3d' || 'xaml' || 'xgl' || 'xvl' || 'xvrml'){
          return false; // Add error notice that says: 'The format XXXX is not supported.'
          }
         */
        $image = $this->input->get('image');
        $tempFile = false;
        if (strripos($image, $this->getTempDir()) > -1) {
            $tempFile = true;
            $imagepath = $image;
        } else {
            $imagepath = $this->getWorkshop() . $image;
        }
        $path = $this->getTempDir() . UUID::v4() . '.' . end(explode('.', $image));
        if ($this->isStorageLocal() || $tempFile) {
            copy($imagepath, $path);
        } else {
            $path = $this->getAWSServer()->download2LocalFile($imagepath, $path);
        }
        
        $this->load->view('editor/image', array('path' => $path));
    }

    public function read() {
        $name = $_GET['name'];
        $mimes = array
            (
            'jpg' => 'image/jpg',
            'jpeg' => 'image/jpg',
            'gif' => 'image/gif',
            'png' => 'image/png'
        );

        $ext = strtolower(end(explode('.', $name)));
        header('content-type: ' . $mimes[$ext]);
        header('content-disposition: inline; filename="' . basename($name) . '";');
        readfile($name);
        unlink($name);
    }

}

?>