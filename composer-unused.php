<?php

declare(strict_types=1);

use ComposerUnused\ComposerUnused\Configuration\Configuration;
use ComposerUnused\ComposerUnused\Configuration\NamedFilter;

return static function (Configuration $config): Configuration {
    return $config
        // This is stupid, but if the symfony/mailer is not the production dependencies we will get an exception like
        // The service "sylius.email_sender" has a dependency on a non-existent service "sylius.email_sender.adapter.symfony_mailer". Did you mean one of these: "sylius.email_renderer.adapter.default", "sylius.email_sender.adapter.default"?
        // in the test application....
        ->addNamedFilter(NamedFilter::fromString('symfony/mailer'))
    ;
};
