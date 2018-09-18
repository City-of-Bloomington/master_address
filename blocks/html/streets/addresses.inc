<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl-3.0.txt GNU/AGPL, see LICENSE
 * @param string $this->street_id
 * @param array  $this->addresses
 */
declare (strict_types=1);
use Application\Url;
?>
<section>
    <header>
        <h2><?= $this->_(['address', 'addresses', count($this->addresses)]); ?></h2>
        <?php
            if (parent::isAllowed('addresses', 'add')) {
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
    </header>
    <?php $this->_include('addresses/list.inc'); ?>
</section>
