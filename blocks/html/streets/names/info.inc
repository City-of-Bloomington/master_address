<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @param Name $this->name
 */
declare (strict_types=1);
?>
<section>
    <header>
        <h1><?= parent::escape($this->name); ?></h1>
        <div class="tools">
        <?php
            $h = $this->template->getHelper('buttonLink');
            foreach ($this->actions as $a) {
                echo $h->buttonLink(
                    parent::generateUri("streetNames.$a", ['id'=>$this->name->id]),
                    $this->_($a),
                    'edit'
                );
            }
        ?>
        </div>
    </header>
    <p><?= parent::escape($this->name->notes); ?></p>
</section>
