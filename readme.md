# Contempo_RSVP

## an easy event management rsvp tool for Contempo Media.

This is a simple event management tool built for Contempo Media.

### Some requirements

* Automatically approve guests whose emails are on our approved guest list, add them to db table and send them a confirmation email
* Pull in more information about the user from our guest list and store in db table
* Add unknown RSVPs to a separate table
* Allow Contempo staff to view unknown RSVPs and either approve or deny them for the event
* Allow Contempo staff members to upload the approved guest list
* Allow Contempo staff to manually set the kind of event RSVP Type
* If desired, let anyone rsvp for an email

## How to Use

#### 1. Clone the repo & install dependencies
In Terminal, `cd` into the directory and run the following commands:
- `npm install`
- `composer install`
- `gulp`

#### 2. Rename the sql files
- Replace the **** with corresponding event name, e.g. rsvp_coach_0917.
- Import into phpMyAdmin.

#### 3. Update the config-sample.php file found in the config directory
- Add the appropriate database info
- Update db table names to match the sql files edited previously
- Update BASEPATH constant to match url folder on server
- Update event confirmation emails, subject lines, hosts, emails, etc.
- Rename to config.php

#### 4. Edit Event Info
- Open event-info.php
- Add pertinent event info based off of invite design
- Coordinate with art if required. Some times it's easier to include some of this info as an one svg rather than html.
- Update Sponoso logos

#### 5. Style page as necessary
- Open main.scss in the styles directory
- Edit sass files as needed

#### 6. Update confirmation emails
-  In email.class.php, ensure email functions are sending the appropriate copy to the guests and to staff
- work with Art to update assets in the inc/emails directory
- are the correct staff members receiving the unknown notifications, if applicable?

#### 7. Prepare list
- Export the csv as Windows CSV in Excel. If the file is not formatted this way, php will not be able to read it properly and the RSVP Match function will break.
- Make sure the csv columns match the columns in rsvp.class.php and db.class.php

#### 8. Set Partner RSVP
- If partners want to invite guests but won't provide their emails, rename the Open directory to the name of the partner
- change the PARTNER_RSVP constant to FALSE

## Tests

Make sure the event is functioning as intended with these manual test

#### 1. List Upload & RSVP Type Change
- Did the list Upload correctly? It should be in Admin/List/event-list.csv
- is the RSVP Type changing in the DB? Are the messages displaying as expected?
#### 2. Match: Single RSVP and Guest RSVPs
- Are Match RSVPs functioning correctly? Added the the main dbTable, sent the appropriate email?
#### 3. Unknown: Single RSVPs and Guest RSVPs
- Are Unknown RSVPs being added to the Unknown dbTable? What confirmation message is displayed?
#### 4. Duplicate Submissions
- Duplicate entries should not be permitted. An 'Already Registered' confirmation screen should display.
#### 5. Unknwnr: Approved
- Approve someone from Unknwnr. Are they moved into main dbTable?
- What email is sent?
#### 6. Unknwnr: Denied
- Reject someone from Unknwnr. Are they removed from the Unknown dbTable?
- What email is sent?
#### 7. Partner RSVP Landing Page
- Set
