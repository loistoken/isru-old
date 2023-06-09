<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_users
 *
 * @copyright   Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
JHtml::register('users.spacer', array('JHtmlUsers', 'spacer'));

$fieldsets = $this->form->getFieldsets();

if (isset($fieldsets['core']))
{
	unset($fieldsets['core']);
}

if (isset($fieldsets['params']))
{
	unset($fieldsets['params']);
}

$tmp          = isset($this->data->jcfields) ? $this->data->jcfields : array();
$customFields = array();

foreach ($tmp as $customField)
{
	$customFields[$customField->name] = $customField;
}

$browserbar = $customFields['first-name']->value.' '.$customFields['last-name']->value;
$document = JFactory::getDocument();
$document->setTitle($browserbar);
?>
    <div uk-accordion>
        <div class="uk-open">
            <a class="uk-accordion-title font uk-accordion-title uk-border-rounded uk-card uk-card-default uk-padding-small uk-text-small uk-text-black" href="#">Student Information</a>
            <div class="uk-accordion-content">
                <table class="uk-table uk-table-striped uk-table-divider uk-table-hover uk-table-small uk-table-middle uk-table-responsive profileTables uk-margin-remove">
                    <thead>
                    <tr>
                        <th class="uk-text-12 uk-text-capitalize uk-text-center font uk-table-auto">Father's Name</th>
                        <th class="uk-text-12 uk-text-capitalize uk-text-center font uk-table-auto">Nationality</th>
                        <th class="uk-text-12 uk-text-capitalize uk-text-center font uk-table-auto">Passport #</th>
                        <th class="uk-text-12 uk-text-capitalize uk-text-center font uk-table-auto">Program</th>
                        <th class="uk-text-12 uk-text-capitalize uk-text-center font uk-table-auto">Apply Date</th>
                        <th class="uk-text-12 uk-text-capitalize uk-text-center font uk-table-auto">Accept Date</th>
                        <th class="uk-text-12 uk-text-capitalize uk-text-center font uk-table-auto">Passport #</th>
                        <th class="uk-text-12 uk-text-capitalize uk-text-center font uk-table-auto">Transfer</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td class="uk-text-center font uk-text-small uk-text-black"><?php echo $customFields['father-s-name']->value; ?></td>
                        <td class="uk-text-center font uk-text-small uk-text-black"><?php echo $customFields['nationality']->value; ?></td>
                        <td class="uk-text-center font uk-text-small uk-text-black"><?php echo $customFields['passport-number']->value; ?></td>
                        <td class="uk-text-center font uk-text-small uk-text-black"><?php echo $customFields['program']->value; ?></td>
                        <td class="uk-text-center font uk-text-small uk-text-black"><?php echo $customFields['apply-date']->value; ?></td>
                        <td class="uk-text-center font uk-text-small uk-text-black"><?php echo $customFields['accept-date']->value; ?></td>
                        <td class="uk-text-center font uk-text-small uk-text-black"><?php echo $customFields['passport-number']->value; ?></td>
                        <td class="uk-text-center font uk-text-small uk-text-black">
                            <span class="uk-text-seccess"><?php echo $customFields['transfer-status']->value ?></span><?php echo $customFields['transfer-destination']->value == '' ? '' : ' - '.$customFields['transfer-destination']->value; ?>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <?php $debt = $customFields['total-amount']->value - $customFields['1st-payment']->value - $customFields['2nd-payment']->value - $customFields['3rd-payment']->value; ?>
        <div>
            <a class="uk-accordion-title font uk-accordion-title uk-border-rounded uk-card uk-card-default uk-padding-small uk-text-small uk-text-black" href="#">Payment Information</a>
            <div class="uk-accordion-content">
                <table class="uk-table uk-table-striped uk-table-divider uk-table-hover uk-table-small uk-table-middle uk-table-responsive profileTables uk-margin-remove">
                    <thead>
                    <tr>
                        <th class="uk-text-12 uk-text-capitalize uk-text-center font uk-width-1-6">Total Amount</th>
                        <th class="uk-text-12 uk-text-capitalize uk-text-center font uk-width-1-6">Status</th>
                        <th class="uk-text-12 uk-text-capitalize uk-text-center font uk-width-1-6">1st Payment</th>
                        <th class="uk-text-12 uk-text-capitalize uk-text-center font uk-width-1-6">2nd Payment</th>
                        <th class="uk-text-12 uk-text-capitalize uk-text-center font uk-width-1-6">3rd Payment</th>
                        <th class="uk-text-12 uk-text-capitalize uk-text-center font uk-width-1-6">Debt</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td class="uk-text-center font uk-text-small uk-text-black"><?php echo '€'.$customFields['total-amount']->value; ?></td>
                        <td class="uk-text-center font uk-text-small uk-text-black uk-text-<?php echo $debt > 0 ? 'danger' : 'success'; ?>"><?php echo $customFields['billing-status']->value; ?></td>
                        <td class="uk-text-center font uk-text-small uk-text-black"><?php echo $customFields['1st-payment']->value != '' ? '€'.$customFields['1st-payment']->value : '---'; ?></td>
                        <td class="uk-text-center font uk-text-small uk-text-black"><?php echo $customFields['2nd-payment']->value != '' ? '€'.$customFields['2nd-payment']->value : '---'; ?></td>
                        <td class="uk-text-center font uk-text-small uk-text-black"><?php echo $customFields['3rd-payment']->value != '' ? '€'.$customFields['3rd-payment']->value : '---'; ?></td>
                        <td class="uk-text-center font uk-text-small uk-text-black"><?php echo '€'.$debt; ?></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <?php if ($customFields['message']->value != '') { ?>
        <div>
            <a class="uk-accordion-title font uk-accordion-title uk-border-rounded uk-card uk-card-default uk-padding-small uk-text-small uk-text-black" href="#">Message</a>
            <div class="uk-accordion-content">
                <div class="uk-text-justify uk-text-12 small"><?php echo $customFields['message']->value; ?></div>
            </div>
        </div>
        <?php } ?>
        <?php if ($customFields['records']->value != '') { ?>
        <div>
            <a class="uk-accordion-title font uk-accordion-title uk-border-rounded uk-card uk-card-default uk-padding-small uk-text-small uk-text-black" href="#">Previous Records</a>
            <div class="uk-accordion-content">
                <div class="uk-text-justify uk-text-12 small"><?php echo $customFields['records']->value; ?></div>
            </div>
        </div>
        <?php } ?>
    </div>








