#!/bin/sh

cd ../locale

cd en_GB/LC_MESSAGES
msgfmt messages.po
cd ../..

cd es_ES/LC_MESSAGES
msgfmt messages.po
cd ../..

cd fr_FR/LC_MESSAGES
msgfmt messages.po
cd ../..

cd it_IT/LC_MESSAGES
msgfmt messages.po
cd ../..

cd pt_PT/LC_MESSAGES
msgfmt messages.po
cd ../..

apachectl restart