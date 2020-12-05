<?php

class AdminIoFaqController extends ModuleAdminController
{
    protected $position_identifier = 'id_faq';

    public function __construct()
    {
        $this->bootstrap = true;
        $this->table = 'io_faq';
        $this->list_id = 'io_faq';
        $this->identifier = 'id_faq';
        $this->className = 'Faq';
        $this->lang = true;
        $this->deleted = false;
        $this->_defaultOrderBy = 'position';

        $this->addRowAction('edit');
        $this->addRowAction('delete');


        parent::__construct();

        $this->fields_list = array(
            'id_faq' => array(
                'title' => $this->trans('ID', array(), 'Modules.IoFaq.Admin'),
                'type' => 'text',
            ),
            'question' => array(
                'title' => $this->trans('Question', array(), 'Modules.IoFaq.Admin'),
                'type' => 'text',
            ),
            'position' => array(
                'title' => $this->trans('Position', array(), 'Modules.IoFaq.Admin'),
                'filter_key' => 'position',
                'position' => 'position'
            ),
            'active' => array(
                'title' => $this->trans('Status', array(), 'Modules.IoFaq.Admin'),
                'width' => 70,
                'active' => 'status',
                'align' => 'center',
                'type' => 'bool',
            ),
        );
    }

    public function init()
    {
        parent::init();
    }

    public function renderList()
    {
        return parent::renderList();
    }

    public function renderForm()
    {
        $this->fields_form = array(
            'legend' => array(
                'title' => $this->trans('New FAQ', array(), 'Admin.Global'),
                'icon' => 'icon-cogs'
            ),
            'input' => array(
                array(
                    'type' => 'text',
                    'label' => $this->trans('Question', array(), 'Admin.Global'),
                    'name' => 'question',
                    'id' => 'question',
                    'lang' => true,
                    'required' => true,
                ),
                array(
                    'type' => 'textarea',
                    'label' => $this->trans('Answer', array(), 'Admin.Design.Feature'),
                    'name' => 'answer',
                    'autoload_rte' => true,
                    'lang' => true,
                    'rows' => 5,
                    'cols' => 40,
                    'size' => 9999999999
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->trans('Displayed', array(), 'Admin.Global'),
                    'name' => 'active',
                    'required' => false,
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->trans('Enabled', array(), 'Admin.Global'),
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->trans('Disabled', array(), 'Admin.Global'),
                        ),
                    ),
                ),
            ),
            'submit' => array(
                'title' => $this->trans('Save', array(), 'Admin.Actions'),
            ),
        );

        if (!($obj = $this->loadObject(true))) {
            return;
        }

        return parent::renderForm();
    }

    public function ajaxProcessUpdatePositions()
    {
        $way = (int) (Tools::getValue('way'));
        $id_faq = (int) (Tools::getValue('id'));
        $positions = Tools::getValue('faq');

        foreach ($positions as $position => $value) {
            $pos = explode('_', $value);

            if (isset($pos[2]) && (int) $pos[2] === $id_faq) {
                if ($faq = new Faq((int) $pos[2])) {
                    if (isset($position) && $faq->updatePosition($way, $position)) {
                        echo 'Correct position ' . (int) $position . ' for FAQ ' . (int) $pos[1] . '\r\n';
                    } else {
                        echo '{"hasError" : true, "errors" : "Can not update FAQ ' . (int) $id_faq . ' to position ' . (int) $position . ' "}';
                    }
                } else {
                    echo '{"hasError" : true, "errors" : "This FAQ (' . (int) $id_faq . ') cant be loaded"}';
                }

                break;
            }
        }
    }

}
