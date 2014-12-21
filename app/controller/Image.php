<?php

/*
 * The MIT License
 *
 * Copyright 2014 Vincent Quatrevieux <quatrevieux.vincent@gmail.com>.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

namespace app\controller;

/**
 * Description of Image
 *
 * @author Vincent Quatrevieux <quatrevieux.vincent@gmail.com>
 */
class Image extends \system\mvc\Controller {
    /**
     *
     * @var \app\model\Image
     */
    private $model;
    
    public function __construct(\system\Base $base, \app\model\Image $model) {
        parent::__construct($base);
        $this->model = $model;
    }
    
    public function indexAction(){
        if(!$this->session->isLogged())
            throw new \system\error\Http403Forbidden();
        
        return $this->output->render('image/index.php', array(
            'images' => $this->model->getUserImages($this->session->id)
        ));
    }
    
    public function getAction($owner = '', $file = ''){
        $data = $this->model->getImage($owner, $file);
        
        if(!$data)
            throw new \system\error\Http404Error('Image introuvable');
        
        $this->output->setLayoutTemplate(null);
        $this->output->getHeader()->setMimeType($data['mime']);
        echo $data['data']->bin;
    }
    
    public function uploadAction(){
        if(!$this->session->isLogged())
            throw new \system\error\Http403Forbidden();
        
        if(!empty($_FILES['file'])){
            $file = $_FILES['file'];
            
            if($file['size'] <= $this->config->image->max_size){
                $sizes = getimagesize($file['tmp_name']);
                
                if($sizes){
                    $this->model->storeImage(
                        $this->session->id, 
                        file_get_contents($file['tmp_name']), 
                        $file['type']
                    );
                }
            }
        }
        
        $this->output->getHeader()->setLocation($this->helpers->url('image.php'));
    }
    
    public function infoAction($image){
        if(!$this->session->isLogged())
            throw new \system\error\Http403Forbidden();
        
        if(!$this->model->imageExists($this->session->id, $image))
            throw new \system\error\Http404Error();
        
        return $this->output->render('image/info.php', array(
            'owner' => $this->session->id,
            'image' => $image
        ));
    }
}