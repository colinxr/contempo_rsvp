# Contempo_RSVP

## an easy event management rsvp tool for Contempo Media.

This is a simple event tool  

### Some requirements

* Automatically approve guests whose emails are on our approve guest list, add them to db table and send them a confirmation email
* Pull in more information about the user from our guest list and store in db table
* Add unknown RSVPs to a separate table
* Allow Contempo staff members to view unknown RSVPs and either approve or deny them for the event
* If desired, let anyone rsvp for an email
* Create a reusable and maintainable codebase and front-end template

## How to Use

#### 1. Clone the repo & install dependencies
In Terminal, `cd` into the directory and run the following commands:
- `npm install`
- `composer install`

#### 2. Rename the sql files
- Replace the **** with corresponding event name, e.g. rsvp_coach_0917.
- Import into phpMyAdmin.

#### 3. Update the config-sample.php file found in the inc directory
- Add the appropriate database info
- Update db table names to match the sql files edited previously
- Update basepath to match url folder on server
- Update event confirmation emails, subject lines, hosts, emails, etc.
- rename config.php

#### 4. Edit Event Info
- Open inc/event-info.php
- Add pertinent event info based off of invite design
- Coordinate with art if required. Some times it's easier to include some of this info as an svg rather than html.

#### 5. Style page as neccessary
- Open main.scss in the styles directory
- Edit sass files as needed
- In Terminal, run the `Gulp` command to compile sass into css

#### 6. Update confirmation emails
-  In src/emails.php, make sure email functions are sending the appropriate copy to the guests and to staff
- Is the correct staff members receiving the unknown notifications, if applicable?

#### 7. Prepare list
- Do the columns match the columns in validate.php?
- Export csv as Windows CSV in Excel. If the file is not formatted this way, php will not be able to read it properly and the RSPV Match function will break.

## Tests

Make sure the event is functioning as intended with these manual test

#### 1. Match: Single RSVP and Guest RSVPs
- Are Match RSVPs functioning correctly? Added the the main dbTable, sent the appropriate email?
#### 2. Unknown: Single RSVPs and Guest RSVPs
- Are Unknown RSVPs being added to the Unknown dbTable? What confirmation message is displayed?
#### 3. Duplicate Submissions
- Duplicate entries should not be permitted. An 'Already Registered' confirmation screen should display.
#### 4. Unknwnr: Approved
- Approve someone from Unknwnr. Are they moved into main dbTable. What email is sent?
#### 5. Unknwnr: Denied
- Reject someone from Unknwnr. Are they removed from the Unknown dbTable? What email is sent?
