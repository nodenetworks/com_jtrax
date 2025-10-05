<?php
/*------------------------------------------------------------------------
# JTrax
# ------------------------------------------------------------------------
# author    Michał Ostrykiewicz
# copyright Copyright (C) 2025 Michał Ostrykiewicz. All rights reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Technical Support:  https://github.com/nodenetworks/jtrax/
-------------------------------------------------------------------------*/

defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\MVC\View\HtmlView;
use Joomla\CMS\Toolbar\ToolbarHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Factory;
use Joomla\CMS\Helper\ContentHelper;

class JtraxViewStatuses extends HtmlView
{
    public $items;
    public $pagination;
    public $state;
	public $filterForm;
	public $activeFilters;

    public function display($tpl = null)
    {
		$this->canDo		 = ContentHelper::getActions('com_jtrax');
		$this->items		 = $this->get('Items');
		$this->pagination	 = $this->get('Pagination');
		$this->state		 = $this->get('State');
		$this->filterForm	 = $this->get('FilterForm');
		$this->activeFilters = $this->get('ActiveFilters');

        if ($errors = $this->get('Errors')) {
            foreach ($errors as $error) {
                Factory::getApplication()->enqueueMessage($error, 'error');
            }
        }

        $this->addToolbar();

        parent::display($tpl);
    }

    protected function addToolbar()
    {
        ToolbarHelper::title(Text::_('COM_JTRAX_STATUSES_TITLE'), 'jtrax');
        ToolbarHelper::addNew('status.add');
        ToolbarHelper::editList('status.edit');
        ToolbarHelper::publish('statuses.publish', 'JTOOLBAR_PUBLISH', true);
        ToolbarHelper::unpublish('statuses.unpublish', 'JTOOLBAR_UNPUBLISH', true);
        ToolbarHelper::deleteList(Text::_('COM_JTRAX_DELETE_QUESTION'), 'statuses.delete', 'JTOOLBAR_DELETE');
        if ($this->canDo->get('core.admin'))
		{
			ToolbarHelper::preferences('com_jtrax');
		}
    }
}