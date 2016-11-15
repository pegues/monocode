<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require_once 'FrontendController.php';

class Forum extends FrontendController {

    private $__postModel = null;

    public function __construct() {
        $this->__need_authentication = false;
        $this->__authorized_actions = array('new_topic', 'reply');
        parent::__construct();
        $this->__load_model('ForumTopicModel');
        $this->__postModel = $this->____load_model('ForumPostModel');
        $this->__categoryModel = $this->____load_model('ForumCategoryModel');
        $this->__set_module_name(MODULE_NAME_FORUM);
        $this->addToResponseData('categories', $this->__categoryModel->search());
    }

    public function index() {
        $category_id = $this->input->get('c');
        $link = $this->__get_base_url();
        if ($category = $this->__categoryModel->entity($category_id)) {
            $link .= '?c=' . $category_id;
            $this->pushPage($this->createPage($category->name, $link));
        }

        $sort = $this->input->get('s');
        if ($sort && $sort != '') {
            $link .= (strpos($link, '?') > -1 ? '' : '?') . 's=' . $sort;
            $this->pushPage($this->createPage($sort == 't' ? 'Top' : 'Latest', $link));
        }

        $pp = $this->input->get('pp');
        if (!$pp || $pp == '') {
            $pp = PER_PAGE;
        }

        $params = array('category_id' => $category_id, 'sort' => $sort, 'pp' => $pp, 'q' => $this->input->get('q'));
        $this->paginate($this->__get_base_url() . 'index/', $this->__model->__total_count($params), $pp, http_build_query($_GET));
        $this->setEntities($this->__model->search($params, $pp, $this->uri->segment($pp)));
        $this->view($this->__module_name . '/index', $params);
    }

    public function categories() {
        $this->__set_page_title('Categories');
        $this->view($this->__module_name . '/categories');
    }

    public function topic($slug) {
        if (!($topic = $this->__model->getBySlug($slug))) {
            if (!($topic = $this->__model->entity($slug))) {
                $this->redirect($this->__module_name);
            }
        }
        $id = $topic->id;
        if ($category = $this->__categoryModel->entity($topic->category_id)) {
            $link = $this->__get_base_url() . '?c=' . $topic->category_id;
            $this->__page->parents[] = ($this->createPage($category->name, $link));
        }

        $this->__model->saveViewCount($topic->id, $topic->views + 1);
        $this->setEntity($topic);
        $this->setEntities($this->__postModel->search(array('topic_id' => $id)));
        $this->__set_page_title($topic->title);
        $this->view($this->__module_name . '/topic');
    }

    public function new_topic() {
        if ($this->isPost()) {
            $data = $_POST;
            $this->addValidationRule('title', 'Title', 'trim|required');
            $this->addValidationRule('content', 'Post', 'trim|required');
            if ($this->validateForm()) {
                $time = gmdate('Y-m-d H:i:s');
                $content = $data['content'];
                unset($data['content']);
                $data['user_id'] = $this->__account->id;
                $data['last_post_user_id'] = $this->__account->id;
                $data['created_at'] = $time;
                $data['last_posted_at'] = $time;
                $data['highest_post_number'] = 1;
                $data['slug'] = $this->create_slug($data['title']);
                $data['updated_at'] = $time;
                if (($id = $this->__model->save($data)) > 0) {
                    if ($this->__postModel->save(array(
                                'topic_id' => $id,
                                'user_id' => $this->__account->id,
                                'raw' => $content,
                                'cooked' => $content,
                                'post_number' => 1
                            ))) {
                        $this->__categoryModel->save(array(
                            'id' => $data['category_id'],
                            'topic_count' => $this->__model->__total_count(array('category_id' => $data['category_id'])),
                        ));
                        $this->addSuccessMessage('A new topic has been added successfully.');
                        $this->redirect($this->__module_name . '/' . $data['slug']);
                    }
                } else {
                    $this->addErrorMessage('Unkown error occurred while adding a new topic.');
                }
            }
            $this->addToResponseData($_POST);
        }
        $this->view($this->__module_name . '/new_topic');
    }

    function create_slug($string) {
        if (strlen($string) > 150) {
            $string = substr($string, 0, 150);
        }
        $slug = strtolower(preg_replace("/[^a-zA-Z0-9]+/", "-", $string));
        $topics = $this->__model->search(array('slug' => $string));
        if (sizeof($topics) > 0) {
            $slug .= '-' . time();
        }

        return $slug;
    }

    public function reply() {
        if ($this->isPost()) {
            $this->addValidationRule('content', 'Reply', 'trim|required');
            if ($this->validateForm()) {
                $post_id = (int) $this->input->post('p');
                if ($post_id <= 0 || !($post = $this->__postModel->entity($post_id))) {
                    $this->redirect($this->__module_name);
                }
                $topic = $this->__model->entity($post->topic_id);

                $remained = strtotime(gmdate('Y-m-d H:i:s')) - strtotime($post->created_at);
                if ($remained < 20) {
                    $this->addErrorMessage("You have $remained seconds to reply.");
                    $this->redirect($this->__module_name . '/' . ($topic->slug ? $topic->slug : $topic->id));
                }
                $postNumber = $this->__postModel->getNewPostNumber($topic->id);
                $content = $this->input->post('content');
                if ($this->__postModel->save(array(
                            'user_id' => $this->__account->id,
                            'topic_id' => $post->topic_id,
                            'raw' => $content,
                            'cooked' => $content,
                            'post_number' => $postNumber,
                            'reply_to_post_number' => $post->post_number
                        )) > 0) {
                    $time = gmdate('Y-m-d H:i:s');
                    $this->__model->save(array(
                        'id' => $topic->id,
                        'reply_count' => $this->__postModel->__total_count(array('topic_id' => $post->topic_id, 'reply' => true)),
                        'updated_at' => $time,
                        'last_posted_at' => $time,
                        'last_post_user_id' => $this->__account->id,
                        'highest_post_number' => $postNumber
                    ));
                    $this->addSuccessMessage("You've replied to " . $post->user_name . ".");
                    $topic_user = $this->getUserModel()->entity($topic->user_id);
                    $this->sendMailUsingTemplate($topic_user, 'forum-thread-reply', array('thread' => substr($topic->title, 0, 150), 'thread_link' => base_url() . 'forum/' . ($topic->slug ? $topic->slug : $topic->id)));
                    if ($topic->user_id != $post->user_id) {
                        $post_user = $this->getUserModel()->entity($post->user_id);
                        $this->sendMailUsingTemplate($post_user, 'forum-thread-reply', array('thread' => substr($topic->title, 0, 150), 'thread_link' => base_url() . 'forum/' . ($topic->slug ? $topic->slug : $topic->id)));
                    }
                    $this->redirect($this->__module_name . '/' . ($topic->slug ? $topic->slug : $topic->id));
                } else {
                    $this->addErrorMessage('Unkown error occurred while adding a new topic.');
                }
            }
            $this->addToResponseData($_POST);
        } else {
            $post_id = (int) $this->uri->segment(3);
            if ($post_id <= 0 || !($post = $this->__postModel->entity($post_id))) {
                $this->redirect($this->__module_name);
            }
            $topic = $this->__model->entity($post->topic_id);
        }

        $this->addToResponseData('post', $post);
        $this->addToResponseData('topic', $topic);
        $this->view($this->__module_name . '/reply');
    }

}
