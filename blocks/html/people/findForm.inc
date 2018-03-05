<?php
/**
 * @copyright 2017-2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @param string $this->firstname
 * @param string $this->lastname
 * @param string $this->email
 * @param Url    $this->callback_url   Url to link person results to
 * @param string $this->callback_field Parameter to add to the callback_url
 * @param array  $this->hidden         Hidden inputs to preserve
 * @param array  $this->people         Array of people search results
 */
declare (strict_types=1);
?>
<section>
    <header>
        <h1><?= $this->_('people_search'); ?></h1>
    </header>
    <form method="get">
        <fieldset>
        <?php
            $helper = $this->template->getHelper('field');
            $fields = ['firstname', 'lastname', 'email'];
            foreach ($fields as $f) {
                echo $helper->field([
                    'name'  => $f,
                    'id'    => $f,
                    'label' => $this->_($f),
                    'value' => $this->$f
                ]);
            }

            foreach ($this->hidden as $k=>$v) {
                echo "<input type=\"hidden\" name=\"$k\" value=\"$v\" />";
            }
        ?>
        <button type="submit" class="search"><?= $this->_('search'); ?></button>
        </fieldset>
    </form>
    <?php
        if ($this->people) { $this->_include('people/list.inc'); }
    ?>
</section>
