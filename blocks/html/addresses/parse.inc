<?php
/**
 * @copyright 2011-2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @param ParseResponse $this->parse
 */
?>
<section>
    <header>
        <h1><?= $this->_('address_parse'); ?></h1>
    </header>
    <div>
        <form method="get">
            <fieldset>
            <?php
                $h = $this->template->getHelper('field');
                echo $h->field([
                    'name'  => 'address',
                    'id'    => 'address',
                    'label' => $this->_('address'),
                    'value' => !empty($_GET['address']) ? parent::escape($_GET['address']) : null
                ]);
            ?>
                <button type="submit" class="submit"><?= $this->_('parse'); ?></button>
            </fieldset>
        </form>
        <?php
            if ($this->parse) {
                echo '<table>';
                foreach ($this->parse as $k=>$v) {
                    if ($v) {
                        $v = parent::escape($v);
                        echo "<tr><th>$k</th><td>$v</td></tr>";
                    }
                }
                echo '</table>';
            }
        ?>
    </div>
</section>    
