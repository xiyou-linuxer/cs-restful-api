#!/bin/bash

ext_param='php'
ignore_param='vendor/*,storage/*,resources/*,bootstrap/cache/*,/Console/*,app/Exception/*,app/Jobs/*,app/Providers/*,app/Events/*,app/Listeners/*,app/Policies/*,app/Http/Kernel,app/Http/Middleware/*,app/Exceptions/*,app/Http/Controllers/Controller.php,database/seeds/*,database/factories/*'

phpcs --ignore=$ignore_param --extensions=$ext_param .
