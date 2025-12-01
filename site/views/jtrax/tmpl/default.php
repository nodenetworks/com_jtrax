<?php
/*------------------------------------------------------------------------
# JTrax
# ------------------------------------------------------------------------
# author    Michał Ostrykiewicz
# copyright Copyright (C) 2010 Giovanni Mansillo. All Rights Reserved.
# copyright Copyright (C) 2025 Michał Ostrykiewicz. All rights reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Technical Support:  https://github.com/nodenetworks/jtrax/
-------------------------------------------------------------------------*/

defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;

// Load Bootstrap 5 framework to ensure modal works
HTMLHelper::_('bootstrap.framework');

// Extract parameters
$params = $this->params;

// Where-to-find text and image
$wheretofindtext = $params->get('wheretofindtext', Text::_('COM_JTRAX_SEARCH_WHERE_TO_FIND'));
$wheretofindimage = $params->get('wheretofindimage', 'media/com_jtrax/images/wheretofind.jpg');

// Build a lookup map of status id -> title for display (if statuses provided by the view)
$statusMap = [];
if (!empty($this->statuses) && is_array($this->statuses)) {
    foreach ($this->statuses as $s) {
        $statusMap[(int) $s->id] = $s->title;
    }
}
?>

<!-- Intro text -->
<p><?php echo $params->get('introtext', Text::_('COM_JTRAX_SEARCH_INTRO_TEXT')); ?></p>

<!-- Where to find modal trigger -->
<?php if ($params->get('wheretofind', '1') == 1): ?>
    <p>
        <a href="#" data-bs-toggle="modal" data-bs-target="#wheretofindModal">
            <?php echo htmlspecialchars($wheretofindtext, ENT_QUOTES, 'UTF-8'); ?>
        </a>
    </p>

    <!-- Modal -->
    <div class="modal fade" id="wheretofindModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><?php echo htmlspecialchars($wheretofindtext, ENT_QUOTES, 'UTF-8'); ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="<?php echo Text::_('JTOOLBAR_CLOSE'); ?>"></button>
                </div>
                <div class="modal-body text-center">
                    <img src="<?php echo htmlspecialchars($wheretofindimage, ENT_QUOTES, 'UTF-8'); ?>" 
                         class="img-fluid"
                         alt="<?php echo htmlspecialchars($wheretofindtext, ENT_QUOTES, 'UTF-8'); ?>" />
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<!-- Search form -->
<form name="input" action="<?php echo Route::_('index.php?option=com_jtrax&Itemid=' . (int) $this->itemid); ?>" method="post" class="mb-3">
    <?php echo HTMLHelper::_('form.token'); ?>
    <input type="hidden" name="Itemid" value="<?php echo (int) $this->itemid; ?>" />
    <label for="code"><?php echo $params->get('label', Text::_('COM_JTRAX_SEARCH_LABEL')); ?></label>
    <input type="text" name="code" id="code" maxlength="31" value="<?php echo htmlspecialchars($this->searchterm ?? '', ENT_QUOTES, 'UTF-8'); ?>" class="form-control d-inline-block w-auto" />
    <input type="submit" value="<?php echo $params->get('button', Text::_('COM_JTRAX_SEARCH_BUTTON')); ?>" class="btn btn-primary" />
</form>

<!-- Search results -->
<?php if (!empty($this->information) && is_array($this->information)) : ?>
    <p><?php echo Text::_('COM_JTRAX_RESULTS_FOR') . ' ' . htmlspecialchars($this->searchterm, ENT_QUOTES, 'UTF-8'); ?></p>
    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th><?php echo Text::_('COM_JTRAX_RESULTS_DATE'); ?></th>
                <th><?php echo Text::_('COM_JTRAX_RESULTS_STATUS'); ?></th>
                <?php if (!empty($this->showDetails)): ?>
                    <th><?php echo Text::_('COM_JTRAX_RESULTS_STATUS_DETAILS'); ?></th>
                <?php endif; ?>
                <?php if ($params->get('enable_notes', 1)): ?>
                    <th><?php echo Text::_('COM_JTRAX_RESULTS_NOTES'); ?></th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($this->information as $el): ?>
                <tr>
                    <?php
                        $date = explode('-', $el->datetime);
                        $formattedDate = isset($date[2], $date[1], $date[0]) ? $date[2] . '/' . $date[1] . '/' . $date[0] : $el->datetime;
                    ?>
                    <td><?php echo $formattedDate; ?></td>
                    <td><?php echo htmlspecialchars($statusMap[(int) $el->status_id] ?? ($el->details ?? $el->status ?? ''), ENT_QUOTES, 'UTF-8'); ?></td>
                    <?php if (!empty($this->showDetails)): ?>
                        <td><?php echo htmlspecialchars($el->details ?? $el->status ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                    <?php endif; ?>
                    <?php if ($params->get('enable_notes', 1)): ?>
                        <td><?php echo nl2br(htmlspecialchars($el->notes ?? '', ENT_QUOTES, 'UTF-8')); ?></td>
                    <?php endif; ?>
                 </tr>
             <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>