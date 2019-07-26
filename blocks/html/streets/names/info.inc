<?php
/**
 * @copyright 2018-2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 * @param Name $this->name
 * @param bool $this->disableButtons (optional)
 */
declare (strict_types=1);
?>
<section>
    <header>
        <h1><?= parent::escape($this->name); ?></h1>
        <div class="tools">
        <?php
            if (!$this->disableButtons) {
                $h = $this->template->getHelper('buttonLink');
                if (parent::isAllowed('streetNames','correct')) {
                    echo $h->buttonLink(
                        parent::generateUri('streetNames.correct', ['id'=>$this->name->id]),
                        $this->_('correct'),
                        'edit'
                    );
                }
            }
        ?>
        </div>
    </header>
    <p><?= parent::escape($this->name->notes); ?></p>
</section>
