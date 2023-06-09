<?php
/**
* @package RSSeo!
* @copyright (C) 2016 www.rsjoomla.com
* @license GPL, http://www.gnu.org/copyleft/gpl.html
*/
defined('_JEXEC') or die('Restricted access');
JText::script('COM_RSSEO_DELETE');
JText::script('COM_RSSEO_METADATA_TYPE_NAME');
JText::script('COM_RSSEO_METADATA_TYPE_PROPERTY');
JHtml::_('behavior.formvalidator');
JHtml::_('behavior.keepalive'); ?>

<script type="text/javascript">
	Joomla.submitbutton = function(task) {
		if (task == 'page.cancel') {
			Joomla.submitform(task, document.adminForm);
		} else {
			if (document.formvalidator.isValid(document.adminForm)) {
				<?php if ($this->config->crawler_type == 'ajax') { ?>
				jQuery('#toolbar button').prop('disabled', true);
				RSSeo.redirectSave  = '<?php echo JRoute::_('index.php?option=com_rsseo&view=pages', false); ?>';
				RSSeo.redirectApply = '<?php echo JRoute::_('index.php?option=com_rsseo&view=page&layout=edit&id=', false); ?>';
				<?php if ($this->item->id) { ?>
				RSSeo.savePage(task, '<?php echo JUri::root().$this->item->url; ?>', jQuery('#jform_original:checked').length);
				<?php } else { ?>
				RSSeo.savePage(task, '<?php echo JUri::root(); ?>', jQuery('#jform_original:checked').length, true);
				<?php } ?>
				<?php } else { ?>
				Joomla.submitform(task, document.adminForm);
				<?php } ?>
			} else {
				alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED'));?>');
			}
		}
	}
	
	jQuery(document).ready(function() {
		RSSeo.updateSnippet();
		jQuery("#metaDraggable").tableDnD();
	});
</script>

<div id="rsseo-page-loading" style="display: none;">
	<?php echo JHtml::image('com_rsseo/loading.gif', '', array(), true); ?>
</div>
<div id="rsseo-page-overlay" style="display: none;"></div>

