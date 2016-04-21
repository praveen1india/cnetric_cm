<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Wcs_pim
 * @author     Praveen <mantu.mnt@gmail.com>
 * @copyright  2016 demo
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');

// Import CSS
$document = JFactory::getDocument();
$document->addStyleSheet(JUri::root() . 'administrator/components/com_wcs_pim/assets/css/wcs_pim.css');
$document->addStyleSheet(JUri::root() . 'media/com_wcs_pim/css/list.css');

$user      = JFactory::getUser();
$userId    = $user->get('id');


?>
<script type="text/javascript">
	Joomla.orderTable = function () {
		table = document.getElementById("sortTable");
		direction = document.getElementById("directionTable");
		order = table.options[table.selectedIndex].value;
		if (order != '<?php echo $listOrder; ?>') {
			dirn = 'asc';
		} else {
			dirn = direction.options[direction.selectedIndex].value;
		}
		Joomla.tableOrdering(order, dirn, '');
	};

	jQuery(document).ready(function () {
		jQuery('#clear-search-button').on('click', function () {
			jQuery('#filter_search').val('');
			jQuery('#adminForm').submit();
		});
	});
</script>

<?php

// Joomla Component Creator code to allow adding non select list filters
if (!empty($this->extra_sidebar))
{
	$this->sidebar .= $this->extra_sidebar;
}

?>

<form action="<?php echo JRoute::_('index.php?option=com_wcs_pim&view=categories'); ?>" method="post"
	  name="adminForm" id="adminForm">
	<?php if (!empty($this->sidebar)): ?>
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span10">
		<?php else : ?>
		<div id="j-main-container">
			<?php endif; ?>
			<?php
			$model = JModelLegacy::getInstance('Extractor', 'Wcs_pimModel');
						$items= $model->getItems();
						/*echo "<pre>";
						print_r($items);*/
			 ?>
			<div id="filter-bar" class="btn-toolbar">

				<?php
				/* XML code*/

		   				$writer = new XMLWriter();
						$writer->openURI('testS.xml');
						$writer->startDocument("1.0");
							foreach ($items as $row)
							{
							    $Rows[]	=$row->fname;
								$writer->startElement("cat_title");
								$writer->text( $row->cat_title );
								$writer->endElement()."\n";

								$writer->startElement("lang");
								$writer->text( $row->lang );
								$writer->endElement()."\n";

								$writer->startElement("keyword");
								$writer->text( $row->keyword );
								$writer->endElement()."\n";
							}
						$writer->endDocument();
						$writer->flush();
						return $Rows;

					/*XML end*/
					 ?>
					 <?php

					$doc = new DOMDocument('1.0');
					// we want a nice output
					$doc->formatOutput = true;

					$root = $doc->createElement('book');
					$root = $doc->appendChild($root);

					$title = $doc->createElement('title');
					$title = $root->appendChild($title);

					$text = $doc->createTextNode('This is the title');
					$text = $title->appendChild($text);

					echo "Saving all the document:\n";
					echo $doc->saveXML() . "\n";

					echo "Saving only the title part:\n";
					echo $doc->saveXML($title);
					?>

			</div>
			<div class="clearfix"></div>

			<input type="hidden" name="task" value=""/>
			<input type="hidden" name="boxchecked" value="0"/>
			<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>"/>
			<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>"/>
			<?php echo JHtml::_('form.token'); ?>
		</div>
</form>
