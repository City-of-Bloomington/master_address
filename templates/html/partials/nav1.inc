<nav id="nav1">
    <div class="container">
        <a href="<?= parent::generateUri('addresses.index'); ?>"><?= $this->_(['address', 'addresses', 100]); ?></a>
        <a href="<?= parent::generateUri(  'streets.index'); ?>"><?= $this->_(['street',  'streets',   100]); ?></a>
    </div>
</nav>
