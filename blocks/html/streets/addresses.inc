<?php
/**
 * @copyright 2018-2019 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl-3.0.txt GNU/AGPL, see LICENSE
 * @param string $this->street_id
 * @param array  $this->addresses
 * @param bool   $this->disableButtons (optional)
 */
declare (strict_types=1);
use Application\Url;
?>
<section>
    <header>
        <h2><?= $this->_(['address', 'addresses', count($this->addresses)]); ?></h2>
        <div class="tools">
        <?php
            $userCanAdd = !$this->disableButtons && parent::isAllowed('addresses', 'add');

            if ($userCanAdd) {
                $h = $this->template->getHelper('buttonLink');
                echo $h->buttonLink(
                    parent::generateUri('addresses.add').'?'.http_build_query([
                        'street_id'  => $this->street_id,
                        'return_url' => Url::current_url(BASE_HOST)
                    ]),
                    $this->_('address_add'),
                    'add'
                );
            }
        ?>
        </div>
    </header>
    <?php $this->_include('addresses/list.inc'); ?>
</section>
