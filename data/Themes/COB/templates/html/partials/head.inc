<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="https://bloomington.in.gov/static/citylogo-64.png" />
    <?php $this->_include('partials/stylesheets.inc'); ?>
    <title><?= isset($this->title) ? self::escape($this->title) : APPLICATION_NAME; ?></title>
    <script type="text/javascript">
        var BASE_URL = '<?= BASE_URL; ?>',
            BASE_URI = '<?= BASE_URI; ?>';
    </script>
</head>