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
    
    /**
     *
     * @var \app\model\Account
     */
    private $account;
    
    /**
     *
     * @var \app\model\Event
     */
    private $event;
    
    public function __construct(\system\Base $base, \app\model\Image $model, \app\model\Account $account, \app\model\Event $event) {
        parent::__construct($base);
        $this->model = $model;
        $this->account = $account;
        $this->event = $event;
    }
    
    public function indexAction(){
        if(!$this->session->isLogged())
            throw new \system\error\Http403Forbidden();
        
        $this->output->setTitle('Mes images');
        $this->output->addKeyword('images');
        
        return $this->output->render('image/index.php', array(
            'images' => $this->model->getUserImages($this->session->id)
        ));
    }
    
    public function getAction($owner = '', $file = ''){
        $data = $this->model->getImage($owner, $file);
        
        if(!$data)
            throw new \system\error\Http404Error('Image introuvable');
        
        $this->output->setLayoutTemplate(null);
        $this->output->getHeader()->setMimeType(!empty($data['mime']) ? $data['mime'] : 'image/png');
        $this->output->getHeader()->set('Cache-Control', 'public');
        $this->output->getHeader()->set('Expires', gmstrftime("%a, %d %b %Y %H:%M:%S GMT", time() + 86400));
        
        echo $data['data']->bin;
    }
    
    public function uploadAction(){
        if(!$this->session->isLogged())
            throw new \system\error\Http403Forbidden();
        
        if(!empty($_FILES['file']) && !empty($_FILES['file']['tmp_name'])){
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
        
        $this->output->getHeader()->setLocation($this->input->getReferer());
    }
    
    public function infoAction($image = ''){
        if(!$this->session->isLogged())
            throw new \system\error\Http403Forbidden();
        
        if(!$this->model->imageExists($this->session->id, $image))
            throw new \system\error\Http404Error('Image inexistante');
        
        $this->output->setTitle('Mes images');
        
        return $this->output->render('image/info.php', array(
            'owner' => $this->session->id,
            'image' => $image
        ));
    }
    
    public function deleteAction($image = ''){
        if(!$this->session->isLogged())
            throw new \system\error\Http403Forbidden();
        
        $this->model->delete($this->session->id, $image);
        
        if($this->account->getAvatar($this->session->id) == $image)
            $this->account->setAvatar ($this->session->id, '');
        
        $this->output->getHeader()->setLocation($this->helpers->url('image.php'));
    }
    
    public function useAction($image = ''){
        if(!$this->session->isLogged() || !$this->model->imageExists($this->session->id, $image))
            throw new \system\error\Http403Forbidden();
        
        $this->account->setAvatar($this->session->id, $image);
        $this->output->getHeader()->setLocation($this->helpers->url('account.php'));
    }
    
    public function selectAction($event_id = 0){
        $event_id = (int)$event_id;
        
        if(!$this->session->isLogged() || !$this->event->isOrganizer($this->session->id, $event_id))
            throw new \system\error\Http403Forbidden();
        
        $this->output->setTitle('Sélectionner une image');
        
        return $this->output->render('image/select.php', array(
            'event' => $event_id,
            'images' => $this->model->getUserImages($this->session->id)
        ));
    }
    
    public function useeventAction($event_id = 0, $file = ''){
        $event_id = (int)$event_id;
        
        if(!$this->session->isLogged() 
                || !$this->event->isOrganizer($this->session->id, $event_id)
                || !$this->model->imageExists($this->session->id, $file))
            throw new \system\error\Http403Forbidden();
        
        $this->event->setImage($event_id, $file);
        $this->output->getHeader()->setLocation($this->helpers->secureUrl('events', 'show', $event_id));
    }
}
