<?php
/**
 * @copyright 2007-2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param Zend_Paginator $this->paginator
 * @param URL $this->url
 */
if (count($this->paginator)) {
	$url = $this->url;

	echo '<ul class="pageNavigation">';

	// Show the Back button
	if (isset($this->paginator->previous)) {
		$url->page = $this->paginator->first;
		echo "<li><a href=\"$url\" class=\"first\">First</a></li>";

		$url->page = $this->paginator->previous;
		echo "<li><a href=\"$url\" class=\"previous\">Back</a></li>";
	}
	// Show the page number links
	// Show only $maxNumLinks pages at a time
	foreach ($this->paginator->pagesInRange as $page) {
		$url->page = $page;
		$class = ($page == $this->paginator->current) ? 'class="current"' : '';
		echo "<li><a href=\"$url\" $class>$page</a></li>";
	}

	// Show the Next button
	if (isset($this->paginator->next)) {
		$url->page = $this->paginator->next;
		echo "<li><a href=\"$url\" class=\"next\">Next</a></li>";

		$url->page = $this->paginator->last;
		echo "<li><a href=\"$url\" class=\"last\">Last</a></li>";
	}

	echo '</ul>';
}
