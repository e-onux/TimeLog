
# TimeLog
Worktime Logging Application

# Requirements

PHP 7.4 or above.

# Installation

Clone project and install it:

    git clone https://github.com/e-onux/TimeLog.git;
    cd TimeLog/;
    
Edit ***DATABASE_URL*** value of ***/.env*** file to connect database.

    nano .env

Schema create:

    php bin/console doctrine:schema:create

Start Server:

    php bin/console server:start
Thats all...

# Usage
Time logging url:

    /
Project management url:

    /project
