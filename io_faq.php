<?php
/**
 * 2020 Implicto
 *
 * NOTICE OF LICENSE
 *
 * IO FAQ for Prestashop 1.7
 *
 * DISCLAIMER
 *
 * @author Implicto <contact@implicto.com>
 * @copyright 2020 Implicto
 * @license https://implicto.com
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

require_once(_PS_MODULE_DIR_ . 'io_faq/classes/Faq.php');

class Io_Faq extends Module
{
    public function __construct()
    {
        $this->name = 'io_faq';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'IMPLICTO';
        $this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);
        $this->need_instance = 0;
        $this->controllers = array(
            'faq'
        );

        $this->bootstrap = true;

        parent::__construct();

        $this->secure_key = Tools::encrypt($this->name);
        $this->displayName = $this->l('IO FAQ');
        $this->description = $this->l('Module for Frequently Asked Questions');
        $this->link = $this->context->link;

        $this->module_path = $this->local_path;
    }

    public function install()
    {
        if (parent::install() && $this->registerHook('header') && $this->installTab()) {

            $res = true;
            $res &= (bool)Db::getInstance()->execute('
                CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'io_faq` (
                `id_faq` int(10) unsigned NOT NULL auto_increment,
                `position` int(10) unsigned NULL,
                `active` tinyint(1) unsigned default 1,
                PRIMARY KEY (`id_faq`)
                ) ENGINE=' . _MYSQL_ENGINE_ . '  DEFAULT CHARSET=utf8;
            ');

            $res &= (bool)Db::getInstance()->execute('
                CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'io_faq_lang` (
                `id_faq` int(10) unsigned NOT NULL auto_increment,            
                `id_lang` int(10) unsigned default 1,
                `id_shop` int(10) unsigned default 1,
                `question` varchar(255) character set utf8 NOT NULL,
                `answer` text character set utf8 NOT NULL,                
                PRIMARY KEY (`id_faq`, `id_lang`, `id_shop`)
                ) ENGINE=' . _MYSQL_ENGINE_ . '  DEFAULT CHARSET=utf8;
            ');

            return (bool)$res;
        }
        return false;
    }

    public function installTab()
    {
        $tab = new Tab();
        $tab->active = 1;
        $tab->class_name = "AdminIoFaq";
        $tab->name = array();
        $tab->icon = 'school';
        foreach (Language::getLanguages(true) as $lang) {
            $tab->name[$lang['id_lang']] = "IO FAQ";
        }
        $tab->id_parent = 42;
        $tab->module = $this->name;
        return $tab->add();
    }

    public function uninstall()
    {
        if (parent::uninstall() && $this->unregisterHook('header')) {
            return true;
        }
        return false;
    }

    public function hookHeader()
    {
        $this->context->controller->addCss('/modules/' . $this->name . '/views/css/io_faq.css');
    }
}