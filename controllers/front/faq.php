<?php

require_once(_PS_MODULE_DIR_ . 'io_faq/classes/Faq.php');

class Io_FaqFaqModuleFrontController extends ModuleFrontController
{
    public $php_self;

    public function initContent()
    {
        $this->php_self = 'faq';

        parent::initContent();

        $id_lang = $this->context->language->id;
        $id_shop = $this->context->shop->id;
        $faqs = Faq::getAll($id_lang, $id_shop);

        $this->context->smarty->assign(array(
            'faqs' => $faqs,
        ));

        $this->setTemplate('module:io_faq/views/templates/front/page.tpl');
    }

    public function getBreadcrumbLinks()
    {
        $breadcrumb = parent::getBreadcrumbLinks();

        $breadcrumb['links'][] = array(
            'title' => $this->l('FAQ', 'io_faq'),
            'url' => $this->context->link->getModuleLink('io_faq', 'faq'),
        );

        return $breadcrumb;
    }
}