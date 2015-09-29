# Local Planning Explorer Ireland

A joint submission to the [Open Data Challenge 2011](http://opendatachallenge.org/) by:

* DERI, NUI Galway, Ireland (main contact: Michael Hausenblas) 
* Local Government Computer Services Board, Ireland (main contact: Tim Willoughby)
* Fingal County Council, Ireland (main contact: Dominic Byrne)
* Freie Universitaet Berlin, Germany (main contact: Anja Jentzsch)


## What is the app about?

Allows to be notified about planning applications throughout Ireland (via feed or Twitter) as well as to understand historical developments.


## License

This software is Public Domain.


## Data preparation

    # Scrape DublinCity
    @@@ jobs/scrape_DublinCity.php -- need to script it
    # Scrape GalwayCo
    @@@ jobs/scrape_GalwayCo.php -- need to script it
    # Clean LGCSB dump
    setup/clean-applications.rb data/GPlan_Metadata.txt > data/applications.csv


## Application Setup

    # Initialize database
    mysql -u root -p < schema.sql
    mysql -u root -p < councils.sql

    # Import archive data
    php jobs/import_fingal_csv.php
    php jobs/import_scraperwiki.php --initial
    find data -name "GalwayCo*.csv" | xargs -n 1 jobs/import_GalwayCo.php
    cd data
    mysql -u root -p gplan < import-lgcsb-applications.sql
    mysql -u root -p gplan < import-DublinCity.sql


## Deploying to the live server

Only for admins obviously … You need to be in the DERI VPN.

    ssh planning-apps.opendata.ie
    cd /var/www/planning-apps.opendata.ie
    git pull


## Cronjobs

    # Daily
    php jobs/import_fingal_csv.php
    php jobs/import_scraperwiki.php
    php jobs/scrape_DublinCity.php --import
    php jobs/geocode.php 2000


## Permalinks

### Council details page

    http://planning-apps.opendata.ie/{council_short}

### Planning application details page

    http://planning-apps.opendata.ie/{council_short}#{app_ref}

{council_short} is a value from the councils.short_name table. See table below.

### Atom feeds

    http://planning-apps.opendata.ie/feed
    http://planning-apps.opendata.ie/feed/{council_short}


## API documentation

Note, that 'all' Ireland has the following bounding box: 51.779126,-12.045563,54.429541,-4.03653

### Look up application details

    http://planning-apps.opendata.ie/CorkCity/app?ref=11/34881

### Get TOP 50 applications near a point

    http://planning-apps.opendata.ie/near?center=53.270,-9.104

### Get all applications in area (limited to 250):

    http://planning-apps.opendata.ie/all?bounds=53.25,-9.1,53.5,-7
    http://planning-apps.opendata.ie/all?bounds=53.266971,-9.05961,53.27213,-9.043968

### Get latest applications in area:

    http://planning-apps.opendata.ie/latest?bounds=53.25,-9.1,53.5,-7
    http://planning-apps.opendata.ie/latest?bounds=53.266971,-9.05961,53.27213,-9.043968

### Get list of all councils as JSON

    http://planning-apps.opendata.ie/councils


## Database schema documentation

### The applications table

    +---------------+---------------+------+-----+---------+-------+
    | Field         | Type          | Null | Key | Default | Extra |
    +---------------+---------------+------+-----+---------+-------+
    | app_ref       | varchar(20)   | NO   | PRI | NULL    |       |
    | council_id    | int(2)        | NO   | PRI | NULL    |       |
    | lat           | double(13,10) | YES  | MUL | NULL    |       |
    | lng           | double(13,10) | YES  | MUL | NULL    |       |
    | applicant1    | text          | YES  |     | NULL    |       |
    | applicant2    | text          | YES  |     | NULL    |       |
    | applicant3    | text          | YES  |     | NULL    |       |
    | received_date | date          | NO   | MUL | NULL    |       |
    | decision_date | date          | YES  |     | NULL    |       |
    | address1      | text          | YES  |     | NULL    |       |
    | address2      | text          | YES  |     | NULL    |       |
    | address3      | text          | YES  |     | NULL    |       |
    | address4      | text          | YES  |     | NULL    |       |
    | decision      | char(1)       | NO   |     | NULL    |       |
    | status        | tinyint(4)    | NO   |     | NULL    |       |
    | details       | text          | YES  |     | NULL    |       |
    | url           | text          | YES  |     | NULL    |       |
    +---------------+---------------+------+-----+---------+-------+

### Table of applications statuses and decision codes

These explain the resepective columns of the application table.

    +----+-------------------------+
    | id | application status      |
    +----+-------------------------+
    |  0 | INCOMPLETED APPLICATION |
    |  1 | NEW APPLICATION         |
    |  2 | FURTHER INFORMATION     |
    |  3 | DECISION MADE           |
    |  4 | LEAVE TO APPEAL         |
    |  5 | APPEALED                |
    |  8 | WITHDRAWN               |
    |  9 | APPLICATION FINALISED   |
    | 10 | PRE-VALIDATION          |
    | 11 | DEEMED WITHDRAWN        |
    | 12 | APPEALED FINANCIAL      |
    | 13 | PENDING DECISION        |
    | 14 | UNKNOWN                 |
    +----+-------------------------+

    +----+---------------------------------+
    | id | decision code                   |
    +----+---------------------------------+
    | C  | ACCEPTED CONDITIONAL            |
    | N  | NO DECISION                     |
    | R  | REFUSED                         |
    | U  | ACCEPTED UNCONDITIONAL          |
    | D  | DECISION MADE BUT UNKNOWN TO US |
    +----+---------------------------------+

### Table of all councils

    +----+--------------+---------------------------------------+-----------+
    | id | short_name   | name                                  | county    |
    +----+--------------+---------------------------------------+-----------+
    |  2 | CorkCity     | Cork City Council                     | Cork      |
    |  3 | DublinCity   | Dublin City Council                   | Dublin    |
    |  4 | GalwayCity   | Galway City Council                   | Galway    |
    |  5 | LimerickCity | Limerick City Council                 | Limerick  |
    |  6 | Waterford    | Waterford City Council                | Waterford |
    |  7 | Carlow       | Carlow County Council                 | Carlow    |
    |  8 | Cavan        | Cavan County Council                  | Cavan     |
    |  9 | Clare        | Clare County Council                  | Clare     |
    | 10 | CorkCo       | Cork County Council                   | Cork      |
    | 14 | Donegal      | Donegal County Council                | Donegal   |
    | 15 | SouthDublin  | South Dublin County Council           | Dublin    |
    | 16 | DunLaoghaire | Dun Laoghaire Rathdown County Council | Dublin    |
    | 17 | Fingal       | Fingal County Council                 | Dublin    |
    | 18 | GalwayCo     | Galway County Council                 | Galway    |
    | 19 | Kerry        | Kerry County Council                  | Kerry     |
    | 20 | Kildare      | Kildare County Council                | Kildare   |
    | 21 | Kilkenny     | Kilkenny County Council               | Kilkenny  |
    | 22 | Laois        | Laois County Council                  | Laois     |
    | 23 | Leitrim      | Leitrim County Council                | Leitrim   |
    | 24 | LimerickCo   | Limerick County Council               | Limerick  |
    | 25 | Longford     | Longford County Council               | Longford  |
    | 26 | Louth        | Louth County Council                  | Louth     |
    | 27 | Mayo         | Mayo County Council                   | Mayo      |
    | 28 | Meath        | Meath County Council                  | Meath     |
    | 29 | Monaghan     | Monaghan County Council               | Monaghan  |
    | 30 | Offaly       | Offaly County Council                 | Offaly    |
    | 31 | Roscommon    | Roscommon County Council              | Roscommon |
    | 32 | Sligo        | Sligo County Council                  | Sligo     |
    | 33 | NTipperary   | North Tipperary County Council        | Tipperary |
    | 34 | STipperary   | South Tipperary County Council        | Tipperary |
    | 35 | WaterfordCo  | Waterford County Council              | Waterford |
    | 36 | Westmeath    | Westmeath County Council              | Meath     |
    | 37 | Wexford      | Wexford County Council                | Wexford   |
    | 38 | Wicklow      | Wicklow County Council                | Wicklow   |
    | 39 | Letterkenny  | Letterkenny Council                   | Donegal   |
    | 40 | Bundoran     | Bundoran Town Council                 | Donegal   |
    | 41 | Buncrana     | Buncrana Town Council                 | Donegal   |
    +----+--------------+---------------------------------------+-----------+


## Directory structure

### Stuff in /setup

This directory contains stuff for preparing data, setting up the
database, and importing the initial data.

clean-applications.rb
  Reads the LGCSB GPlan_Metadata.txt file, does various bits of cleaning,
  drops some columns that we don't need, and writes a deduplicated CSV
  file to stdout.

  Both the original GPlan_Metadata.txt file, and the cleaned
  applications.csv, are on @mhausenblas' dropbox.

schema.sql
  The database schema, including data for some of the small 
  auxiliary tables.

import-lgcsb-applications.sql
  A SQL script that imports the LGCSB application dump. Input
  is the cleaned CSV file derived from the original
  GPlan_Metadata.txt. It must be called applications.csv and
  be in the current directory.

initial_tweeting.php
  Populates the Twitter accounts with a few tweets. This should
  never be run again!


### Stuff in /jobs

This directory contains maintenance and update jobs that should be run
periodically via cron.

import_fingal_csv.php
  Import the CSV dump from data.fingal.ie

import_scraperwiki.php
  Import the latest updates from ScraperWiki. If run with --initial
  argument, it imports everything from the ScraperWiki data store.
  This should be once as part of the application setup.

send_tweets.php
  Send tweets for recently imported new applications
