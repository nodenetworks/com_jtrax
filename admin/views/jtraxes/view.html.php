<?php
/*------------------------------------------------------------------------
# JTrax
# ------------------------------------------------------------------------
# author    Michał Ostrykiewicz
# copyright Copyright (C) 2010 Giovanni Mansillo. All Rights Reserved.
# copyright Copyright (C) 2020 - 2025 Michał Ostrykiewicz. All rights reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Technical Support:  https://github.com/nodenetworks/jtrax/
-------------------------------------------------------------------------*/

defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Toolbar\ToolbarHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Factory;
use Joomla\CMS\Helper\ContentHelper;

/**
 * View class for a list of tracking codes.
 */
class JtraxViewJtraxes extends BaseHtmlView
{
    protected $items;
    protected $pagination;
    protected $state;
    public $filterForm;
    public $activeFilters;

    /**
     * Display the view
     */
    public function display($tpl = null)
    {
		$this->canDo		 = ContentHelper::getActions('com_jtrax');
		$this->items		 = $this->get('Items');
        $this->pagination	 = $this->get('Pagination');
        $this->state		 = $this->get('State');
        $this->filterForm	 = $this->get('FilterForm');
        $this->activeFilters = $this->get('ActiveFilters');

        // Check for errors.
        if ($errors = $this->get('Errors'))
		{
            foreach ($errors as $error) {
                Factory::getApplication()->enqueueMessage($error, 'error');
            }
        }

        // Add the toolbar
        $this->addToolbar();

        parent::display($tpl);
    }

    /**
     * Add the page title and toolbar.
     */
    protected function addToolbar()
    {
        ToolbarHelper::title(Text::_('COM_JTRAX_TITLE_MAIN'), 'stack');
			if ($this->canDo->get('core.create')) 
			{
				ToolbarHelper::addNew('jtrax.add');
			}
			if ($this->canDo->get('core.edit'))
			{
				ToolbarHelper::editList('jtrax.edit');
			}
			if ($this->canDo->get('core.edit.state'))
			{
				ToolbarHelper::publish('jtraxes.publish', 'JTOOLBAR_PUBLISH', true);
			}
			if ($this->canDo->get('core.edit.state'))
			{
				ToolbarHelper::unpublish('jtraxes.unpublish', 'JTOOLBAR_UNPUBLISH', true);
			}
			if ($this->canDo->get('core.delete')) 
			{
			ToolbarHelper::deleteList(Text::_('COM_JTRAX_DELETE_QUESTION'), 'jtraxes.delete', 'JTOOLBAR_DELETE');
			}
		
		if ($this->canDo->get('core.admin'))
		{
			ToolbarHelper::preferences('com_jtrax');
		}
    }
}
