<?php
if (!defined('_PS_VERSION_')) {
    exit;
}

class Faq extends ObjectModel
{
    public $id;
    public $id_faq;
    public $question;
    public $answer;
    public $position;
    public $active;

    public static $definition = array(
        'table' => 'io_faq',
        'primary' => 'id_faq',
        'multilang' => true,
        'multilang_shop' => true,
        'fields' => array(
            'position' => array('type' => self::TYPE_INT),
            'active' => array('type' => self::TYPE_BOOL),

            /* Lang fields */
            'question' => array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isGenericName', 'required' => true, 'size' => 255),
            'answer' => array('type' => self::TYPE_HTML, 'lang' => true, 'validate' => 'isCleanHtml'),
        )
    );

    public function add($autoDate = true, $nullValues = false)
    {
        if ($this->position <= 0) {
            $this->position = Faq::getHigherPosition() + 1;
        }

        return parent::add($autoDate, $nullValues);
    }

    public static function getAll($id_lang, $id_shop)
    {
        $sql = 'SELECT f.id_faq, f.position, f.active, fl.question, fl.answer
				FROM ' . _DB_PREFIX_ . 'io_faq f
				LEFT JOIN ' . _DB_PREFIX_ . 'io_faq_lang fl ON (f.id_faq = fl.id_faq AND fl.id_lang = ' . (int)$id_lang . ') 
				WHERE f.active = 1 ORDER BY f.position ASC';

        return Db::getInstance()->executeS($sql);
    }

    public function updatePosition($way, $position)
    {
        if (!$res = Db::getInstance()->executeS('
            SELECT `id_faq`, `position`
			FROM `' . _DB_PREFIX_ . 'io_faq`	 
			ORDER BY `position` ASC
        ')) {
            return false;
        }

        foreach ($res as $faq) {
            if ((int)$faq['id_faq'] == (int)$this->id) {
                $moved_faq = $faq;
            }
        }

        if (!isset($moved_faq) || !isset($position)) {
            return false;
        }

        return Db::getInstance()->execute('
                UPDATE `' . _DB_PREFIX_ . 'io_faq`
                SET `position`= `position` ' . ($way ? '- 1' : '+ 1') . '
                WHERE `position` ' . ($way ? '> ' . (int)$moved_faq['position'] . '
                AND `position` <= ' . (int)$position : '< ' . (int)$moved_faq['position'] . ' 
                AND `position` >= ' . (int)$position)
            ) && Db::getInstance()->execute('
                UPDATE `' . _DB_PREFIX_ . 'io_faq`
                SET `position` = ' . (int)$position . '
                WHERE `id_faq` = ' . (int)$moved_faq['id_faq']
            );
    }

    public static function getHigherPosition()
    {
        $position = Db::getInstance()->getValue('
            SELECT MAX(`position`)
            FROM `' . _DB_PREFIX_ . 'io_faq`
        ');

        return (is_numeric($position)) ? $position : -1;
    }
}