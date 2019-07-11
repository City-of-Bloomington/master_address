<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 * @param array $this->zipCodes  Zip Code data
 */
declare (strict_types=1);
?>
<section>
    <header><h1><?= $this->_(['zip', 'zips', count($this->zipCodes)]); ?></h1></header>

    <table>
        <thead>
            <tr><th><?= $this->_('zip'  ); ?></th>
                <th><?= $this->_('city' ); ?></th>
                <th><?= $this->_('state'); ?></th>
            </tr>
        </thead>
        <tbody>
        <?php
            foreach ($this->zipCodes as $z) {
                echo "
                <tr><th>$z[zip]</th>
                    <td>$z[city]</td>
                    <td>$z[state]</td>
                </tr>
                ";
            }
        ?>
        </tbody>
    </table>
</section>