<form action="<?php echo JRoute::_('index.php?option=com_rsseo&view=page&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="adminForm" autocomplete="off" class="form-validate form-horizontal">
	<div class="row-fluid">
		<div class="span7">
			<?php $url = rsseoHelper::showURL($this->item->url, $this->item->sef); ?>
			<?php $extra = $this->item->id ? '<a target="_blank" href="'.JURI::root().$this->escape($url).'"><i class="fa fa-external-link"></i></a>' : ''; ?>
			<?php $titleCounter = ' <span id="titleCounter" class="badge badge-secondary">30</span>'; ?>
			<?php $keywordsCounter = ' <span id="keywordsCounter" class="badge badge-secondary">30</span>'; ?>
			<?php $descrCounter = ' <span id="descriptionCounter" class="badge badge-secondary">30</span>'; ?>
			<?php echo JHtml::_('rsfieldset.start', 'adminform'); ?>
			<?php echo JHtml::_('rsfieldset.element', $this->form->getLabel('url'), $this->form->getInput('url').$extra); ?>
			<?php if ($this->config->enable_sef) echo JHtml::_('rsfieldset.element', $this->form->getLabel('sef'), $this->form->getInput('sef')); ?>
			<?php echo JHtml::_('rsfieldset.element', $this->form->getLabel('title'), $this->form->getInput('title'). $titleCounter); ?>
			<?php echo JHtml::_('rsfieldset.element', $this->form->getLabel('keywords'), $this->form->getInput('keywords').$keywordsCounter); ?>
			<?php echo JHtml::_('rsfieldset.element', $this->form->getLabel('keywordsdensity'), $this->form->getInput('keywordsdensity')); ?>
			<?php echo JHtml::_('rsfieldset.element', $this->form->getLabel('description'), $this->form->getInput('description').$descrCounter); ?>
			<?php echo JHtml::_('rsfieldset.element', $this->form->getLabel('canonical'), $this->form->getInput('canonical').'<div class="clr"></div><div id="rss_results"><ul id="rsResultsUl"></ul></div>'); ?>
			<?php echo JHtml::_('rsfieldset.element', $this->form->getLabel('frequency'), $this->form->getInput('frequency')); ?>
			<?php echo JHtml::_('rsfieldset.element', $this->form->getLabel('priority'), $this->form->getInput('priority')); ?>
			<?php echo JHtml::_('rsfieldset.element', $this->form->getLabel('level'), $this->form->getInput('level')); ?>
			<?php echo JHtml::_('rsfieldset.element', $this->form->getLabel('original'), $this->form->getInput('original')); ?>
			<?php echo JHtml::_('rsfieldset.element', $this->form->getLabel('published'), $this->form->getInput('published')); ?>
			<?php if ($this->item->parent) { 
					echo JHtml::_('rsfieldset.element', '<label>'.JText::_('COM_RSSEO_PAGE_CRAWLED_FROM').'</label>', '<a href="'.JURI::root().$this->item->parent.'" target="_blank">'.$this->item->parent.'</a>'); 
				}
			?>
			<?php echo JHtml::_('rsfieldset.end'); ?>
			
			<?php echo JHtml::_('rsfieldset.start', 'adminform', JText::_('COM_RSSEO_PAGE_ROBOTS')); ?>
			<?php foreach($this->form->getGroup('robots') as $field) { ?>
			<?php echo JHtml::_('rsfieldset.element', $field->label, $field->input); ?>
			<?php } ?>
			<?php echo JHtml::_('rsfieldset.end'); ?>
			
			<div class="rsj-block">
				<div class="rsj-head">
					<h5>
						<?php echo JText::_('COM_RSSEO_CUSTOM_METADATA'); ?>
					</h5>
					<div class="rss_broken_check">
						<button type="button" class="btn btn-info button" onclick="RSSeo.addCustomMetadata()"><?php echo JText::_('COM_RSSEO_ADD_NEW'); ?></button>
					</div>
				</div>
				
				<div class="rsj-content">
					<div class="rsj-box">
						<table class="table table-striped" id="metaDraggable">
							<thead>
								<tr>
									<th><?php echo JText::_('COM_RSSEO_METADATA_TYPE'); ?></th>
									<th><?php echo JText::_('COM_RSSEO_METADATA_NAME'); ?></th>
									<th align="right"><?php echo JText::_('COM_RSSEO_METADATA_CONTENT'); ?></th>
									<th width="1%"></th>
								</tr>
							</thead>
							<tbody id="customMeta">
							<?php if (!empty($this->item->custom)) { ?>
							<?php $i = 1; ?>
							<?php foreach ($this->item->custom as $meta) { ?>
							<tr id="meta00<?php echo $i; ?>">
								<td>
									<select name="jform[custom][type][]">
										<?php echo JHtml::_('select.options', $this->get('MetaTypes'), 'value', 'text', $meta['type']);?>
									</select>
								</td>
								<td><input type="text" name="jform[custom][name][]" value="<?php echo $meta['name']; ?>" /></td>
								<td><input type="text" name="jform[custom][content][]" value="<?php echo $meta['content']; ?>" /></td>
								<td><a href="javascript:void(0)" onclick="RSSeo.removeCustomMetadata('00<?php echo $i; ?>');"><?php echo JText::_('COM_RSSEO_DELETE');?></a></td>
							</tr>
							<?php $i++; ?>
							<?php } ?>
							<?php } ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			
			<?php if ($this->item->id && $this->item->crawled) { ?>
			<div class="rsj-block">
				<div class="rsj-head">
					<h5>
						<?php echo JText::_('COM_RSSEO_PAGE_BROKEN_LINKS'); ?>
					</h5>
					<div class="rss_broken_check">
						<?php echo JHtml::image('com_rsseo/loader.gif', '', array('id' => 'loader_links', 'style' => 'display:none;'), true); ?>
						<?php if ($this->config->crawler_type == 'ajax') { ?>
						<button id="brokenButton" type="button" class="btn btn-info button" onclick="RSSeo.broken('<?php echo addslashes(JUri::root().$this->item->url); ?>', <?php echo $this->item->id; ?>)"><?php echo JText::_('COM_RSSEO_CHECK'); ?></button>
						<?php } else { ?>
						<button id="brokenButton" type="button" class="btn btn-info button" onclick="RSSeo.checkBroken(<?php echo $this->item->id; ?>,0)"><?php echo JText::_('COM_RSSEO_CHECK'); ?></button>
						<?php } ?>
					</div>
				</div>
				<div class="rsj-content">
					<div class="rsj-box">
					
						<div class="rsj-progress" style="display: none; width: 100%;" id="brokenProgress">
							<span id="brokenBar" style="width: 0%;" class="green">
								<span id="brokenPercentage">0%</span>
							</span>
						</div>
						
						<div class="clearfix"></div>
					
						<table class="table table-striped">
							<thead>
								<tr>
									<th><?php echo JText::_('COM_RSSEO_PAGE_BROKEN_URL'); ?></th>
									<th align="center" class="center"><?php echo JText::_('COM_RSSEO_PAGE_BROKEN_URL_CODE'); ?></th>
								</tr>
							</thead>
							<tbody id="brokenLinks">
								<?php foreach ($this->broken as $i => $brokenLink) { ?>
								<tr class="row<?php echo $i % 2; ?>">
									<td><?php echo $brokenLink->url; ?></td>
									<td align="center" class="center"><b><?php echo $brokenLink->code; ?></b> <?php echo rsseoHelper::getResponseMessage($brokenLink->code); ?></td>
								</tr>
								<?php } ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<?php } ?>
			
		</div>
		
		<?php if ($this->item->id && $this->item->crawled) { ?>
		<div class="span5">
			<div class="rsseo-snippet-container hasTooltip" title="<?php echo JText::_('COM_RSSEO_PAGE_SNIPPET_INFO'); ?>">
				<div class="rsseo-snippet-title"><a href="<?php echo JUri::root().$this->escape($url); ?>" target="_blank"><?php echo $this->item->title; ?></a></div>
				<div class="rsseo-snippet-url"><?php echo JUri::root().$url; ?></div>
				<div class="rsseo-snippet-description"><?php echo $this->item->description; ?></div>
			</div>
			
			<div class="rsj-block">
				<div class="rsj-head">
					<h5>
						<?php echo JText::_('COM_RSSEO_PAGE_SEO_GRADE'); ?> 
						<?php $grade = ($this->item->grade <= 0) ? 0 : ceil($this->item->grade); ?>
					</h5>
					<div class="rsj-progress">
						<span class="<?php echo $this->item->color; ?>" style="width: <?php echo $grade; ?>%;">
							<span><?php echo $grade; ?>%</span>
						</span>
					</div>
				</div>
				<div class="rsj-content">
					<div class="rsj-box">
						<table class="table table-striped">
							<tbody>
								<?php if ($this->config->crawler_sef && isset($this->item->params['url_sef'])) { ?>
								<tr>
									<td colspan="2">
										<strong><?php echo JText::_('COM_RSSEO_CHECK_FOR_SEF'); ?></strong>
									</td>
								</tr>
								<tr>
									<td style="vertical-align:middle;" width="6%">
										<a href="https://www.rsjoomla.com/index.php?option=com_rsfirewall_kb&task=redirect&code=SEO_SEFCHECK" target="_blank">
											<?php echo JHtml::image('com_rsseo/help.png', '', array(), true); ?>
										</a>
										<?php $url_sef = $this->item->params['url_sef'] == 1; ?>
										<?php echo JHtml::image('com_rsseo/'.($url_sef ? 'ok' : 'notok').'.png', '', array(), true); ?>
									</td>
									<td>
										<?php echo $url_sef ? JText::_('COM_RSSEO_CHECKPAGE_URL_SEF_YES') : JText::_('COM_RSSEO_CHECKPAGE_URL_SEF_NO'); ?>
									</td>
								</tr>
								<?php } ?>
								
								<?php if ($this->config->crawler_title_duplicate && isset($this->item->params['duplicate_title'])) { ?>
								<tr>
									<td colspan="2">
										<strong><?php echo JText::_('COM_RSSEO_CHECK_FOR_DUPLICATE_PAGE_TITLES'); ?></strong>
									</td>
								</tr>
								<tr>
									<td style="vertical-align:middle;" width="6%">
										<a href="https://www.rsjoomla.com/index.php?option=com_rsfirewall_kb&task=redirect&code=SEO_TITLE_DUPLICATE" target="_blank">
											<?php echo JHtml::image('com_rsseo/help.png', '', array(), true); ?>
										</a>
										<?php $duplicate_title = $this->item->params['duplicate_title'] > 1; ?>
										<?php echo JHtml::image('com_rsseo/'.(!$duplicate_title ? 'ok' : 'notok').'.png', '', array(), true); ?>
									</td>
									<td>
										<?php if ($duplicate_title) { ?>
										<?php echo JText::sprintf('COM_RSSEO_CHECKPAGE_METATITLE_DUPLICATE_YES', ($this->item->params['duplicate_title'] - 1)); ?>
										<a href="<?php echo JRoute::_('index.php?option=com_rsseo&view=pages&hash=title|'.md5($this->item->title), false); ?>" target="_blank"><?php echo JText::_('COM_RSSEO_CHECKPAGE_METATITLE_DUPLICATE_YES_VIEW'); ?></a>
										<?php } else { ?>
										<?php echo JText::_('COM_RSSEO_CHECKPAGE_METATITLE_DUPLICATE_NO') ?>
										<?php } ?>
									</td>
								</tr>
								<?php } ?>
								
								<?php if ($this->config->crawler_title_length && isset($this->item->params['title_length'])) { ?>
								<tr>
									<td colspan="2">
										<strong><?php echo JText::_('COM_RSSEO_CHECK_FOR_PAGE_TITLE_LENGTH'); ?></strong>
									</td>
								</tr>
								<tr>
									<td style="vertical-align:middle;" width="6%">
										<a href="https://www.rsjoomla.com/index.php?option=com_rsfirewall_kb&task=redirect&code=SEO_TITLE_LENGTH" target="_blank">
											<?php echo JHtml::image('com_rsseo/help.png', '', array(), true); ?>
										</a>
										<?php $tlength = $this->item->params['title_length']; ?>
										<?php $titlelength = ($tlength == 0 || $tlength > 70 || $tlength < 10); ?>
										<?php echo JHtml::image('com_rsseo/'.(!$titlelength ? 'ok' : 'notok').'.png', '', array(), true); ?>
									</td>
									<td>
										<?php 
											if ($tlength == 0) 
												echo JText::_('COM_RSSEO_CHECKPAGE_METATITLE_LENGTH_0');
											else if ($tlength < 10)
												echo JText::sprintf('COM_RSSEO_CHECKPAGE_METATITLE_LENGTH_SHORT',$tlength);
											else if ($tlength > 70)
												echo JText::sprintf('COM_RSSEO_CHECKPAGE_METATITLE_LENGTH_LONG',$tlength);
											else echo JText::sprintf('COM_RSSEO_CHECKPAGE_METATITLE_LENGTH_OK',$tlength);
										?>
									</td>
								</tr>
								<?php } ?>
								
								<?php if ($this->config->crawler_description_duplicate && isset($this->item->params['duplicate_desc'])) { ?>
								<tr>
									<td colspan="2">
										<strong><?php echo JText::_('COM_RSSEO_CHECK_FOR_DUPLICATE_PAGE_DESCRIPTION'); ?></strong>
									</td>
								</tr>
								<tr>
									<td style="vertical-align:middle;" width="6%">
										<a href="https://www.rsjoomla.com/index.php?option=com_rsfirewall_kb&task=redirect&code=SEO_DESCRIPTION_DUPLICATE" target="_blank">
											<?php echo JHtml::image('com_rsseo/help.png', '', array(), true); ?>
										</a>
										<?php $duplicate_desc = $this->item->params['duplicate_desc'] > 1; ?>
										<?php echo JHtml::image('com_rsseo/'.(!$duplicate_desc ? 'ok' : 'notok').'.png', '', array(), true); ?>
									</td>
									<td>
										<?php if ($duplicate_desc) { ?>
										<?php echo JText::sprintf('COM_RSSEO_CHECKPAGE_METADESC_DUPLICATE_YES', ($this->item->params['duplicate_desc'] - 1)); ?>
										<a href="<?php echo JRoute::_('index.php?option=com_rsseo&view=pages&hash=description|'.md5($this->item->description), false); ?>" target="_blank"><?php echo JText::_('COM_RSSEO_CHECKPAGE_METADESC_DUPLICATE_YES_VIEW'); ?></a>
										<?php } else { ?>
										<?php echo JText::_('COM_RSSEO_CHECKPAGE_METADESC_DUPLICATE_NO') ?>
										<?php } ?>
									</td>
								</tr>
								<?php } ?>
								
								<?php if ($this->config->crawler_description_length && isset($this->item->params['description_length'])) { ?>
								<tr>
									<td colspan="2">
										<strong><?php echo JText::_('COM_RSSEO_CHECK_FOR_PAGE_DESCRIPTION_LENGTH'); ?></strong>
									</td>
								</tr>
								<tr>
									<td style="vertical-align:middle;" width="6%">
										<a href="https://www.rsjoomla.com/index.php?option=com_rsfirewall_kb&task=redirect&code=SEO_DESCRIPTION_LENGTH" target="_blank">
											<?php echo JHtml::image('com_rsseo/help.png', '', array(), true); ?>
										</a>
										<?php $dlength = $this->item->params['description_length']; ?>
										<?php $descrlength = ($dlength == 0 || $dlength > 300 || $dlength < 70); ?>
										<?php echo JHtml::image('com_rsseo/'.(!$descrlength ? 'ok' : 'notok').'.png', '', array(), true); ?>
									</td>
									<td>
										<?php 
											if ($dlength == 0) 
												echo JText::_('COM_RSSEO_CHECKPAGE_METADESC_LENGTH_0');
											else if ($dlength < 70)
												echo JText::sprintf('COM_RSSEO_CHECKPAGE_METADESC_LENGTH_SHORT',$dlength);
											else if ($dlength > 300)
												echo JText::sprintf('COM_RSSEO_CHECKPAGE_METADESC_LENGTH_LONG',$dlength);
											else echo JText::sprintf('COM_RSSEO_CHECKPAGE_METADESC_LENGTH_OK',$dlength);
										?>
									</td>
								</tr>
								<?php } ?>
								
								<?php if ($this->config->crawler_keywords && isset($this->item->params['keywords'])) { ?>
								<tr>
									<td colspan="2">
										<strong><?php echo JText::_('COM_RSSEO_CHECK_FOR_PAGE_KEYWORDS'); ?></strong>
									</td>
								</tr>
								<tr>
									<td style="vertical-align:middle;" width="6%">
										<a href="https://www.rsjoomla.com/index.php?option=com_rsfirewall_kb&task=redirect&code=SEO_KEYWORD_COUNT" target="_blank">
											<?php echo JHtml::image('com_rsseo/help.png', '', array(), true); ?>
										</a>
										<?php $keywordsnr = $this->item->params['keywords']; ?>
										<?php $keywords = $keywordsnr > 10; ?>
										<?php echo JHtml::image('com_rsseo/'.(!$keywords ? 'ok' : 'notok').'.png', '', array(), true); ?>
									</td>
									<td>
										<?php 
											if ($keywordsnr == 0)
												echo JText::_('COM_RSSEO_CHECKPAGE_METAKEYWORDS_0');
											else if ($keywordsnr < 10)
												echo JText::sprintf('COM_RSSEO_CHECKPAGE_METAKEYWORDS_SMALL', $keywordsnr);
											else if ($keywordsnr > 10)
												echo JText::sprintf('COM_RSSEO_CHECKPAGE_METAKEYWORDS_BIG', $keywordsnr);
											else echo JText::_('COM_RSSEO_CHECKPAGE_METAKEYWORDS_OK');
										?>
									</td>
								</tr>
								<?php } ?>
								
								<?php if ($this->config->crawler_headings && isset($this->item->params['headings'])) { ?>
								<tr>
									<td colspan="2">
										<strong><?php echo JText::_('COM_RSSEO_CHECK_FOR_PAGE_HEADINGS'); ?></strong>
									</td>
								</tr>
								<tr>
									<td style="vertical-align:middle;" width="6%">
										<a href="https://www.rsjoomla.com/index.php?option=com_rsfirewall_kb&task=redirect&code=SEO_HEADINGS" target="_blank">
											<?php echo JHtml::image('com_rsseo/help.png', '', array(), true); ?>
										</a>
										<?php $headings = $this->item->params['headings'] <= 0; ?>
										<?php echo JHtml::image('com_rsseo/'.(!$headings ? 'ok' : 'notok').'.png', '', array(), true); ?>
									</td>
									<td>
										<?php echo $headings ? JText::_('COM_RSSEO_CHECKPAGE_HEADINGS_ERROR') : JText::sprintf('COM_RSSEO_CHECKPAGE_HEADINGS_OK',$this->item->params['headings']); ?>
									</td>
								</tr>
								<?php } ?>
								
								<?php if ($this->config->crawler_intext_links && isset($this->item->params['links'])) { ?>
								<tr>
									<td colspan="2">
										<strong><?php echo JText::_('COM_RSSEO_CHECK_FOR_PAGE_IE_LINKS'); ?></strong>
									</td>
								</tr>
								<tr>
									<td style="vertical-align:middle;" width="6%">
										<a href="https://www.rsjoomla.com/index.php?option=com_rsfirewall_kb&task=redirect&code=SEO_IELINKS" target="_blank">
											<?php echo JHtml::image('com_rsseo/help.png', '', array(), true); ?>
										</a>
										<?php $ielinks = $this->item->params['links'] > 100; ?>
										<?php echo JHtml::image('com_rsseo/'.(!$ielinks ? 'ok' : 'notok').'.png', '', array(), true); ?>
									</td>
									<td>
										<?php echo $ielinks ? JText::_('COM_RSSEO_CHECKPAGE_IE_LINKS_ERROR') : JText::_('COM_RSSEO_CHECKPAGE_IE_LINKS_OK'); ?>
									</td>
								</tr>
								<?php } ?>
								
								<?php if ($this->config->crawler_images && isset($this->item->params['images'])) { ?>
								<tr>
									<td colspan="2">
										<strong><?php echo JText::_('COM_RSSEO_CHECK_FOR_PAGE_IMAGES'); ?></strong>
									</td>
								</tr>
								<tr>
									<td style="vertical-align:middle;" width="6%">
										<a href="https://www.rsjoomla.com/index.php?option=com_rsfirewall_kb&task=redirect&code=SEO_IMG" target="_blank">
											<?php echo JHtml::image('com_rsseo/help.png', '', array(), true); ?>
										</a>
										<?php $images = $this->item->params['images'] > 10; ?>
										<?php echo JHtml::image('com_rsseo/'.(!$images ? 'ok' : 'notok').'.png', '', array(), true); ?>
									</td>
									<td>
										<?php echo $images ? JText::sprintf('COM_RSSEO_CHECKPAGE_IMAGES_ERROR',$this->item->params['images']) : JText::sprintf('COM_RSSEO_CHECKPAGE_IMAGES_OK',$this->item->params['images']); ?>
									</td>
								</tr>
								<?php } ?>
								
								<?php if ($this->config->crawler_images_alt && isset($this->item->params['images_wo_alt'])) { ?>
								<tr>
									<td colspan="2">
										<strong><?php echo JText::_('COM_RSSEO_CHECK_FOR_PAGE_IMAGES_W_ALT'); ?></strong>
									</td>
								</tr>
								<tr>
									<td style="vertical-align:middle;" width="6%">
										<a href="https://www.rsjoomla.com/index.php?option=com_rsfirewall_kb&task=redirect&code=SEO_IMG_ALT" target="_blank">
											<?php echo JHtml::image('com_rsseo/help.png', '', array(), true); ?>
										</a>
										<?php $images_alt = $this->item->params['images_wo_alt'] > 0; ?>
										<?php echo JHtml::image('com_rsseo/'.(!$images_alt ? 'ok' : 'notok').'.png', '', array(), true); ?>
									</td>
									<td>
										<?php echo $images_alt ? JText::sprintf('COM_RSSEO_CHECKPAGE_IMAGES_WO_ALT_ERROR',$this->item->params['images_wo_alt']) : JText::_('COM_RSSEO_CHECKPAGE_IMAGES_WO_ALT_OK'); ?>
									</td>
								</tr>
								<?php } ?>
								
								<?php if ($this->config->crawler_images_hw && isset($this->item->params['images_wo_hw'])) { ?>
								<tr>
									<td colspan="2">
										<strong><?php echo JText::_('COM_RSSEO_CHECK_FOR_PAGE_IMAGES_W_HW'); ?></strong>
									</td>
								</tr>
								<tr>
									<td style="vertical-align:middle;" width="6%">
										<a href="https://www.rsjoomla.com/index.php?option=com_rsfirewall_kb&task=redirect&code=SEO_IMG_RESIZE" target="_blank">
											<?php echo JHtml::image('com_rsseo/help.png', '', array(), true); ?>
										</a>
										<?php $images_hw = $this->item->params['images_wo_hw'] > 0; ?>
										<?php echo JHtml::image('com_rsseo/'.(!$images_hw ? 'ok' : 'notok').'.png', '', array(), true); ?>
									</td>
									<td>
										<?php echo $images_hw ? JText::sprintf('COM_RSSEO_CHECKPAGE_IMAGES_WO_HW_ERROR',$this->item->params['images_wo_hw']) : JText::_('COM_RSSEO_CHECKPAGE_IMAGES_WO_HW_OK'); ?>
									</td>
								</tr>
								<?php } ?>
								
								<tr>
									<td style="vertical-align:middle;" width="6%">
										<a href="https://www.rsjoomla.com/index.php?option=com_rsfirewall_kb&task=redirect&code=SEO_IMG_NAMES" target="_blank">
											<?php echo JHtml::image('com_rsseo/help.png', '', array(), true); ?>
										</a>
									</td>
									<td>
										<?php echo JText::_('COM_RSSEO_CHECKPAGE_IMAGES_NAMES_DESC'); ?>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			
			<div class="rsj-block">
				<div class="rsj-head">
					<h5>
						<a href="<?php echo JRoute::_('index.php?option=com_rsseo&view=page&layout=links&id='.$this->item->id,false); ?>"><?php echo JText::_('COM_RSSEO_PAGE_INT_EXT_LINKS'); ?></a>
					</h5>
				</div>
				<div class="rsj-content">
					<div class="rsj-box">
						<table class="table table-striped">
							<tr>
								<td><?php echo JText::_('COM_RSSEO_PAGE_INT_LINKS'); ?></td>
								<td align="center" class="center"><?php echo $this->item->internal; ?></td>
							</tr>
							<tr>
								<td><?php echo JText::_('COM_RSSEO_PAGE_EXT_LINKS'); ?></td>
								<td align="center" class="center"><?php echo $this->item->external; ?></td>
							</tr>
						</table>
					</div>
				</div>
			</div>
			
			<?php if ($this->config->crawler_images_alt && !empty($this->item->imagesnoalt)) { ?>
			<div class="rsj-block">
				<div class="rsj-head">
					<h5>
						<?php echo JText::_('COM_RSSEO_PAGE_IMAGES_WITHOUT_ALT'); ?> 
					</h5>
				</div>
				<div class="rsj-content">
					<div class="rsj-box">
						<table class="table table-striped">
							<?php foreach ($this->item->imagesnoalt as $image) { ?>
							<tr>
								<td><?php echo $image; ?></td>
							</tr>
							<?php } ?>
						</table>
					</div>
				</div>
			</div>
			<?php } ?>
			
			<?php if ($this->config->crawler_images_hw && !empty($this->item->imagesnowh)) { ?>
			<div class="rsj-block">
				<div class="rsj-head">
					<h5>
						<?php echo JText::_('COM_RSSEO_PAGE_IMAGES_WITHOUT_WH'); ?> 
					</h5>
				</div>
				<div class="rsj-content">
					<div class="rsj-box">
						<table class="table table-striped">
							<?php foreach ($this->item->imagesnowh as $image) { ?>
							<tr>
								<td><?php echo $image; ?></td>
							</tr>
							<?php } ?>
						</table>
					</div>
				</div>
			</div>
			<?php } ?>
			
			<?php if ($this->config->keyword_density_enable) { ?>
			<div class="rsj-block">
				<div class="rsj-head">
					<h5>
						<?php echo JText::_('COM_RSSEO_PAGE_KEYWORD_DENSITY'); ?> 
					</h5>
				</div>
				<div class="rsj-content">
					<div class="rsj-box">
						<?php if (!empty($this->item->densityparams)) { ?>
						<table class="table table-striped">
							<?php foreach ($this->item->densityparams as $keyword => $value) { ?>
							<tr>
								<td style="vertical-align:middle;" width="6%">
									<a href="https://www.rsjoomla.com/index.php?option=com_rsfirewall_kb&task=redirect&code=SEO_DENSITY" target="_blank">
										<?php echo JHtml::image('com_rsseo/help.png', '', array(), true); ?>
									</a>
								</td>
								<td><?php echo $keyword; ?></td>
								<td><?php echo $value; ?></td>
							</tr>
							<?php } ?>
						</table>
						<?php } ?>
					</div>
				</div>
			</div>
			<?php } ?>
			
			<?php if ($this->item->id) { ?>
			<div class="rsj-block">
				<div class="rsj-head">
					<h5>
						<a href="javascript:void(0)" onclick="RSSeo.pageLoadingTime(<?php echo $this->item->id; ?>);">
							<?php echo JText::_('COM_RSSEO_PAGE_CHECK_LOAD_SIZE'); ?> 
						</a>
						<?php echo JHtml::image('com_rsseo/loader.gif', '', array('id' => 'loader', 'style' => 'display:none;vertical-align:bottom;'), true); ?>
					</h5>
				</div>
				<div class="rsj-content">
					<div class="rsj-box">
						<table class="table table-striped">
							<tbody>
								<tr id="pageloadtr" style="display:none;">
									<td style="vertical-align:middle;" width="6%">
										<a href="https://www.rsjoomla.com/index.php?option=com_rsfirewall_kb&task=redirect&code=SEO_PAGELOAD" target="_blank">
											<?php echo JHtml::image('com_rsseo/help.png', '', array(), true); ?>
										</a>
									</td>
									<td><?php echo JText::_('COM_RSSEO_CHECKPAGE_TOTAL_PAGE_DESCR'); ?></td>
									<td><span id="pageload"></span></td>
								</tr>
								<tr id="pagesizetr" style="display:none;">
									<td style="vertical-align:middle;" width="6%">
										<a href="https://www.rsjoomla.com/index.php?option=com_rsfirewall_kb&task=redirect&code=SEO_PAGESIZE" target="_blank">
											<?php echo JHtml::image('com_rsseo/help.png', '', array(), true); ?>
										</a>
									</td>
									<td><?php echo JText::_('COM_RSSEO_CHECKPAGE_PAGE_SIZE'); ?></td>
									<td><span id="pagesize"></span></td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<?php } ?>
		</div>
		<?php } ?>
	</div>

	<?php echo JHTML::_('form.token'); ?>
	<?php echo $this->form->getInput('id')."\n"; ?>
	<input type="hidden" name="id" value="<?php echo $this->form->getValue('id'); ?>" />
	<input type="hidden" name="task" value="" />
</form>

<script type="text/javascript">
RSSeo.titleLength = <?php echo (int) $this->config->title_length; ?>;
RSSeo.keywordsLength = <?php echo (int) $this->config->keywords_length; ?>;
RSSeo.descriptionLength = <?php echo (int) $this->config->description_length; ?>;

jQuery(document).ready(function () {
	jQuery('#jform_title, #jform_keywords, #jform_description').each(function() {
		RSSeo.counters(jQuery(this));
		jQuery(this).on('keyup', function() {
			RSSeo.counters(jQuery(this));
		})
	});
});
jQuery('#jform_canonical').on('keyup', function() {
	RSSeo.generateRSResults(0);
});
</script>