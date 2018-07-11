<?php
/**
 * @copyright 2009-2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @param string $this->street         The search string
 * @param array  $this->streets        Array of street results
 * @param array  $this->hidden         Hidden inputs to preserve
 * @param Url    $this->callback_url   Url to link person results to
 * @param string $this->callback_field Parameter to add to the callback_url
 */
?>
<section>
    <header>
        <h1><?= $this->_('street_search'); ?></h1>
    </header>
    <form method="get">
        <fieldset><legend><?= $this->_('search'); ?></legend>
            <?php
                $h = $this->template->getHelper('field');
                echo $h->field([
                    'name'  => 'street',
                    'id'    => 'street',
                    'label' => $this->_('street'),
                    'value' => $this->street
                ]);

                foreach ($this->hidden as $k=>$v) {
                    echo "<input type=\"hidden\" name=\"$k\" value=\"$v\" />";
                }
            ?>
            <button type="submit" class="search"><?= $this->_('search'); ?></button>
        </fieldset>
    </form>
    <?php
        if (!$this->streets) {
            if ($this->street) {
                echo sprintf($this->_('no_results', 'messages'), $this->_(['street', 'streets', 2]));
            }
        }
        else {
            $this->_include('streets/list.inc');
        }
    ?>
</section>
