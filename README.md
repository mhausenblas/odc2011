# All-Irish Planning Applications

A joint submission to the [Open Data Challenge 2011](http://opendatachallenge.org/) by:

* DERI, NUI Galway, Ireland (main contact: Michael Hausenblas) 
* Local Government Computer Services Board, Ireland (main contact: Tim Willoughby)
* Fingal County Council, Ireland (main contact: Dominic Byrne)
* Freie Universitaet Berlin, Germany (main contact: Anja Jentzsch)

## What is the app about?

Allows to be notified about planning applications throughout Ireland (via feed or Twitter) as well as to understand historical developments.

## License

This software is Public Domain.

## Application Setup

    clean-applications.rb GPlan_Metadata.txt > applications.csv
    mysql -u root < schema.sql
    mysql -u root gplan < import-lgcsb-applications.sql
    # Run the Fingal importer once
    php jobs/import_fingal_csv.php
    import-scraperwiki.php


## API documentation

Note, that 'all' Ireland has the following bounding box: 51.779126,-12.045563,54.429541,-4.03653

Get TOP 50 applications near a point
http://localhost:8888/gplan/near?center=53.270,-9.104

Get all applications in area (limited to 250):
http://localhost:8888/gplan/all?bounds=53.25,-9.1,53.5,-7

Get latest applications in area:
http://localhost:8888/gplan/latest?bounds=53.25,-9.1,53.5,-7


## Database schema documentation

Table of applications statuses and decision codes

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


## What's in here?

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
  auxilliary tables.

import-lgcsb-applications.sql
  A SQL script that imports the LGCSB application dump. Input
  is the cleaned CSV file derived from the original
  GPlan_Metadata.txt. It must be called applications.csv and
  be in the current directory.

initial_import_scraperwiki.php
  Imports all applications that ScraperWiki has scraped so
  far.

initial_tweeting.php
  Populates the Twitter accounts with a few tweets. This should
  never be run again!


### Stuff in /jobs

This directory contains maintenance and update jobs that should be run
periodically via cron.

import_fingal_csv.php
  Import the CSV dump from data.fingal.ie

import_scraperwiki.php
  Import the latest updates from ScraperWiki

send_tweets.php
  Send tweets for recently imported new applications