<?php /* foreach ($fieldsets as $group => $fieldset) : ?>
	<?php $fields = $this->form->getFieldset($group); ?>
	<?php if (count($fields)) : ?>
        <?php if (isset($fieldset->label) && ($legend = trim(JText::_($fieldset->label))) !== '') : ?>
            <legend><?php echo $legend; ?></legend>
        <?php endif; ?>
        <?php if (isset($fieldset->description) && trim($fieldset->description)) : ?>
            <p><?php echo $this->escape(JText::_($fieldset->description)); ?></p>
        <?php endif; ?>
        <div class="uk-child-width-1-1 uk-child-width-1-2@m" uk-grid>
            <?php foreach ($fields as $field) : ?>
                <?php if (!$field->hidden && $field->type !== 'Spacer') : ?>
                    <div class="font uk-text-small uk-text-black">
                        <?php echo '<span class="uk-text-muted">'.$field->title.'</span>'; ?>
                        <?php if (key_exists($field->fieldname, $customFields)) : ?>
                            <?php echo strlen($customFields[$field->fieldname]->value) ? $customFields[$field->fieldname]->value : JText::_('COM_USERS_PROFILE_VALUE_NOT_FOUND'); ?>
                        <?php elseif (JHtml::isRegistered('users.' . $field->id)) : ?>
                            <?php echo JHtml::_('users.' . $field->id, $field->value); ?>
                        <?php elseif (JHtml::isRegistered('users.' . $field->fieldname)) : ?>
                            <?php echo JHtml::_('users.' . $field->fieldname, $field->value); ?>
                        <?php elseif (JHtml::isRegistered('users.' . $field->type)) : ?>
                            <?php echo JHtml::_('users.' . $field->type, $field->value); ?>
                        <?php else : ?>
                            <?php echo JHtml::_('users.value', $field->value); ?>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
	<?php endif; ?>
<?php endforeach; */ ?>