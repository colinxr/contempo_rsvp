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

#### 2. Open the create-rsvp-tables.sql and change the table names. 
- Find the `****` inside of the table names and reaplce with 
- Replace the **** with corresponding event name, e.g. rsvp_coach_0917.
- Import into phpMyAdmin.

#### 3. Update config-sample.php
- Add the appropriate database info for local and production
- Update BASE_URL env to match url folder on local and production
- Update db table names to match the sql files edited previously
- Update event email info
- Rename to config.php

#### 4. Edit Event Info
- Open event-info.php
- Add pertinent event info based off of invite design
- Go into _inc/alerts/conf-msg.php and update the event info for the Add-To-Calendar button. 
- Update Sponosor logos

#### 5. Style page as necessary
- There are two set themes for contempo events. Theme One is a two column layout, with an image on the left and the event info on the right. Theme Two is a one column layout with a full bleed background image. Decide which layout you want to use for the event 

#### 6. Update confirmation emails 
- In email.class.php, update the event info in the email body 
- are the correct staff members receiving the unknown notifications, if applicable?

#### 7. Prepare list
- Make sure the CSV columns match the columns in rsvp.class.php and db.class.php
- Guest Of, First Name, Last Name, Company, Prof Level, Email, Email Lowercase, Gender, VIP Type
- Lowercase Email field is essential as it ensures a perfect match when user submits the form

#### 8. Set Partner RSVP
- If partners want to invite guests but won't provide their emails, rename the Open directory to the name of the partner
- change the PARTNER_RSVP constant to TRUE

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
