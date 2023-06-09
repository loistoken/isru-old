<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_users
 *
 * @copyright   Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/*
JHtml::_('behavior.keepalive');
JHtml::_('behavior.formvalidator');
*/

?>
<div class="reset <?php echo $this->pageclass_sfx; ?>">
    <div class="uk-flex-center" uk-grid>
        <div class="uk-width-1-1 uk-width-1-3@l">
            <div>
                <h3 class="font uk-text-center uk-text-left@l">Forgot Password</h3>
                <form id="user-registration" action="<?php echo JRoute::_('index.php?option=com_users&task=reset.request'); ?>" method="post" class="form-validate form-horizontal well">
                    <div class="uk-child-width-1-1 uk-grid-small" uk-grid>
                        <?php foreach ($this->form->getFieldsets() as $fieldset) : ?>
                        <div><p class="font uk-text-tiny uk-text-center uk-text-left@l"><?php echo JText::_($fieldset->label); ?></p></div>
                            <?php foreach ($this->form->getFieldset($fieldset->name) as $name => $field) : ?>
                                <?php if ($field->hidden === false) : ?>
                                    <div class="controls"><?php echo $field->input; ?></div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                        <div><button type="submit" class="btn btn-primary validate uk-button uk-button-secondary uk-border-rounded uk-width-1-1 uk-width-auto@l"><?php echo JText::_('JSUBMIT'); ?></button></div>
                    </div>
                    <?php echo JHtml::_('form.token'); ?>
                </form>
            </div>
        </div>
    </div>
</div>