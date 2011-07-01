#!/bin/bash

clear

stty erase '^?'

echo "To install Magento, you will need a blank database ready with a user assigned to it."

echo
echo -n "Do you have all of your database information? (y/n) "

read dbinfo

if [ "$dbinfo" = "y" ]; then
    echo

    echo -n "Database Host (usually localhost): "
    read dbhost

    echo -n "Database Name: "
    read dbname

    echo -n "Database User: "
    read dbuser

    echo -n "Database Password: "
    read dbpass

    echo -n "Store URL: "
    read url

    echo -n "Admin Username: "
    read adminuser

    echo -n "Admin Password: "
    read adminpass

    echo -n "Admin First Name: "
    read adminfname

    echo -n "Admin Last Name: "
    read adminlname

    echo -n "Admin Email Address: "
    read adminemail

    echo -n "Include Sample Data? (y/n) "
    read sample

    if [ "$sample" = "y" ]; then
        echo
        echo "Now installing Magento with sample data..."

        echo
        echo "Downloading packages..."
        echo

        wget http://www.magentocommerce.com/downloads/assets/1.4.2.0/magento-1.4.2.0.tar.gz
        wget http://www.magentocommerce.com/downloads/assets/1.2.0/magento-sample-data-1.2.0.tar.gz

        echo
        echo "Extracting data..."
        echo

        tar -xvf magento-1.4.2.0.tar.gz
        tar -xvf magento-sample-data-1.2.0.tar.gz

        echo
        echo "Moving files..."
        echo

        mv magento-sample-data-1.2.0/media/* magento/media/
        mv magento-sample-data-1.2.0/magento_sample_data_for_1.2.0.sql magento/data.sql
        mv magento/* magento/.htaccess .

        echo
        echo "Setting permissions..."
        echo

        chmod 550 pear

        echo
        echo "Importing sample products..."
        echo

        mysql -h $dbhost -u $dbuser -p$dbpass $dbname < data.sql

        echo
        echo "Initializing PEAR registry..."
        echo
        ./pear channel-update pear.php.net
        ./pear upgrade --force PEAR
        ./pear upgrade --force PEAR
        ./pear mage-setup .

        echo
        echo "Downloading packages..."
        echo

        ./pear install magento-core/Mage_All_Latest
        ./pear install magento-community/Yoast_MetaRobots
        ./pear install magento-community/canonical_url
        ./pear install magento-community/Flagbit_GoogleWebmasterTools
        ./pear install magento-community/Fooman_Speedster
        ./pear install magento-community/Locale_Mage_community_pt_BR
        ./pear install magento-community/Fooman_GoogleAnalyticsPlus
        ./pear install magento-community/Yoast_Blank_Seo_Theme
        ./pear install magento-community/BusinessDecision_Interaktingslider
        ./pear install magento-community/Cloudzoom
        ./pear install magento-community/OrangeValley_YahooAnalytics
        ./pear install magento-community/PedroTeixeira_Correios
        ./pear install magento-community/Bragento_BrazilianRegions
        ./pear install magento-community/BrunoAssarisse_PagSeguro
        ./pear install magento-community/Fooman_SameOrderInvoiceNumber
        ./pear install magento-community/Mxperts_Newsemail




        echo
        echo "Cleaning up files..."
        echo

        rm -rf downloader/pearlib/cache/* downloader/pearlib/download/*
        rm -rf magento/ magento-sample-data-1.2.0/
        rm -rf magento-1.4.2.0.tar.gz magento-sample-data-1.2.0.tar.gz
        rm -rf index.php.sample .htaccess.sample php.ini.sample *.txt data.sql

        echo
        echo "Installing Magento..."
        echo

        php -f install.php -- \
        --license_agreement_accepted "yes" \
        --locale "pt_BR" \
        --timezone "America/Sao_Paulo" \
        --default_currency "BRL" \
        --db_host "$dbhost" \
        --db_name "$dbname" \
        --db_user "$dbuser" \
        --db_pass "$dbpass" \
        --url "$url" \
        --use_rewrites "yes" \
        --use_secure "no" \
        --secure_base_url "" \
        --use_secure_admin "no" \
        --admin_firstname "$adminfname" \
        --admin_lastname "$adminlname" \
        --admin_email "$adminemail" \
        --admin_username "$adminuser" \
        --admin_password "$adminpass"

        echo
        echo "Finished installing Magento"
        echo

        exit
    else
        echo "Now installing Magento without sample data..."

        echo
        echo "Downloading packages..."
        echo

        wget http://www.magentocommerce.com/downloads/assets/1.4.2.0/magento-1.4.2.0.tar.gz

        echo
        echo "Extracting data..."
        echo

        tar -xvf magento-1.4.2.0.tar.gz

        echo
        echo "Moving files..."
        echo

        mv magento/* magento/.htaccess .

        echo
        echo "Setting permissions..."
        echo

        chmod 550 pear

        echo
        echo "Initializing PEAR registry..."
        echo
        ./pear channel-update pear.php.net
        ./pear upgrade --force PEAR
        ./pear upgrade --force PEAR
        ./pear mage-setup .

        echo
        echo "Downloading packages..."
        echo

        ./pear install magento-core/Mage_All_Latest
        ./pear install magento-community/Yoast_MetaRobots
        ./pear install magento-community/canonical_url
        ./pear install magento-community/Flagbit_GoogleWebmasterTools
        ./pear install magento-community/Fooman_Speedster
        ./pear install magento-community/Locale_Mage_community_pt_BR
        ./pear install magento-community/Fooman_GoogleAnalyticsPlus
        ./pear install magento-community/Yoast_Blank_Seo_Theme
        ./pear install magento-community/OrangeValley_YahooAnalytics
        ./pear install magento-community/PedroTeixeira_Correios
        ./pear install magento-community/Bragento_BrazilianRegions
        ./pear install magento-community/Fooman_SameOrderInvoiceNumber
        ./pear install magento-community/Mxperts_Newsemail
        ./pear install magento-community/MultiBanner_Module
        ./pear install magento-community/Auctionmaid_Matrxrate


        echo
        echo "Cleaning up files..."
        echo

        rm -rf downloader/pearlib/cache/* downloader/pearlib/download/*
        rm -rf magento/ magento-1.4.2.0.tar.gz
        rm -rf index.php.sample .htaccess.sample php.ini.sample *.txt

        echo
        echo "Installing Magento..."
        echo

        php-cli -f install.php -- \
        --license_agreement_accepted "yes" \
        --locale "pt_BR" \
        --timezone "America/Sao_Paulo" \
        --default_currency "BRL" \
        --db_host "$dbhost" \
        --db_name "$dbname" \
        --db_user "$dbuser" \
        --db_pass "$dbpass" \
        --url "$url" \
        --use_rewrites "yes" \
        --use_secure "no" \
        --secure_base_url "" \
        --use_secure_admin "no" \
        --admin_firstname "$adminfname" \
        --admin_lastname "$adminlname" \
        --admin_email "$adminemail" \
        --admin_username "$adminuser" \
        --admin_password "$adminpass"

        echo
        echo "Finished installing Magento"

        exit
    fi
else
    echo
    echo "Please setup a database first. Don't forget to assign a database user!"

    exit
fi

