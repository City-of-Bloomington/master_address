<?php
/**
 * @copyright 2009-2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 * @param array  $this->addresses  Array of Address entities
 * @param bool   $this->searching  Whether a search request was submitted or not
 *
 * @param string $this->street_number_prefix
 * @param string $this->street_number
 * @param string $this->street_number_suffix
 * @param string $this->street_direction
 * @param string $this->street_name
 * @param string $this->street_post_direction
 * @param string $this->street_suffix_code
 * @param string $this->city
 * @param string $this->zip
 * @param array  $this->directions  Options to populate dropdown
 * @param array  $this->cities      Options to populate dropdown
 * @param array  $this->streetTypes
 */
$cities      = [['value'=>'']];
$directions  = [['value'=>'']];
$streetTypes = [['value'=>'']];
foreach ($this->cities      as $c) { $cities     [] = ['value'=>$c]; }
foreach ($this->directions  as $d) { $directions [] = ['value'=>$d]; }
foreach ($this->streetTypes as $t) { $streetTypes[] = ['value'=>$t['id'], 'label'=>$t['code']]; }

?>
<section id="addressSearch">
    <header><h1><?= $this->_(['address', 'addresses', 100]); ?></h1></header>

    <?php
        if (!$this->addresses) {
            if (isset($_GET['address'])) {
                echo sprintf($this->_('no_results', 'messages'), $this->_(['address', 'addresses', 10]));
            }
        }
        else {
            $this->_include('addresses/list.inc');
        }
    ?>
</section>
