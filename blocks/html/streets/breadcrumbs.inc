<?php
/**
 * @copyright 2009-2018 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 * @param Street $this->street (optional)
 */
?>
<nav class="breadcrumbs">
    <a href="<?php echo BASE_URL; ?>/streets">Streets</a>
    <?php
        if (isset($this->street)) {
            $name = View::escape($this->street->getStreetName());
            echo "<a href=\"{$this->street->getURL()}\">$name</a>";
        }
    ?>
</nav>
