#!/bin/bash


( crontab -l ; echo "* 1 * * * ./generate_sitemap.sh" ) | crontab -