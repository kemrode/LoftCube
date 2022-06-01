<?php

namespace App;

/**
 * Application configuration
 *
 * PHP version 7.0
 */
class Config
{

    /**
     * Database host
     * @var string
     */
    const DB_HOST = 'bdd';

    /**
     * Database name
     * @var string
     */
    const DB_NAME = 'loftcube';

    /**
     * Database user
     * @var string
     */
    const DB_USER = 'loftcube';

    /**
     * Database password
     * @var string
     */
    const DB_PASSWORD = 'loftcubepassword';

    /**
     * Affiche une belle page d'erreur si TRUE, affiche les details de l'erreur si false (Pour le dev)
     * @var boolean
     */
    const SHOW_ERRORS = true;
}
